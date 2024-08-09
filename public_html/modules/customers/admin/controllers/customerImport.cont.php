<?php

$iStart = microtime(true);
# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout                = new PageLayout();
$oPageLayout->sWindowTitle  = 'Klanten import';
$oPageLayout->sTemplateName = 'Klanten import';

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// column mapping. Columms are always in the language chosen and are mapped to the right fields in the database
$aImportFields = [
    ['prop' => 'debNr', 'column' => 'Bedrijven.Debiteurennummer', 'required' => true],
    ['prop' => 'companyName', 'column' => 'Bedrijven.Naam', 'required' => true],
    ['prop' => 'companyAddress', 'column' => 'Bedrijven.Straat', 'required' => true],
    ['prop' => 'companyPostalCode', 'column' => 'Bedrijven.Postcode', 'required' => false],
    ['prop' => 'companyCity', 'column' => 'Bedrijven.Plaatsnaam', 'required' => true],
    ['prop' => 'companyEmail', 'column' => 'Emailadres', 'required' => false],
    ['prop' => 'Phone', 'column' => 'Bedrijven.Telefoon', 'required' => false],
    ['prop' => 'contactPersonName', 'column' => 'Contactpersoon', 'required' => false],
    ['prop' => 'contactPersonEmail', 'column' => 'Contact.Email', 'required' => false],
    ['prop' => 'contactPersonPhone', 'column' => 'Contact.Telefoon', 'required' => false],


];

$aRequiredColumns  = ['Bedrijven.Debiteurennummer', 'Bedrijven.Naam', 'Bedrijven.Straat', 'Bedrijven.Plaatsnaam'];
$aSupportedColumns = [];
foreach ($aImportFields AS $aImportField) {
    $aSupportedColumns[$aImportField['column']] = $aImportField['prop'];
}



// flip cols to use when invalid objects get tried to be saved
$aSupportedProps = array_flip($aSupportedColumns);

$aLogs = [];
if (http_post('action') == 'import' && CSRFSynchronizerToken::validate()) {

    $iTotal    = 0;
    $iSaved    = 0;
    $iErrors   = 0;
    $iWarnings = 0;

    $bTestMode = http_get('testMode', false);
    if (!empty($_FILES['file']) && $_FILES['file']['error'] === 0) {

        $sFile         = $_FILES['file']['name'];
        $sExtension    = strtolower(pathinfo($sFile, PATHINFO_EXTENSION) ?? '');
        $sFileName     = strtolower(pathinfo($sFile, PATHINFO_FILENAME) ?? '');
        $sFileLocation = $_FILES['file']['tmp_name'];

        try {
            switch ($sExtension) {
                case 'xls':
                    $oReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($sExtension));
                    $oReader->setReadDataOnly(true);
                    $oPHPExcel = $oReader->load($sFileLocation);
                    break;
                case 'xlsx':
                    $oReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($sExtension));
                    $oReader->setReadDataOnly(true);
                    $oPHPExcel = $oReader->load($sFileLocation);
                    break;
                default:
                    $oPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($sFileLocation);
                    break;
            }
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            $aErrors[] = 'Bestand kon niet worden gelezen';
        }

        if (!empty($oPHPExcel)) {
            $oSheet = $oPHPExcel->getSheet(0);

            // prepare rows
            $aColumns = [];
            $aValues  = [];
            foreach ($oSheet->getRowIterator() as $oRow) {
                $oCellIterator = $oRow->getCellIterator();
                $oCellIterator->setIterateOnlyExistingCells(false);
                if ($oRow->getRowIndex() == 1) {
                    $bSetColumns = true;
                } else {
                    $bSetColumns = false;
                }
                foreach ($oCellIterator as $oCell) {
                    if ($bSetColumns) {
                        $aColumns[$oCell->getColumn()] = $oCell->getValue();
                        continue;
                    }
                    $aValues[$oCell->getRow()][$aColumns[$oCell->getColumn()]] = $oCell->getValue();
                }
            }

            $bIsMissingRequired = false;
            foreach ($aRequiredColumns AS $sColumn) {
                if (!in_array($sColumn, $aColumns)) {
                    $aErrors[]          = 'Verplichte kolom `' . $sColumn . '` mist';
                    $bIsMissingRequired = true;
                }
            }

            // not missing required fields?
            if (!$bIsMissingRequired) {
                $aColumnsByName = @array_flip($aColumns);
                foreach ($aValues AS $iRow => $aValue) {

                    $sEmail = $aValue['Emailadres'];

                    if (($oCustomer = CustomerManager::getCustomerByDebNr($aValue['Bedrijven.Debiteurennummer']))) {
                        $aCustomerGroups = $oCustomer->getCustomerGroups();
                    } else {
                        $oCustomer = new Customer();
                        $oCustomer->setDefaultCsvImportValues();
                        $aCustomerGroups = [];
                    }

                    foreach ($aValue AS $sColumn => $sValue) {

                        if (array_key_exists($sColumn, $aSupportedColumns)) {

                            $sProp             = $aSupportedColumns[$sColumn];
                            $oCustomer->$sProp = $sValue;

                        } else {
                            $aLogs[$iRow]['warnings'][] = 'Kolom is niet ondersteund en wordt niet opgeslagen `' . $sColumn . '`';
                            $iWarnings++;
                        }
                    }

                    //_d($oCustomer);

                    //$oCustomer->companyEmail = $oCustomer->contactPersonEmail;
                    //die;

                    // add customer groups, no problem that there are already some groups.
                    foreach (http_post('customerGroupIds', []) AS $iCustomerGroupId) {
                        $aCustomerGroups[] = new CustomerGroup(['customerGroupId' => $iCustomerGroupId]);
                    }

                    // set groups in customer
                    $oCustomer->setCustomerGroups($aCustomerGroups);
                    if ($oCustomer->isValid()) {
                        if (!empty($oCustomer->customerId)) {
                            $aLogs[$iRow]['saved'][] = 'Klant gevonden';
                        } else {
                            $aLogs[$iRow]['saved'][] = 'Nieuwe klant';
                        }
                        if (!$bTestMode) {
                            CustomerManager::saveCustomer($oCustomer, false);
                        }
                        $aLogs[$iRow]['saved'][] = 'Klant opgeslagen `' . $oCustomer->getFullName() . '`';
                        $iSaved++;
                    } else {
                        foreach ($oCustomer->getInvalidProps() AS $sInvalidProp) {
                            //not a supported property, so do fill in another way
                            if (empty($aSupportedProps[$sInvalidProp])) {
                                // some properties are not supported to fill by import file. Do set in another way because like this they are not valid.
                                _d('Property not supported by import, fill another way');
                                _d($sInvalidProp);
                                continue;
                            }
                            $sColumn                  = $aSupportedProps[$sInvalidProp];
                            $aLogs[$iRow]['errors'][] = 'Waarde `' . ($oCustomer->$sInvalidProp ? $oCustomer->$sInvalidProp : 'LEEG') . '` is niet juist voor kolom`' . $sColumn . '` op regelnummer ' . $iRow;
                            $iErrors++;
                        }
                        $aLogs[$iRow]['errors'][] = 'Klant kan niet worden opgeslagen `' . $oCustomer->getFullName() . '`';
                    }
                    $iTotal++;
                }
            }
        }
    } else {
        $aErrors[] = 'Fout bij uploaden bestand';
    }
}

$oPageLayout->sViewPath = getAdminView('customerImport/customerImport_form', 'customers');

# include default template
include_once getAdminView('layout');
?>
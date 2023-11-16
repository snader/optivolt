<?php

$iStart = microtime(true);
# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout                = new PageLayout();
$oPageLayout->sWindowTitle  = 'Locaties & systemen import';
$oPageLayout->sTemplateName = 'Locaties & systemen import';

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// column mapping. Columms are always in the language chosen and are mapped to the right fields in the database
$aImportFields = [
    ['prop' => 'debNr', 'column' => 'Bedrijven.Debiteurennummer', 'required' => true],
    ['prop' => 'locationName', 'column' => 'Locatie.Naam', 'required' => true],
    ['prop' => 'floor', 'column' => 'Systeem.Verdieping', 'required' => false],
    ['prop' => 'position', 'column' => 'Pos', 'required' => true],
    ['prop' => 'systemType', 'column' => 'Systeem.Type', 'required' => true],
    ['prop' => 'systemName', 'column' => 'Systeem.Naam', 'required' => false],
    ['prop' => 'systemModel', 'column' => 'Systeem.Model', 'required' => true],
    ['prop' => 'systemMachineNr', 'column' => 'Systeem.MachineNr', 'required' => false],

];

$aRequiredColumns  = ['Bedrijven.Debiteurennummer', 'Locatie.Naam', 'Pos', 'Systeem.Type', 'Systeem.Model']; // 'Systeem.Naam',
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
        $sExtension    = strtolower(pathinfo($sFile, PATHINFO_EXTENSION));
        $sFileName     = strtolower(pathinfo($sFile, PATHINFO_FILENAME));
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

                    // lege regel
                    if (empty($aValue['Bedrijven.Debiteurennummer']) && empty($aValue['Pos']) && empty($aValue['Locatie.Naam'])) {

                        $iWarnings++;
                        $aLogs[$iRow]['saved'][] = 'Lege regel gevonden';
                        continue;
                    }

                    if (($oCustomer = CustomerManager::getCustomerByDebNr(trim($aValue['Bedrijven.Debiteurennummer'])))) {
                        # klant bestaat
                    } else {
                        $aLogs[$iRow]['warnings'][] = 'Klant met debiteurennummer `' . $aValue['Bedrijven.Debiteurennummer'] . '` niet gevonden';
                        $iWarnings++;
                        continue;
                    }

                    if (($oLocation = LocationManager::getLocationByName($aValue['Locatie.Naam'], $oCustomer->customerId))) {
                        # locatie bestaat
                    } else {
                        $aLogs[$iRow]['saved'][] = 'Nieuwe locatie';
                        $oLocation = new Location();
                        $oLocation->name = _e($aValue['Locatie.Naam']);
                        $oLocation->customerId = $oCustomer->customerId;
                        if ($oLocation->isValid()) {
                            LocationManager::saveLocation($oLocation); //save object
                        } else {
                            $aLogs[$iRow]['warnings'][] = 'Locatie `' . _e($aValue['Locatie.Naam']) . '` kon niet worden opgeslagen';
                            $iWarnings++;
                            continue;
                        }
                    }

                    if (empty($aValue['Systeem.Naam'])) {
                        $aValue['Systeem.Naam'] = ucfirst($aValue['Systeem.Type']);
                    }

                    if (!empty($aValue['Pos']) && !empty($aValue['Systeem.Type']) && !empty($aValue['Systeem.Model'])) {

                        $oSystem = SystemManager::getSystemByNamePosLocationId($oLocation->locationId, trim($aValue['Pos']), trim($aValue['Systeem.Naam']));

                        if (!$oSystem) {
                            $aLogs[$iRow]['saved'][] = 'Nieuw systeem';
                            $oSystem = new System();
                            $oSystem->locationId = $oLocation->locationId;
                        }

                        if (is_numeric(trim($aValue['Systeem.Type'])) && trim($aValue['Systeem.Type']) < 4 && trim($aValue['Systeem.Type']) > 0) {
                            $oSystem->systemTypeId = trim($aValue['Systeem.Type']);
                        } else {

                            if (strtolower(trim($aValue['Systeem.Type'])) == "multiliner") {
                                $oSystem->systemTypeId = System::SYSTEM_TYPE_MULTILINER;
                            }
                            if (strtolower(trim($aValue['Systeem.Type'])) == "powerliner") {
                                $oSystem->systemTypeId = System::SYSTEM_TYPE_POWERLINER;
                            }
                            if (strtolower(trim($aValue['Systeem.Type'])) == "vliner" || strtolower(trim($aValue['Systeem.Naam'])) == "v-liner") {
                                $oSystem->systemTypeId = System::SYSTEM_TYPE_VLINER;
                            }
                        }

                        if (empty($oSystem->systemTypeId)) {
                            $oSystem->systemTypeId = System::SYSTEM_TYPE_MULTILINER;
                        }

                        $oSystem->pos = trim($aValue['Pos']);
                        $oSystem->name = trim($aValue['Systeem.Naam']);
                        $oSystem->model = trim($aValue['Systeem.Model']);
                        if (isset($aValue['Systeem.MachineNr'])) {
                            $oSystem->machineNr = trim($aValue['Systeem.MachineNr']);
                        }
                        if (isset($aValue['Systeem.Verdieping'])) {
                            $oSystem->floor = trim($aValue['Systeem.Verdieping']);
                        }



                        if ($oSystem->isValid()) {

                            SystemManager::saveSystem($oSystem); //save object

                            $aLogs[$iRow]['saved'][] = 'Systeem opgeslagen `' . $oSystem->pos . ' ' .$oSystem->name . '`';
                            $iSaved++;
                        } else {


                            $aLogs[$iRow]['warnings'][] = 'Systeem `' . _e($aValue['Locatie.Naam']) . ' - ' . $aValue['Pos'] . ' ' . $aValue['Systeem.Naam'] . '` kon niet worden opgeslagen';
                            $iWarnings++;
                            continue;
                        }


                    }


                    $iTotal++;
                }
            }
        }
    } else {
        $aErrors[] = 'Fout bij uploaden bestand';
    }
}

$oPageLayout->sViewPath = getAdminView('systemImport/systemImport_form', 'customers');

# include default template
include_once getAdminView('layout');
?>
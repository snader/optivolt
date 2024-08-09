<?php

$iStart = microtime(true);
# check if controller is required by index.php
if (!defined('ACCESS')) {
  die;
}

global $oPageLayout;

$oPageLayout                = new PageLayout();
$oPageLayout->sWindowTitle  = 'Systemen installatiedatum import';
$oPageLayout->sTemplateName = 'Systemen installatiedatum import';

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// column mapping. Columms are always in the language chosen and are mapped to the right fields in the database
$aImportFields = [
  ['prop' => 'installDate', 'column' => 'Installatiedatum', 'required' => true],
  ['prop' => 'debNr', 'column' => 'Financieel.Debiteurennummer', 'required' => true]

];

$aRequiredColumns  = ['Installatiedatum', 'Financieel.Debiteurennummer']; // 'Systeem.Naam',
$aSupportedColumns = [];
foreach ($aImportFields as $aImportField) {
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
      foreach ($aRequiredColumns as $sColumn) {
        if (!in_array($sColumn, $aColumns)) {
          $aErrors[]          = 'Verplichte kolom `' . $sColumn . '` mist';
          $bIsMissingRequired = true;
        }
      }

      // not missing required fields?
      if (!$bIsMissingRequired) {
        $aColumnsByName = @array_flip($aColumns);

        foreach ($aValues as $iRow => $aValue) {

          // lege regel
          if (empty($aValue['Financieel.Debiteurennummer']) || empty($aValue['Installatiedatum'])) {

            $iWarnings++;
            $aLogs[$iRow]['saved'][] = 'Regel met missende data gevonden';
            continue;
          }

          if (($oCustomer = CustomerManager::getCustomerByDebNr(trim($aValue['Financieel.Debiteurennummer'])))) {
            # klant bestaat
          } else {
            $aLogs[$iRow]['warnings'][] = 'Klant met debiteurennummer `' . $aValue['Financieel.Debiteurennummer'] . '` niet gevonden';
            $iWarnings++;
            continue;
          }

          $aFilter['customerId'] = $oCustomer->customerId;
          $aLocations = LocationManager::getLocationsByFilter($aFilter);


          foreach ($aLocations as $oLocation) {

            $aSystems = $oLocation->getSystems();
            foreach ($aSystems as $oSystem) {

              $oSystem->installDate = trim($aValue['Installatiedatum']);

              if ($oSystem->isValid()) {

                SystemManager::saveSystem($oSystem); //save object

                $aLogs[$iRow]['saved'][] = 'Systeem opgeslagen `' . $oSystem->pos . ' ' . $oSystem->name . '`' . ' - ' . $aValue['Installatiedatum'];
                $iSaved++;
              } else {


                $aLogs[$iRow]['warnings'][] = 'Systeem `' . _e($aValue['Locatie.Naam']) . ' - ' . $aValue['Pos'] . ' ' . $aValue['Systeem.Naam'] . '` kon niet worden opgeslagen';
                $iWarnings++;
                continue;
              }
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


$oPageLayout->sViewPath = getAdminView('systemImport/systemImportDate_form', 'customers');

# include default template
include_once getAdminView('layout');

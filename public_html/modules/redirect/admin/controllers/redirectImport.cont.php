<?php

$iStart = microtime(true);
# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('redirectImportTitle');
$oPageLayout->sModuleName  = sysTranslations::get('redirectImportTitle');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

$aImportFields = [
    ['prop' => 'pattern', 'column' => 'oldUrl', 'required' => true],
    ['prop' => 'newUrl', 'column' => 'newUrl', 'required' => true],
];

$aRequiredColumns  = ['oldUrl', 'newUrl'];
$aSupportedColumns = [];
foreach ($aImportFields AS $aImportField) {
    $aSupportedColumns[$aImportField['column']] = $aImportField['prop'];
}

// flip cols to use when invalid objects get tried to be saved
$aSupportedProps = array_flip($aSupportedColumns);

$aErrors = [];
$aLogs   = [];
if (http_post('action') == 'import' && CSRFSynchronizerToken::validate()) {

    $iTotal    = 0;
    $iSaved    = 0;
    $iErrors   = 0;
    $iWarnings = 0;

    $bTestMode = http_post('testMode', false);
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
                    $oPHPSpreadsheet = $oReader->load($sFileLocation);
                    break;
                case 'xlsx':
                    $oReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($sExtension));
                    $oReader->setReadDataOnly(true);
                    $oPHPSpreadsheet = $oReader->load($sFileLocation);
                    break;
                default:
                    $oPHPSpreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sFileLocation);
                    break;
            }
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            $aErrors[] = sysTranslations::get('redirectFileCouldNotBeRead');
        }

        if (!empty($oPHPSpreadsheet)) {
            $oSheet = $oPHPSpreadsheet->getSheet(0);

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
                    $aErrors[]          = sysTranslations::get('redirectMandatoryColumn') . ' `' . $sColumn . '` mist';
                    $bIsMissingRequired = true;
                }
            }

            // not missing required fields?
            if (!$bIsMissingRequired) {
                $aColumnsByName = array_flip($aColumns);
                foreach ($aValues AS $iRow => $aValue) {
                    $sPattern  = $aValue['oldUrl'];
                    $sNewUrl   = $aValue['newUrl'];
                    $oRedirect = RedirectManager::getRedirectByPattern($sPattern);

                    if (!empty($oRedirect)) {
                        $aLogs[$iRow]['warnings'][] = sysTranslations::get('redirectColomnOverwrite') . ' `' . $sColumn . '`';
                        RedirectManager::updateForImport($sNewUrl, $oRedirect->redirectId);
                    } else {

                        $oRedirect       = new Redirect();
                        $oRedirect->type = Redirect::TYPE_SPECIFIC;
                        foreach ($aValue AS $sColumn => $sValue) {
                            if (array_key_exists($sColumn, $aSupportedColumns)) {
                                if (empty($sColumn) || empty($sValue)) {
                                    $aLogs[$iRow]['errors'][] = sysTranslations::get('redirectEmptyValue');
                                    continue;
                                }

                                // check if the urls are not too long
                                if (strlen($sValue) > 255){
                                    $aLogs[$iRow]['errors'][] = sysTranslations::get('redirectTooLong') . ' `' . $sColumn  . '`';
                                    continue;
                                }
                                
                                if (!preg_match('#^/#', $sValue)) {
                                    $sValue = '/' . $sValue;
                                }
                                $sProp             = $aSupportedColumns[$sColumn];
                                $oRedirect->$sProp = $sValue;
                            } else {
                                $aLogs[$iRow]['warnings'][] = sysTranslations::get('redirectColomnNotSupported') . ' `' . $sColumn . '`';
                                $iWarnings++;
                            }
                        }

                        if ($oRedirect->isValid()) {
                            if (!empty($oRedirect->redirectId)) {
                                $aLogs[$iRow]['saved'][] = sysTranslations::get('redirectFound');
                            } else {
                                $aLogs[$iRow]['saved'][] = sysTranslations::get('redirectNew');
                            }
                            if (!$bTestMode) {
                                RedirectManager::saveRedirect($oRedirect);
                            }
                            $aLogs[$iRow]['saved'][] = sysTranslations::get('redirectsSaved') . ' `' . $oRedirect->pattern . ' -> ' . $oRedirect->newUrl . '`';
                            $iSaved++;
                        } else {
                            foreach ($oRedirect->getInvalidProps() AS $sInvalidProp) {
                                $sColumn                  = $aSupportedProps[$sInvalidProp];
                                $aLogs[$iRow]['errors'][] = sysTranslations::get('global_value') . ' `' . $oRedirect->$sInvalidProp . '`' . sysTranslations::get('redirectNotCorrectForColumn') . '`' . $sColumn . '`';
                                $iErrors++;
                            }
                            $aLogs[$iRow]['errors'][] = sysTranslations::get('redirectCannotSave') . ' `' . $oRedirect->pattern . ' -> ' . $oRedirect->newUrl . '`';
                        }
                    }
                    $iTotal++;
                }
            }
        }
    } else {
        $aErrors[] = sysTranslations::get('redirectFileCouldNotBeUploaded');
    }
}

$oPageLayout->sViewPath = getAdminView('/import/import_form', 'redirect');

# include default template
include_once getAdminView('layout');
?>
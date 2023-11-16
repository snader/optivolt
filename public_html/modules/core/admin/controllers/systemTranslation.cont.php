<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('sysTrans_manager');
$oPageLayout->sModuleName  = sysTranslations::get('sysTrans_manager');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// handle perPage
if (http_post('setPerPage')) {
    $_SESSION['systemTranslationsPerPage'] = http_post('perPage');
}

// handle filter
$aSystemTranslationFilter = http_session('systemTranslationFilter');
if (http_post('filterSystemTranslations')) {
    $aSystemTranslationFilter            = http_post('systemTranslationFilter');
    $_SESSION['systemTranslationFilter'] = $aSystemTranslationFilter;
    http_redirect(getCurrentUrl());
}

if (http_post('resetFilter') || empty($aSystemTranslationFilter)) {
    unset($_SESSION['systemTranslationFilter']);
    $aSystemTranslationFilter                     = [];
    $aSystemTranslationFilter['systemLanguageId'] = '';
    $aSystemTranslationFilter['text']             = '';
    $aSystemTranslationFilter['label']            = '';
    if (http_post('resetFilter')) {
        http_redirect(getCurrentUrlPath());
    }
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oSystemTranslation = SystemTranslationManager::getTranslationById(http_get("param2"));
        if (!$oSystemTranslation) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oSystemTranslation = new SystemTranslation ();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oSystemTranslation->_load($_POST);
        $oSystemTranslation->text = http_post('text');

        $oSystemTranslation->label = trim($oSystemTranslation->label, '[]'); // trim `[` and `]` at beginning and end
        # if object is valid, save
        if ($oSystemTranslation->isValid()) {
            $bNew = empty($oSystemTranslation->systemTranslationId);
            SystemTranslationManager::saveTranslation($oSystemTranslation); //save systemTranslation
            $_SESSION['statusUpdate'] = sysTranslations::get('sysTrans_saved'); //save status update into session
            sysTranslations::reset(); // reset system translations
            if ($bNew) {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/toevoegen');
            } else {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
            }
        } else {
            Debug::logError("", "SystemTranslation module php validate error", __FILE__, __LINE__, "Tried to save systemTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('sysTrans_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('systemTranslations/systemTranslation_form');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oSystemTranslation = SystemTranslationManager::getTranslationById(http_get("param2"));
    }

    if ($oSystemTranslation && CSRFSynchronizerToken::validate() && SystemTranslationManager::deleteTranslation($oSystemTranslation)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('sysTrans_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('sysTrans_not_deleted'); //save status update into session
    }
    sysTranslations::reset(); // reset system translations
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-checkLabel') {

    # check if systemTranslationname exists
    $oSystemTranslation = SystemTranslationManager::getTranslationByLabel(http_get('systemLanguageId', SystemLanguage::default_languageId), trim(http_get('label'), '[]'));
    if ($oSystemTranslation && $oSystemTranslation->systemTranslationId != http_get('systemTranslationId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} elseif (http_get('param1') == 'edit-full-system-translation') {
    $oSystemLanguage = SystemLanguageManager::getLanguageById(http_get('param2'));
    if (!$oSystemLanguage) {
        die;
    }

    if (http_post('action') == 'save' && CSRFSynchronizerToken::validate()) {
        $aSystemTranslations = http_post('systemTranslations');
        foreach ($aSystemTranslations['labels'] AS $iKey => $sLabel) {
            $sText = $aSystemTranslations['texts'][$iKey];
            if (!empty($sText)) {
                $oSystemTranslation = SystemTranslationManager::getTranslationByLabel($oSystemLanguage->systemLanguageId, $sLabel);
                if (!$oSystemTranslation) {
                    $oSystemTranslation = new SystemTranslation();
                }
                $oSystemTranslation->systemLanguageId = $oSystemLanguage->systemLanguageId;
                $oSystemTranslation->label            = $sLabel;
                $oSystemTranslation->text             = $sText;
                if ($oSystemTranslation->isValid()) {
                    SystemTranslationManager::saveTranslation($oSystemTranslation);
                }
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('sysTrans_saved'); //save status update into session
        http_redirect(getCurrentUrlPath(true));
    } else {

        $oCompareSystemLanguage = SystemLanguageManager::getLanguageById(http_get('compareSystemLanguageId', SystemLanguage::default_languageId));
        if ($oCompareSystemLanguage) {
            $aCompareSystemTranslations = SystemTranslationManager::getTranslationsByFilter(['systemLanguageId' => $oCompareSystemLanguage->systemLanguageId]);
            $aCompareTextsByLabel       = [];
            foreach ($aCompareSystemTranslations AS $oCompareTranslation) {
                $aCompareTextsByLabel[$oCompareTranslation->label] = $oCompareTranslation->text;
            }
        }

        // get all unique labels and, texts where filled
        $aSystemTranslations = SystemTranslationManager::getFullTranslationForLanguage($oSystemLanguage->systemLanguageId);

        $oPageLayout->sViewPath = getAdminView('systemTranslations/systemTranslation_full_form');
    }
} elseif (http_get('param1') == 'missing-translations') {

    if (http_post('action') == 'save' && CSRFSynchronizerToken::validate()) {

        $oSystemLanguage = SystemLanguageManager::getLanguageById(http_post('systemLanguageId'));
        if (!$oSystemLanguage) {
            Session::set('statusUpdate', sysTranslations::get('sysTrans_error')); //save status update into session
            Router::redirect(getCurrentUrlPath(true));
        }

        $aSystemTranslations = Request::postVar('systemTranslations');

        foreach ($aSystemTranslations['labels'] AS $iKey => $sLabel) {
            $sText = $aSystemTranslations['texts'][$iKey];
            if (!empty($sText)) {
                $oSystemTranslation = SystemTranslationManager::getTranslationByLabel($oSystemLanguage->systemLanguageId, $sLabel);
                if (!$oSystemTranslation) {
                    $oSystemTranslation = new SystemTranslation();
                }
                $oSystemTranslation->systemLanguageId = $oSystemLanguage->systemLanguageId;
                $oSystemTranslation->label            = $sLabel;
                $oSystemTranslation->text             = $sText;
                if ($oSystemTranslation->isValid()) {
                    SystemTranslationManager::saveTranslation($oSystemTranslation);
                }
            }
        }

        // reset translations
        sysTranslations::reset();

        Session::set('statusUpdate', sysTranslations::get('sysTrans_saved')); //save status update into session
        http_redirect(getCurrentUrlPath(true));
    }

    function scanFolder($sDir, &$aMissingLabels)
    {
        $aAllTranslations = sysTranslations::getTranslations('all');

        $rDir = opendir($sDir);
        while (false !== ($sFile = readdir($rDir))) {
            // not a file name, continue
            if ($sFile == '.' || $sFile == '..') {
                continue;
            }

            $sFileLocation = $sDir . '/' . $sFile;

            // handle PHP files
            if (preg_match('#\.php$#', $sFile)) {
                $sContents = file_get_contents($sFileLocation);
                $aMatches  = [];
                preg_match_all('#sysTranslations::get\([\'"]([^\'"]+)[\'"]\)#siU', $sContents, $aMatches, PREG_PATTERN_ORDER);
                foreach ($aMatches[1] AS $sMatch) {
                    if (!array_key_exists($sMatch, $aAllTranslations)) {
                        $aMissingLabels[] = $sMatch;
                    }
                }
            }

            if (is_dir($sFileLocation)) {
                scanFolder($sFileLocation, $aMissingLabels);
            }
        }
    }

    $aMissingLabels = [];
    scanFolder(SYSTEM_MODULES_FOLDER, $aMissingLabels);

    // make unique
    $aMissingLabels = array_unique($aMissingLabels);

    $oPageLayout->sViewPath = getAdminView('systemTranslations/systemTranslation_missing_form');
} elseif (http_get('param1') == 'export') {
    $aSystemTranslations = SystemTranslationManager::getTranslationsByFilter($aSystemTranslationFilter);

    $aSystemLanguages = SystemLanguageManager::getLanguagesByFilter();
    $aLanguagesByAbbr = [];
    foreach ($aSystemLanguages AS $oSystemLanguage) {
        $aLanguagesByAbbr[$oSystemLanguage->systemLanguageId] = $oSystemLanguage->abbr;
    }

    $aSystemTranslationsByLanguageAbbr = [];
    foreach ($aSystemTranslations AS $oSystemTranslation) {
        $aSystemTranslationsByLanguageAbbr[$aLanguagesByAbbr[$oSystemTranslation->systemLanguageId]][] = $oSystemTranslation;
    }

    $oPageLayout->sViewPath = getAdminView('systemTranslations/systemTranslations_export');
} else {
    $iNrOfRecords = DBConnection::count('system_translations');
    $iPerPage     = http_session('systemTranslationsPerPage', 10);
    $iCurrPage    = http_get('page', 1);
    if (http_post('setPerPage')) {
        // reset iCurrPage on setPerPage change
        $iCurrPage = 1;
    }
    if ($iCurrPage > ($iNrOfRecords / $iPerPage) + 1) {
        // prevent non existing iCurrpage
        $iCurrPage = (round($iNrOfRecords / $iPerPage) + 1);
    }
    $iStart = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    $aSystemTranslations    = SystemTranslationManager::getTranslationsByFilter($aSystemTranslationFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('systemTranslations/systemTranslations_overview');
}

# include default template
include_once getAdminView('layout');
?>

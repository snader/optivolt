<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('siteTrans_manager');
$oPageLayout->sModuleName  = sysTranslations::get('siteTrans_manager');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// handle perPage
if (http_post('setPerPage')) {
    $_SESSION['siteTranslationsPerPage'] = http_post('perPage');
}

// handle filter
$aSiteTranslationFilter = http_session('siteTranslationFilter');
if (http_post('filterSiteTranslations')) {
    $aSiteTranslationFilter = http_post('siteTranslationFilter');

    if ($oCurrentUser->isAdmin()) {
        $aSiteTranslationFilter['showEditable'] = 1;
    } else {
        $aSiteTranslationFilter['showEditable'] = 0;
    }

    $_SESSION['siteTranslationFilter'] = $aSiteTranslationFilter;
    http_redirect(getCurrentUrl());
}

if (http_post('resetFilter') || empty($aSiteTranslationFilter)) {
    unset($_SESSION['siteTranslationFilter']);
    $aSiteTranslationFilter               = [];
    $aSiteTranslationFilter['languageId'] = '';
    $aSiteTranslationFilter['text']       = '';
    $aSiteTranslationFilter['label']      = '';

    if ($oCurrentUser->isAdmin()) {
        $aSiteTranslationFilter['showEditable'] = 1;
    } else {
        $aSiteTranslationFilter['showEditable'] = 0;
    }

    if (http_post('resetFilter')) {
        http_redirect(getCurrentUrlPath());
    }
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oSiteTranslation = SiteTranslationManager::getTranslationById(http_get("param2"));
        if (!$oSiteTranslation || !$oSiteTranslation->isEditable()) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oSiteTranslation = new SiteTranslation();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oSiteTranslation->_load($_POST);
        $oSiteTranslation->text = http_post('text');

        $oSiteTranslation->label = trim($oSiteTranslation->label, '[]'); // trim `[` and `]` at beginning and end
        # if object is valid, save
        if ($oSiteTranslation->isValid()) {
            $bNew = empty($oSiteTranslation->siteTranslationId);
            SiteTranslationManager::saveTranslation($oSiteTranslation); //save siteTranslation
            $_SESSION['statusUpdate'] = sysTranslations::get('siteTrans_saved'); //save status update into session
            SiteTranslations::reset(); // reset site translations
            if ($bNew) {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/toevoegen');
            } else {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
            }
        } else {
            Debug::logError("", "SiteTranslation module php validate error", __FILE__, __LINE__, "Tried to save siteTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('siteTrans_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('siteTranslations/siteTranslation_form');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oSiteTranslation = SiteTranslationManager::getTranslationById(http_get("param2"));
    }

    if ($oSiteTranslation && CSRFSynchronizerToken::validate() && SiteTranslationManager::deleteTranslation($oSiteTranslation)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('siteTrans_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('siteTrans_not_deleted'); //save status update into session
    }
    SiteTranslations::reset(); // reset site translations
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-checkLabel') {

    # check if siteTranslationname exists
    $oSiteTranslation = SiteTranslationManager::getTranslationByLabel(http_get('languageId'), trim(http_get('label'), '[]'));
    if ($oSiteTranslation && $oSiteTranslation->siteTranslationId != http_get('siteTranslationId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} elseif (http_get('param1') == 'edit-full-site-translation') {
    $oLanguage = LanguageManager::getLanguageById(http_get('param2'));
    if (!$oLanguage) {
        die;
    }

    if (http_post('action') == 'save' && CSRFSynchronizerToken::validate()) {
        $aSiteTranslations = http_post('siteTranslations');
        foreach ($aSiteTranslations['labels'] AS $iKey => $sLabel) {
            $sText = $aSiteTranslations['texts'][$iKey];
            if (!empty($sText)) {
                $oSiteTranslation = SiteTranslationManager::getTranslationByLabel($oLanguage->languageId, $sLabel);
                if (!$oSiteTranslation) {
                    $oSiteTranslation = new SiteTranslation();
                }
                $oSiteTranslation->languageId = $oLanguage->languageId;
                $oSiteTranslation->label      = $sLabel;
                $oSiteTranslation->text       = $sText;

                if ($oSiteTranslation->isValid()) {
                    SiteTranslationManager::saveTranslation($oSiteTranslation);
                }
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('siteTrans_saved'); //save status update into session
        http_redirect(getCurrentUrlPath(true));
    } else {

        $oCompareLanguage = LanguageManager::getLanguageById(http_get('compareLanguageId'));
        if ($oCompareLanguage) {
            $aCompareSiteTranslations = SiteTranslationManager::getTranslationsByFilter(['languageId' => $oCompareLanguage->languageId]);
            $aCompareTextsByLabel     = [];
            foreach ($aCompareSiteTranslations AS $oCompareTranslation) {
                $aCompareTextsByLabel[$oCompareTranslation->label] = $oCompareTranslation->text;
            }
        }

        // get all unique labels and, texts where filled
        $aSiteTranslations = SiteTranslationManager::getFullTranslationForLanguage($oLanguage->languageId);

        $oPageLayout->sViewPath = getAdminView('siteTranslations/siteTranslation_full_form');
    }
} elseif (http_get('param1') == 'missing-translations') {

    if (http_post('action') == 'save' && CSRFSynchronizerToken::validate()) {

        $oLanguage = LanguageManager::getLanguageById(Request::postVar('languageId'));

        if (!$oLanguage) {
            Session::set('statusUpdate', sysTranslations::get('siteTrans_error')); //save status update into session
            Router::redirect(getCurrentUrlPath(true));
        }
        $aSiteTranslations = Request::postVar('siteTranslations');

        foreach ($aSiteTranslations['labels'] AS $iKey => $sLabel) {
            $sText = $aSiteTranslations['texts'][$iKey];
            if (!empty($sText)) {
                $oSiteTranslation = SiteTranslationManager::getTranslationByLabel($oLanguage->languageId, $sLabel);
                if (!$oSiteTranslation) {
                    $oSiteTranslation = new SiteTranslation();
                }
                $oSiteTranslation->languageId = $oLanguage->languageId;
                $oSiteTranslation->label      = $sLabel;
                $oSiteTranslation->text       = $sText;

                if ($oSiteTranslation->isValid()) {
                    SiteTranslationManager::saveTranslation($oSiteTranslation);
                }
            }
        }

        // reset translations
        SiteTranslations::reset();

        Session::set('statusUpdate', sysTranslations::get('siteTrans_saved')); //save status update into session
        http_redirect(getCurrentUrlPath(true));
    }

    function scanFolder($sDir, &$aMissingLabels)
    {
        $aAllTranslations = SiteTranslations::getTranslations('all');
        //_d($aAllTranslations); die;

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
                preg_match_all('#SiteTranslations::get\([\'"]([^\'"]+)[\'"]\)#siU', $sContents, $aMatches, PREG_PATTERN_ORDER);
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
    scanFolder(SYSTEM_THEMES_FOLDER, $aMissingLabels);

    // make unique
    $aMissingLabels = array_unique($aMissingLabels);

    $oPageLayout->sViewPath = getAdminView('siteTranslations/siteTranslation_missing_form');
} elseif (http_get('param1') == 'export') {
    $aSiteTranslationFilter['showEditable'] = 1;
    $aSiteTranslations                      = SiteTranslationManager::getTranslationsByFilter($aSiteTranslationFilter);

    $aLanguages       = LanguageManager::getLanguagesByFilter(['hasLocale' => true]);
    $aLanguagesByAbbr = [];
    foreach ($aLanguages AS $oLanguage) {
        $aLanguagesByAbbr[$oLanguage->languageId] = $oLanguage->code;
    }

    /*
      $aSiteTranslationsByLanguageAbbr = array();
      foreach ($aSiteTranslations AS $oSiteTranslation) {
      $aSiteTranslationsByLanguageAbbr[$aLanguagesByAbbr[$oSiteTranslation->languageId]][] = $oSiteTranslation;
      }
     *
     */

    $aSiteTranslationsByLanguageId = [];
    foreach ($aSiteTranslations AS $oSiteTranslation) {
        $aSiteTranslationsByLanguageId[$oSiteTranslation->languageId][] = $oSiteTranslation;
    }

    $oPageLayout->sViewPath = getAdminView('siteTranslations/siteTranslations_export');
} else {

    $iNrOfRecords = DBConnection::count('site_translations');
    $iPerPage     = http_session('siteTranslationsPerPage', 10);
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

    $aSiteTranslations      = SiteTranslationManager::getTranslationsByFilter($aSiteTranslationFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('siteTranslations/siteTranslations_overview');
}

# include default template
include_once getAdminView('layout');
?>

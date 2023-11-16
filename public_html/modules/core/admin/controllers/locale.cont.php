<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('locales_locales');
$oPageLayout->sModuleName  = sysTranslations::get('locales_locales');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oLocale = LocaleManager::getLocaleById(http_get("param2"));
        if (empty($oLocale)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oLocale = new ACMS\Locale();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oLocale->_load($_POST);

        # if object is valid, save
        if ($oLocale->isValid()) {
            LocaleManager::saveLocale($oLocale); //save object

            $_SESSION['statusUpdate'] = sysTranslations::get('locales_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLocale->localeId);
        } else {
            Debug::logError("", "Locale module php validate error", __FILE__, __LINE__, "Tried to save Locale with wrong values despite javascript check.<br />" . _d($oLocale, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('locales_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('locales/locale_form');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    $bOnline   = http_get("online", 0); //no value, set offline by default
    $bAjax     = http_get("ajax", false); //controller requested by ajax
    $iLocaleId = http_get("param2");
    $oResObj   = new stdClass(); //standard class for json feedback
# update online for object
    if (is_numeric($iLocaleId) && CSRFSynchronizerToken::validate()) {
        $oResObj->success  = LocaleManager::updateOnlineByLocaleId($bOnline, $iLocaleId);
        $oResObj->localeId = $iLocaleId;
        $oResObj->online   = $bOnline;
    }

# redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if (is_numeric(http_get("param2"))) {
        $oLocale = LocaleManager::getLocaleById(http_get("param2"));
    }
    if (!empty($oLocale) && CSRFSynchronizerToken::validate() && LocaleManager::deleteLocale($oLocale)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('locales_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('locales_not_deleted'); //save status update into session
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'volgorde-wijzigen') {

    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aLocaleIds = explode('|', http_post('order'));
            $iC         = 1;
            foreach ($aLocaleIds as $iLocaleId) {
                $oLocale        = LocaleManager::getLocaleById($iLocaleId);
                $oLocale->order = $iC;
                if ($oLocale->isValid()) {
                    LocaleManager::saveLocale($oLocale);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

// get all items for order changing
    $aLocales               = LocaleManager::getLocalesByFilter(['showAll' => true]);
    $oPageLayout->sViewPath = getAdminView('locales/locales_change_order');
} elseif (http_get('param1') == 'ajax-checkURLFormat') {

// simulate locale to get urlFormat
    $oTmpLocale             = new ACMS\Locale();
    $oTmpLocale->languageId = http_post('languageId');
    $oTmpLocale->countryId  = http_post('countryId');
    $oTmpLocale->domain     = http_post('domain');
    $oTmpLocale->subdomain  = http_post('subdomain');
    $oTmpLocale->prefix1    = http_post('prefix1');
    $oTmpLocale->prefix2    = http_post('prefix2');

# check if username exists
    if (LocaleManager::urlFormatExists($oTmpLocale->getURLFormat(true), http_post('localeId'))) {
        echo json_encode([sysTranslations::get('locales_url_format_already_exists')]);
    } else {
        echo json_encode(true);
    }
    die;
} elseif (http_get('param1') == 'ajax-checkLanguageCountryCombination') {

# check if username exists
    if (LocaleManager::languageAndCountryExists(http_post('languageId'), http_post('countryId'), http_post('localeId'))) {
        echo json_encode([sysTranslations::get('locales_language_country_combi_already_exists')]);
    } else {
        echo json_encode(true);
    }
    die;
} # display overview
else {
    $aLocales               = LocaleManager::getLocalesByFilter(['showAll' => true]);
    $oPageLayout->sViewPath = getAdminView('locales/locales_overview');
}

# include template
include_once getAdminView('layout');
?>

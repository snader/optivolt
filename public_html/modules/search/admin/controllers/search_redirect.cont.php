<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// reset crop settings
unset($_SESSION['aCropSettings']);

global $oPageLayout;

// set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Zoek Redirect";
$oPageLayout->sModuleName  = "Zoek Redirect";

// get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

// handle perPage
if (http_post('setPerPage')) {
    $_SESSION['searchRedirectPerPage'] = http_post('perPage');
}

// handle filter
$aSearchRedirectFilter = http_session('searchRedirectFilter');
if (http_post('filterSearchRedirects')) {
    $aSearchRedirectFilter            = http_post('searchRedirectFilter');
    $_SESSION['searchRedirectFilter'] = $aSearchRedirectFilter;
}

if (http_post('resetFilter') || empty($aSearchRedirectFilter)) {
    unset($_SESSION['searchRedirectFilter']);
    $aSearchRedirectFilter             = [];
    $aSearchRedirectFilter['q']        = '';
    $aSearchRedirectFilter['withlink'] = '';

}

// handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    // set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oSearchRedirect = SearchRedirectManager::getSearchRedirectById(http_get("param2"));
        if (empty($oSearchRedirect)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oSearchRedirect = new SearchRedirect();
    }

    // action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        // load data in object
        $oSearchRedirect->_load($_POST);

        $oSearchRedirect->pageId           = http_post('pageId');
        $oSearchRedirect->newsItemId       = http_post('newsItemId');
        $oSearchRedirect->catalogProductId = http_post('catalogProductId');
        $oSearchRedirect->photoAlbumId     = http_post('photoAlbumId');

        // if object is valid, save
        if ($oSearchRedirect->isValid()) {
            SearchRedirectManager::saveSearchRedirect($oSearchRedirect); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('searchredirect_item_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oSearchRedirect->searchId);
        } else {
            Debug::logError("", "SearchRedirect module php validate error", __FILE__, __LINE__, "Tried to save SearchRedirect with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('searchredirect_item_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('search_redirect_form', 'search');
} // delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oSearchRedirect = SearchRedirectManager::getSearchRedirectById(http_get("param2"));
        }

        if (!empty($oSearchRedirect) && SearchRedirectManager::deleteSearchRedirect($oSearchRedirect)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('searchredirect_item_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('searchredirect_item_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} // display overview
else {
    $iNrOfRecords = DBConnection::count('search_redirects');
    $iPerPage     = http_session('searchRedirectPerPage', 10);
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

    $aAllSearchRedirects = SearchRedirectManager::getSearchRedirectsByFilter($aSearchRedirectFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount          = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('search_redirect_overview', 'search');
}

// include template
include_once getAdminView('layout');
?>
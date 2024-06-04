<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

# reset crop settings
Session::clear('aCropSettings');

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('inventarisation');
$oPageLayout->sModuleName  = sysTranslations::get('inventarisation');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

// handle perPage
if (Request::postVar('setPerPage')) {
    Session::set('inventarisationsPerPage', Request::postVar('perPage'));
}

// handle filter
$aInventarisationFilter = Session::get('inventarisationsFilter');
if (Request::postVar('filterInventarisations')) {
    $aInventarisationFilter            = Request::postVar('inventarisationsFilter');
    $aInventarisationFilter['showAll'] = true; // manually set showAll to true
    Session::set('inventarisationsFilter', $aInventarisationFilter);
}

if (Request::postVar('resetFilter') || empty($aInventarisationFilter)) {
    Session::clear('inventarisationsFilter');
    $aInventarisationFilter                       = [];
    $aInventarisationFilter['q']                  = '';
    $aInventarisationFilter['showAll']            = true; // manually set showAll to true
}

# handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oInventarisation = InventarisationManager::getInventarisationById(Request::param('OtherID'));
        if (!$oInventarisation) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oInventarisation->isEditable()) {
            Session::set('statusUpdate', sysTranslations::get('inventarisation_not_edited')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
        }
    } else {
        $oInventarisation             = new Inventarisation();
        $oInventarisation->userId = UserManager::getCurrentUser()->userId;
    }

    # action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oInventarisation->_load($_POST);

        # set some properties after load, _load strips tags for all inputs
        $oInventarisation->name      = Request::postVar('name');
        $oInventarisation->remarks    = Request::postVar('remarks');

        

        # if object is valid, save
        if ($oInventarisation->isValid()) {
            InventarisationManager::saveInventarisation($oInventarisation); //save item
            Session::set('statusUpdate', sysTranslations::get('inventarisation_saved')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oInventarisation->inventarisationId);
        } else {
            Debug::logError("", "Inventarisation module php validate error", __FILE__, __LINE__, "Tried to save Inventarisation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('inventarisation_not_saved');
        }
    }

    

    $oPageLayout->sViewPath = getAdminView('inventarisations/inventarisation_form', 'inventarisations');

} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oInventarisation = InventarisationManager::getInventarisationById(Request::param('OtherID'));
        }
        if ($oInventarisation && InventarisationManager::deleteInventarisation($oInventarisation)) {
            Session::set('statusUpdate', sysTranslations::get('inventarisation_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('inventarisation_not_deleted')); //save status update into session
        }
    }
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());

} else {

    $iNrOfRecords = DBConnection::count('inventarisations');
    $iPerPage     = Session::get('inventarisationsPerPage', 10);
    $iCurrPage    = Request::getVar('page') ? Request::getVar('page') : 1;

    if (Request::postVar('setPerPage')) {
        // reset iCurrPage on setPerPage change
        $iCurrPage = 1;
    }
    if ($iCurrPage > ($iNrOfRecords / $iPerPage) + 1) {
        // prevent non existing iCurrpage
        $iCurrPage = (round($iNrOfRecords / $iPerPage) + 1);
    }

    $iStart = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }

    # add language to filter
    $aInventarisationFilter = [];

    #display overview
    $aInventarisations             = InventarisationManager::getInventarisationsByFilter($aInventarisationFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('inventarisations/inventarisations_overview', 'inventarisations');
}

# include template
include_once getAdminView('layout');
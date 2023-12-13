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
$oPageLayout->sWindowTitle = sysTranslations::get('log');
$oPageLayout->sModuleName  = sysTranslations::get('log');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
Session::clear('statusUpdate'); //remove statusupdate, always show once

// handle perPage
if (Request::postVar('setPerPage')) {
    Session::set('logsPerPage', Request::postVar('perPage'));
}

// handle filter
$aLogFilter = Session::get('logsFilter');
if (Request::postVar('filterLogs')) {
    $aLogFilter            = Request::postVar('logsFilter');
    $aLogFilter['showAll'] = true; // manually set showAll to true
    Session::set('logsFilter', $aLogFilter);
}

if (Request::postVar('resetFilter') || empty($aLogFilter)) {
    Session::clear('logsFilter');
    $aLogFilter                       = [];
    $aLogFilter['q']                  = '';
    $aLogFilter['showAll']            = true; // manually set showAll to true
}

# handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {

    # set crop referrer for pages module
    Session::set('cropReferrer', ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID'));

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oLog = LogManager::getLogById(Request::param('OtherID'));
        if (!$oLog) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oLog->isEditable()) {
            Session::set('statusUpdate', sysTranslations::get('log_not_edited')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
        }
    } else {
        $oLog             = new Log();
        $oLog->languageId = AdminLocales::language();
    }

    # action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oLog->_load($_POST);

        # set some properties after load, _load strips tags for all inputs
        $oLog->intro      = Request::postVar('intro');
        $oLog->content    = Request::postVar('content');

        

        # if object is valid, save
        if ($oLog->isValid()) {
            LogManager::saveLog($oLog); //save item
            Session::set('statusUpdate', sysTranslations::get('log_saved')); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oLog->logId);
        } else {
            Debug::logError("", "Log module php validate error", __FILE__, __LINE__, "Tried to save Log with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('log_not_saved');
        }
    }

    

    $oPageLayout->sViewPath = getAdminView('logs/log_form', 'logs');

} elseif (Request::param('ID') == 'volgorde-wijzigen') {

    if (Request::postVar('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (Request::postVar('order')) {
            $aLogIds = explode('|', Request::postVar('order'));
            $iC               = 1;
            foreach ($aLogIds AS $iLogId) {
                $oLog        = LogManager::getLogById($iLogId);
                $oLog->order = $iC;
                if ($oLog->isValid()) {
                    LogManager::saveLog($oLog);
                }
                $iC++;
            }
        }
        Session::set('statusUpdate', sysTranslations::get('global_sequence_saved')); //save status update into session

        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }

    // get all items for order changing
    $aLogs      = LogManager::getLogsByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('logs/logs_change_order', 'logs');

} elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oLog = LogManager::getLogById(Request::param('OtherID'));
        }
        if ($oLog && LogManager::deleteLog($oLog)) {
            Session::set('statusUpdate', sysTranslations::get('log_deleted')); //save status update into session
        } else {
            Session::set('statusUpdate', sysTranslations::get('log_not_deleted')); //save status update into session
        }
    }
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());

} elseif (Request::param('ID') == 'ajax-setOnline') {
    if (!CSRFSynchronizerToken::validate()) {
        die(json_encode(['success'=>false]));
    }
    $bOnline     = Request::getVar("online") ? Request::getVar("online") : 0; //no value, set offline by default
    $bAjax       = Request::getVar("ajax") ? Request::getVar("ajax") : false; //controller requested by ajax
    $iLogId = Request::param('OtherID');
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for page
    if (is_numeric($iLogId)) {
        $oResObj->success    = LogManager::updateOnlineByLogId($bOnline, $iLogId);
        $oResObj->logId = $iLogId;
        $oResObj->online     = $bOnline;
    }

    if (!$bAjax) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }
    print json_encode($oResObj);
    die;
} else {

    $iNrOfRecords = DBConnection::count('logs');
    $iPerPage     = Session::get('logsPerPage', 10);
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
    $aLogFilter['languageId'] = AdminLocales::language();

    #display overview
    $aLogs             = LogManager::getLogsByFilter($aLogFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('logs/logs_overview', 'logs');
}

# include template
include_once getAdminView('layout');
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


# include template
include_once getAdminView('layout');
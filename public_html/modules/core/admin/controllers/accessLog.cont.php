<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('accessLogs_management');
$oPageLayout->sModuleName  = sysTranslations::get('accessLogs_management');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle accessLogFilter
$aAccessLogFilter = http_session('accessLogFilter');
if (http_post('filterForm')) {
    $aAccessLogFilter            = http_post('accessLogFilter');
    $_SESSION['accessLogFilter'] = $aAccessLogFilter;
}

if (http_post('resetFilter') || empty($aAccessLogFilter)) {
    unset($_SESSION['accessLogFilter']);
    $aAccessLogFilter       = [];
    $aAccessLogFilter['ip'] = '';
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['accessLogsPerPage'] = http_post('perPage');
}

if (isDeveloper() && http_get('param1') == 'ajax-getServerInfo') {
    $oAccessLog = AccessLogManager::getAccessLogById(http_get('param2'));
    if (!$oAccessLog) {
        die('AccessLog not found');
    }

    #include page and `die` for ajax without template
    include_once getAdminView('accessLogs/accessLog_details');
    die;
} # display overview
else {

    if (http_post('action') == 'blockAccess') {
        $oAccessLog = AccessLogManager::getAccessLogById(http_post('accessLogId'));
        if ($oAccessLog) {
            $oAccessLog->blocked   = strftime(Date::FORMAT_DB_F);
            $oAccessLog->reason    = http_post('reason');
            $oAccessLog->extraInfo = http_post('extraInfo');
            AccessLogManager::saveAccessLog($oAccessLog);
            $_SESSION['statusUpdate'] = sysTranslations::get('accessLogs_saved');
            http_redirect(getCurrentUrlPath());
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('accessLogs_not_saved');
            http_redirect(getCurrentUrl());
        }
    } elseif (http_post('action') == 'unlockAccess') {
        $oAccessLog = AccessLogManager::getAccessLogById(http_post('accessLogId'));
        if ($oAccessLog) {
            $oAccessLog->reason        = http_post('reason');
            $oAccessLog->extraInfo     = http_post('extraInfo');
            $oAccessLog->blocked       = null;
            $oAccessLog->loginFails    = 0;
            $oAccessLog->lastLoginFail = null;
            AccessLogManager::saveAccessLog($oAccessLog);
            $_SESSION['statusUpdate'] = sysTranslations::get('accessLogs_saved');
            http_redirect(getCurrentUrlPath());
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('accessLogs_not_saved');
            http_redirect(getCurrentUrl());
        }
    }

    $iNrOfRecords = DBConnection::count('access_logs');
    $iPerPage     = http_session('accessLogsPerPage', 10);
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

    $aAccessLogs = AccessLogManager::getAccessLogsByFilter($aAccessLogFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount  = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('accessLogs/accessLogs_overview');
}

# include template
include_once getAdminView('layout');
?>
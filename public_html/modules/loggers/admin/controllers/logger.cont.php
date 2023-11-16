<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
  die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('logger');
$oPageLayout->sModuleName  = sysTranslations::get('logger');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

$aFilter = array();


// end ajax handling
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

  # de logger zelf handle add/edit

  if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
    $oLogger = LoggerManager::getLoggerById(http_get("param2"));
    if (empty($oLogger)) {
      http_redirect(ADMIN_FOLDER . "/");
    }
  } else {
    $oLogger = new Logger();
  }

  # action = save
  if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

    # load data in object
    $oLogger->_load($_POST);

    if (empty($_POST['online'])) {
      $oLogger->online = 0;
    }
    if (!empty(http_post("availableFrom"))) {
      $oLogger->availableFrom = date('Y-m-d ', strtotime(http_post("availableFrom")));
    }

    # if object is valid, save
    if ($oLogger->isValid()) {
      LoggerManager::saveLogger($oLogger); //save object
      $_SESSION['statusUpdate'] = sysTranslations::get('logger_saved'); //save status update into session
      http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLogger->loggerId);
    } else {
      Debug::logError("", "Logger module php validate error", __FILE__, __LINE__, "Tried to save Logger with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
      $_SESSION['statusUpdate'] = sysTranslations::get('logger_not_saved');
    }
  }

  //$aLinkedCustomers = PlanningManager::getPlanningItemsByLoggerId(['loggerId' => $oLogger->loggerId]);
  $oPageLayout->sViewPath = getAdminView('loggers/logger_form', 'loggers');
}


# delete logger object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {

    if (is_numeric(http_get("param2"))) {
      $oLogger = LoggerManager::getLoggerById(http_get("param2"));
    }

    # trigger error
    if (empty($oLogger)) {
      $_SESSION['statusUpdate'] = sysTranslations::get('logger_not_deleted'); //save status update into session
      http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    if (!empty($oLogger) && LoggerManager::deleteLogger($oLogger)) {
      $_SESSION['statusUpdate'] = sysTranslations::get('logger_deleted'); //save status update into session
    } else {
      $_SESSION['statusUpdate'] = sysTranslations::get('logger_not_deleted'); //save status update into session
  }

  http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));


} elseif (http_get('param1') == 'ajax-checkName') {
  # check if name exists
  $oLogger = LoggerManager::getLoggerByName(http_get('name'));
  if ($oLogger && $oLogger->loggerId != http_get('loggerId')) {
    echo 'false';
  } else {
    echo 'true';
  }
  die;
} elseif (http_get("param1") == 'ajax-setOnline') {
  $bOnline       = http_get("online");
  $bAjax         = http_get("ajax", false); //controller requested by ajax
  $iLoggerId  = http_get("param2");
  $oLoggers   = LoggerManager::getLoggerById($iLoggerId);
  $sLockedReason = '';
  $oResObj       = new stdClass(); //standard class for json feedback
  // update online for user
  if ($oLoggers && $oLoggers->isOnlineChangeable()) {
    $oResObj->success      = LoggerManager::updateOnlineByLoggerId($bOnline, $iLoggerId);
    $oResObj->logger  = $iLoggerId;
    $oResObj->online       = $bOnline;
    $oResObj->reason       = $sLockedReason;
  }

  if (!$bAjax) {
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
  }
  print json_encode($oResObj);
  die;
} # display overview loggers
else {

  // handle filter
  $aLoggersFilter = Session::get('loggersFilter');
  if (Request::postVar('filterLoggers')) {
    $aLoggersFilter            = Request::postVar('loggersFilter');
    $aLoggersFilter['showAll'] = true; // manually set showAll to true

    Session::set('loggersFilter', $aLoggersFilter);
  }

  if (Request::postVar('resetFilter')) {


    $aLoggersFilter['customerId']                  = '';
    Session::set('loggersFilter', $aLoggersFilter);
  }



  $aLoggersFilter['online'] = true;


  $aLoggers        = LoggerManager::getLoggersOnlyByFilter($aLoggersFilter);


  $oPageLayout->sViewPath = getAdminView('loggers/loggers_overview', 'loggers');
}

# include template
include_once getAdminView('layout');

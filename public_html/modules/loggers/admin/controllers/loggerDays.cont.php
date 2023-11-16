<?php
# check if controller is required by index.php
if (!defined('ACCESS')) {
  die;
}


global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Loggers uitzonderingsdagen";
$oPageLayout->sModuleName  = "Loggers uitzonderingsdagen";

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

  if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
    $oLoggersDay = LoggersDaysManager::getLoggersDayById(http_get("param2"));
    if (!$oLoggersDay || !$oLoggersDay->isEditable()) {
      http_redirect(ADMIN_FOLDER . "/");
    }
  } else {
    $oLoggersDay = new LoggersDay();
  }

  # action = save
  if (http_post("action") == 'save') {

    # load data in object
    $oLoggersDay->_load($_POST);

    if (empty($_POST['online'])) {
      $oLoggersDay->online = 0;
    }

    if (!empty(http_post("date"))) {
      $oLoggersDay->date = date('Y-m-d ', strtotime(http_post("date")));
    }

    # if object is valid, save
    if ($oLoggersDay->isValid()) {
      LoggersDaysManager::saveLoggersDay($oLoggersDay); //save userAccessGroup
      $_SESSION['statusUpdate'] = "Uitzonderingsrecord opgeslagen"; //save status update into session
      http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLoggersDay->loggerDayId);
    } else {

      Debug::logError("", "LoggersDay module php validate error", __FILE__, __LINE__, "Tried to save LoggersDay with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
      $oPageLayout->sStatusUpdate = "Er ging iets mis bij het opslaan van het record";
    }
  }

  $aFilter            = [];
  $aFilter['showAll'] = 1;
  $aFilter['active']  = 1;

  $oPageLayout->sViewPath = getAdminView(
    '/loggersDays/loggersDay_form',
    'loggers'
  );
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
  if (is_numeric(http_get("param2"))) {
    $oLoggersDay = LoggersDaysManager::getLoggersDayById(http_get("param2"));
  }


  if ($oLoggersDay && LoggersDaysManager::deleteLoggersDay($oLoggersDay)) {
    $_SESSION['statusUpdate'] = "Uitzonderingsrecord verwijderd"; //save status update into session
  } else {
    $_SESSION['statusUpdate'] = "Uitzonderingsrecord niet verwijderd"; //save status update into session
  }
  http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-checkName') {

  # check if name exists
  $oLoggersDay = LoggersDaysManager::getLoggersDayByName(http_get('name'));
  if ($oLoggersDay && $oLoggersDay->loggerDayId != http_get('loggerDayId')) {
    echo 'false';
  } else {
    echo 'true';
  }
  die;
} elseif (http_get("param1") == 'ajax-setOnline') {
  $bOnline       = http_get("online");
  $bAjax         = http_get("ajax", false); //controller requested by ajax
  $iLoggerDayId  = http_get("param2");
  $oLoggersDay   = LoggersDaysManager::getLoggersDayById($iLoggerDayId);
  $sLockedReason = '';
  $oResObj       = new stdClass(); //standard class for json feedback
  // update online for user
  if ($oLoggersDay && $oLoggersDay->isOnlineChangeable()) {
    $oResObj->success      = LoggersDaysManager::updateOnlineByLoggerId($bOnline, $iLoggerDayId);
    $oResObj->loggerDayId  = $iLoggerDayId;
    $oResObj->online       = $bOnline;
    $oResObj->reason       = $sLockedReason;
  }

  if (!$bAjax) {
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
  }
  print json_encode($oResObj);
  die;
} else {
  $aFilter['showAll'] = 1;
  $aLoggersDays      = LoggersDaysManager::getLoggersDaysByFilter($aFilter);
  $oPageLayout->sViewPath = getAdminView('/loggersDays/loggersDays_overview', 'loggers');
}

# include default template
include_once getAdminView('layout');

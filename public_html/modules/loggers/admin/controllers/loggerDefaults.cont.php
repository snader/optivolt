<?php
# check if controller is required by index.php
if (!defined('ACCESS')) {
  die;
}


global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Loggers defaults";
$oPageLayout->sModuleName  = "Loggers defaults";

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

  if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
    $oLoggersDefault = LoggersDefaultsManager::getLoggersDefaultById(http_get("param2"));
    if (!$oLoggersDefault || !$oLoggersDefault->isEditable()) {
      http_redirect(ADMIN_FOLDER . "/");
    }
  } else {
    $oLoggersDefault = new LoggersDefault();
  }


  # action = save
  if (http_post("action") == 'save') {

    # load data in object
    $oLoggersDefault->_load($_POST);



    if (empty($_POST['online'])) {
      $oLoggersDefault->online = 0;
    }

    # if object is valid, save
    if ($oLoggersDefault->isValid()) {
      LoggersDefaultsManager::saveLoggersDefault($oLoggersDefault); //save userAccessGroup
      $_SESSION['statusUpdate'] = "Uitzonderingsrecord opgeslagen"; //save status update into session
      http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oLoggersDefault->loggerDefaultId);
    } else {

      Debug::logError("", "LoggersDefaults module php validate error", __FILE__, __LINE__, "Tried to save LoggersDefaults with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
      $oPageLayout->sStatusUpdate = "Er ging iets mis bij het opslaan van het record";
    }
  }

  $aFilter            = [];
  $aFilter['showAll'] = 1;
  $aFilter['active']  = 1;

  $oPageLayout->sViewPath = getAdminView(
    '/loggersDefaults/loggersDefault_form',
    'loggers'
  );
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
  if (is_numeric(http_get("param2"))) {
    $oLoggersDefault = LoggersDefaultsManager::getLoggersDefaultById(http_get("param2"));
  }


  if ($oLoggersDefault && LoggersDefaultsManager::deleteLoggersDefault($oLoggersDefault)) {
    $_SESSION['statusUpdate'] = "Uitzonderingsrecord verwijderd"; //save status update into session
  } else {
    $_SESSION['statusUpdate'] = "Uitzonderingsrecord niet verwijderd"; //save status update into session
  }
  http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'ajax-checkName') {

  # check if name exists
  $oLoggersDefault = LoggersDefaultsManager::getLoggersDefaultByName(http_get('name'));
  if ($oLoggersDefault && $oLoggersDefault->loggerDayId != http_get('loggerDayId')) {
    echo 'false';
  } else {
    echo 'true';
  }
  die;
} elseif (http_get("param1") == 'ajax-setOnline') {
  $bOnline       = http_get("online");
  $bAjax         = http_get("ajax", false); //controller requested by ajax
  $iLoggerDefaultId  = http_get("param2");
  $oLoggersDefault   = LoggersDefaultsManager::getLoggersDefaultById($iLoggerDefaultId);
  $sLockedReason = '';
  $oResObj       = new stdClass(); //standard class for json feedback
  // update online for user
  if ($oLoggersDefault && $oLoggersDefault->isOnlineChangeable()) {
    $oResObj->success      = LoggersDefaultsManager::updateOnlineByLoggerId($bOnline, $iLoggerDefaultId);
    $oResObj->loggerDefaultId  = $iLoggerDefaultId;
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
  $aLoggersDefaults      = LoggersDefaultsManager::getLoggersDefaultsByFilter($aFilter);
  $oPageLayout->sViewPath = getAdminView('/loggersDefaults/loggersDefaults_overview', 'loggers');
}

# include default template
include_once getAdminView('layout');

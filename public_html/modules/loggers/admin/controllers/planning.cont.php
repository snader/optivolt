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


if (http_post("ajaxCallLog")) {
  if (empty(http_post("days")) || http_post("days") == 0) {
    return false;
  }
  $aInterfering = [];
  $aLoggers = LoggerManager::getLoggersOnlyByFilter();
  foreach ($aLoggers as $oLogger) {

    if (http_post("startDate")) {
      $aPlanningsFilter['startDate'] = date('Y-m-d', strtotime(http_post("startDate")));
    }
    if (http_post("days")) {
      $aPlanningsFilter['endDate'] = date('Y-m-d', strtotime(http_post("startDate") . " +" . (http_post("days") - 1) . " day"));
    }

    $aPlanningsFilter['loggerId'] = $oLogger->loggerId;

    // somehow this check wasn;t possible in mysql... no clue why
    if (http_post('planningId') && is_numeric(http_post('planningId'))) {
      $aPlanningsFilter['notPlanningId'] = http_post('planningId');
      $aPlanningsFilter['checkParentPlanningId'] = http_post('planningId');
    }

    $aPlanningItems        = PlanningManager::getPlanningByFilter($aPlanningsFilter);

    /*if ($aPlanningsFilter['loggerId']<3) {
      $to      = 'sander.voorn@gmail.com';
      $subject = 'ajaxCallLog';
      $message = '' . print_r($aPlanningsFilter, true) . print_r($aPlanningItems,true);
      $headers = 'From: test@optivolt.nl' . "\r\n" .
          'Reply-To: test@optivolt.nl' . "\r\n" .
          'X-Mailer: PHP/' . phpversion();

      mail($to, $subject, $message, $headers);
    }*/

    if (is_array($aPlanningItems) && count($aPlanningItems) > 0) {
      $aInterfering[$oLogger->loggerId] = 1;
    } else {
      $aInterfering[$oLogger->loggerId] = 0;
    }
  }
  $aInterfering[0] = 0; // offset
  echo json_encode($aInterfering);
  die;
}


// check if this can be added in planning
if (http_post("ajaxCall")) {

 
  if (empty(http_post("days")) || http_post("days") == 0) {
    return false;
  }

  if (empty(http_post("loggerId")) || http_post("loggerId") == 0) {
    return false;
  }

  // haal alle planningsitems op die in deze periode vallen (voor de zekerheid tot 15 dagen terug, immers kan een planning item voor vandaag begonnen zijn.)
  if (http_post("startDate")) {
    $aPlanningsFilter['startDate'] = date('Y-m-d', strtotime(http_post("startDate")));
  }
  if (http_post("days")) {
    $aPlanningsFilter['endDate'] = date('Y-m-d', strtotime(http_post("startDate") . " +" . (http_post("days") - 1) . " day"));
  }

  $aPlanningsFilter['loggerId'] = http_post('loggerId');

  // somehow this check wasn;t possible in mysql... no clue why
  if (http_post('planningId') && is_numeric(http_post('planningId'))) {
    $aPlanningsFilter['notPlanningId'] = http_post('planningId');


  }


  $aPlanningItems        = PlanningManager::getPlanningByFilter($aPlanningsFilter);
  foreach ($aPlanningItems as $iKey => $oPlanning) {
    if ($oPlanning->parentPlanningId == http_post('planningId')) {
      unset($aPlanningItems[$iKey]);
    }
  }

  /* warningType:
  * 1=impossible (reeds ingepland -> kan niet inplannen)
  * 2=valt in speciale dagen (bv weekend -> kan wel inplannen, maar waarschuwing)
  *
  */

  $aResult = array();
  $aResult['interfering'] = (is_array($aPlanningItems) ? count($aPlanningItems) : 0);
  $aResult['warning'] = '';
  $aResult['warningType'] = '';

  if ($aResult['interfering'] > 0) {
    if (is_array($aPlanningItems) && count($aPlanningItems) > 0) {

      foreach ($aPlanningItems as $oPlanning) {
        $oLogger = LoggerManager::getLoggerById($oPlanning->loggerId);
        $aResult['warning'] .= "<li>In de gekozen periode is <strong>logger " . $oLogger->name . '</strong> reeds ingepland bij "' . $oPlanning->companyName .
          '" van ' . date('d-m-Y', strtotime($oPlanning->startDate)) . ' t/m ' . date('d-m-Y', strtotime($oPlanning->endDate) . '.') . '</li>';
      }
    }

    if (!empty($aResult['warning'])) {
      $aResult['warning'] = 'Let op! De gekozen periode bevat conflicten:<ul>' . $aResult['warning'] . '</ul>';
      $aResult['warningType'] = 1;
    }
    //foreach (http_post('loggerId') as $iLoggerId) {
    //  $oLogger = LoggerManager::getLoggerById($iLoggerId);
    //  $aResult['warning'] .= '<li>In de gekozen periode is logger ' . $oLogger->name . ' reeds ingepland bij "' . $aPlanningItems[0]->companyName . '" van ' . date('d-m-Y', strtotime($aPlanningItems[0]->startDate)) . ' t/m ' . date('d-m-Y', strtotime($aPlanningItems[0]->endDate) . '.') . '</li>';
    //  $aResult['warningType'] = 1;
    //}
  } else {
    // check for speciale feestdagen
    $aLoggerDays        = LoggersDaysManager::getLoggersDatesByFilter($aPlanningsFilter);

    $aResult['interfering'] = (is_array($aLoggerDays) ? count($aLoggerDays) : 0);
    if ($aResult['interfering'] > 0) {

      foreach ($aLoggerDays as $oLoggersDay) {
        $aResult['warning'] .= '<li>' . date('d-m-Y', strtotime($oLoggersDay->date)) . ' - ' . $oLoggersDay->name . '</li>';
      }

      $aResult['warningType'] = '2';
    }

    // check for speciale dagen van de week
    $period = new DatePeriod(
      new DateTime($aPlanningsFilter['startDate']),
      new DateInterval('P1D'),
      new DateTime($aPlanningsFilter['endDate'])
    );
    $aLoggerDays = LoggersDaysManager::getLoggersDaysByFilter(['onlyDays' => 1]);
    $aDays = [];
    foreach ($period as $key => $value) {

      foreach ($aLoggerDays as $oLoggersDay) {

        if ($value->format('w') == $oLoggersDay->dayNumber) {
          $aResult['warning'] .= '<li>' . $value->format('d-m-Y') . ' - ' . $oLoggersDay->name . '</li>';
        }
      }
    }


    // dag erna?
    if (http_post("days")) {
      $aPlanningsFilter['endDate'] = date('Y-m-d', strtotime(http_post("startDate") . " +" . (http_post("days")) . " day"));
      $aPlanningsFilter['startDate'] = $aPlanningsFilter['endDate'];
      $aPlanningItems        = PlanningManager::getPlanningByFilter($aPlanningsFilter);

      if (is_array($aPlanningItems) && count($aPlanningItems) > 0) {
        foreach ($aPlanningItems as $oPlanning) {
          $oLogger = LoggerManager::getLoggerById($oPlanning->loggerId);
          $aResult['warning'] .= "<li>Een dag later staat <strong>logger " . $oLogger->name . "</strong> ingepland bij " . $oPlanning->companyName . '</li>';
        }
      }
    }

    // output
    if (!empty($aResult['warning'])) {
      $aResult['warning'] = 'Let op! De gekozen periode bevat bijzonderheden:<ul>' . $aResult['warning'] . '</ul>';
      $aResult['warningType'] = '2';
    }
  }


  echo implode("|", $aResult);

  die;
}
// end ajax handling



if (http_get("param1") == 'planning-bewerken' && is_numeric(http_get("param2"))) {

  $oPlanning = PlanningManager::getPlanningById(http_get("param2"));
  if (empty($oPlanning)) {
    http_redirect(ADMIN_FOLDER . "/");
  }

  if ($oPlanning->parentPlanningId) {
    $oPlanning = $oPlanning->getParentPlanning();
    if (empty($oPlanning)) {
      http_redirect(ADMIN_FOLDER . "/");
    } else {

      http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/planning-bewerken/' . $oPlanning->planningId);
    }
  }

  $aLoggers = $oPlanning->getLoggers();
  if (empty($aLoggers)) {
    http_redirect(ADMIN_FOLDER . "/");
  }

  $aAllCustomers = CustomerManager::getCustomersByFilter();

  # action = save existing PLANNING
  if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

    $iRandomColor = Rand(1, 18);

    // check if previously saved loggers are still needed, if not, delete corresponding planningitem
    foreach ($aLoggers as $iPlanningId => $oCurrentLogger) {
      //if (!in_array($oCurrentLogger->loggerId, $_POST['loggerId'])) {
      $oPlanningToDelete = PlanningManager::getPlanningById($iPlanningId);
      if ($oPlanningToDelete) {
        // wat als we perongeluk de parentId planning deleten, dan worden ze allemaal gedelete, maar ook weer aangemaakt, hierna
        PlanningManager::deletePlanning($oPlanningToDelete);
      }
      //}
    }

    // save them all
    $iCount = 0;
    $iParentPlanningId = null;
    foreach ($_POST['loggerId'] as $iLoggerId) {

      $oPlanning = new Planning();
      $iCount++;

      # load data in object
      $oPlanning->_load($_POST);
      $oPlanning->loggerId = $iLoggerId;
      $oPlanning->parentPlanningId = $iParentPlanningId;

      if (http_post('days') == '-') {
        $oPlanning->days = http_post('daystoo');
      } else {
        $oPlanning->days = http_post('days');
      }

      $oPlanning->startDate = date('Y-m-d ', strtotime(http_post("startDate")));
      $oPlanning->endDate = date('Y-m-d', strtotime(http_post("startDate") . " +" . ($oPlanning->days - 1) . " day"));

      $aAccountmanagers = [];
      foreach (http_post("accountmanagers", []) as $iUserId) {
        $aAccountmanagers[] = new User(['userId' => $iUserId]);
      }

      # set accountmanagers into planning item (only in first = parent)
      if ($iCount == 1) {
        $oPlanning->setAccountmanagers($aAccountmanagers);
      }

      if (empty($oPlanning->color)) {
        $oPlanning->color = $iRandomColor;
      }

      if ($oPlanning->isValid()) {

        PlanningManager::savePlanning($oPlanning); //save object
        if ($iCount == 1) {
          $iParentPlanningId = $oPlanning->planningId;
        }
      }
    }


    $_SESSION['statusUpdate'] = sysTranslations::get('planning_saved'); //save status update into session

    saveLog(
      ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPlanning->planningId,
      ucfirst(http_get("param1")) . ' planning #' . $oPlanning->planningId . ' (' . CustomerManager::getCustomerById($oPlanning->customerId)->companyName . ')',
      arrayToReadableText(object_to_array($oPlanning))
    );

    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '#' . $oPlanning->planningId);

  }

  $oPageLayout->sViewPath = getAdminView('loggers/loggers_planning_form', 'loggers');
}

# delete planning object
elseif (http_get("param1") == 'planning-verwijderen' && is_numeric(http_get("param2"))) {
  if (CSRFSynchronizerToken::validate()) {
    if (is_numeric(http_get("param2"))) {
      $oPlanning = PlanningManager::getPlanningById(http_get("param2"));
    }

    # trigger error
    if (empty($oPlanning)) {
      $_SESSION['statusUpdate'] = "Planningsitem kon niet verwijderd worden"; //save status update into session
      http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    if (!empty($oPlanning) && PlanningManager::deletePlanning($oPlanning)) {
      $_SESSION['statusUpdate'] = "Planningsitem is verwijderd"; //save status update into session

      saveLog(
        ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPlanning->planningId,
        'Verwijderen planning #' . $oPlanning->planningId . ' (' . CustomerManager::getCustomerById($oPlanning->customerId)->companyName . ')',
        arrayToReadableText(object_to_array($oPlanning))
      );

    } else {
      $_SESSION['statusUpdate'] = "Planningsitem kon niet verwijderd worden";; //save status update into session
    }
  }
  http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
}


# NEW planning form and SAVING
elseif (
  http_get("param1") == 'inplannen' && http_get("param2") && substr_count(http_get("param2"), '_') == 1
) {

  $aParts = explode('_', http_get("param2"));
  $oLogger = LoggerManager::getLoggerById($aParts[0]);
  $aLoggers[] = $oLogger;

  $oPlanning = new Planning();
  $oPlanning->loggerId = $oLogger->loggerId;

  $iDays = 1;
  // dragged selection
  if (http_get("param3") && substr_count(http_get("param3"), '_') == 1) {
    $aPartsEnd = explode('_', http_get("param3"));
    $aLoggers = LoggerManager::getLoggersOnlyByFilter(['min' => $oLogger->loggerId, 'max' => $aPartsEnd[0]]);

    $oPlanning->days = round(((int)$aPartsEnd[1] - (int)$aParts[1]) / (60 * 60 * 24) + 1);
  }

  // customer
  if (http_get("param3") && is_numeric(http_get("param3"))) {
    $oCustomer = CustomerManager::getCustomerById(http_get("param3"));

    if ($oCustomer && $oCustomer->online) {
      $oPlanning->customerId = $oCustomer->customerId;
    }
  }

  if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

    $iRandomColor = Rand(1, 18);

    $oPlanning = new Planning();

    $iCount = 0;
    $iParentPlanningId = null;
    foreach ($_POST['loggerId'] as $iLoggerId) {

      $oPlanning = new Planning();
      $iCount++;

      # load data in object
      $oPlanning->_load($_POST);
      $oPlanning->loggerId = $iLoggerId;
      $oPlanning->parentPlanningId = $iParentPlanningId;

      if (http_post('days') == '-') {
        $oPlanning->days = http_post('daystoo');
      } else {
        $oPlanning->days = http_post('days');
      }

      $oPlanning->startDate = date('Y-m-d ', strtotime(http_post("startDate")));
      $oPlanning->endDate = date('Y-m-d', strtotime(http_post("startDate") . " +" . ($oPlanning->days - 1) . " day"));

      $aAccountmanagers = [];
      foreach (http_post("accountmanagers", []) as $iUserId) {
        $aAccountmanagers[] = new User(['userId' => $iUserId]);
      }

      # set accountmanagers into planning item (only in first = parent)
      if ($iCount == 1) {
        $oPlanning->setAccountmanagers($aAccountmanagers);
      }

      if (empty($oPlanning->color)) {
        $oPlanning->color = $iRandomColor;
      }

      if ($oPlanning->isValid()) {

        PlanningManager::savePlanning($oPlanning); //save object

        saveLog(
          ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPlanning->planningId,
          ucfirst(http_get("param1")) . ' planning #' . $oPlanning->planningId . ' (' . CustomerManager::getCustomerById($oPlanning->customerId)->companyName . ')',
          arrayToReadableText(object_to_array($oPlanning))
        );

        if ($iCount == 1) {
          $iParentPlanningId = $oPlanning->planningId;
        }
      }
    }



    //if ($oPlanning->isValid()) {
    //  PlanningManager::savePlanning($oPlanning); //save object
    $_SESSION['statusUpdate'] = sysTranslations::get('planning_saved'); //save status update into session
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '#' . $oPlanning->planningId);
    //} else {
    //  Debug::logError("", "Logger module php oPlanning validate error", __FILE__, __LINE__, "Tried to save oPlanning with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
    //  $_SESSION['statusUpdate'] = sysTranslations::get('planning_not_saved');
    //}
  }

  $oPlanning->startDate = date('Y-m-d', $aParts[1]);

  $aAllCustomers = CustomerManager::getCustomersByFilter();

  # load view
  $oPageLayout->sViewPath = getAdminView('loggers/loggers_planning_form', 'loggers');

} # display overview planning
else {


  // EXPORT TO EXCEL
  if (
    http_get("param1") == 'exportxls'
  ) {

    include_once getAdminSnippet('excel_export', 'loggers');
  }


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

  if (!isset($aLoggersFilter['dates']) || empty($aLoggersFilter['dates'])) {
    $aLoggersFilter['dates'] = date('d-m-Y', strtotime("-10 day")) . ' - ' . date('d-m-Y', strtotime("+20 day"));
  }

  //[dates] => 11/01/2021 - 12/22/2021
  if (!empty($aLoggersFilter['dates'])) {
    $aDates = explode(' - ', $aLoggersFilter['dates']);
    $aLoggersFilter['startDate'] = date('Y-m-d', strtotime($aDates[0]));
    $aLoggersFilter['endDate'] = date('Y-m-d', strtotime(date("Y-m-d", strtotime($aDates[1])) . "+15 day")); // for getting planning items that go over the end of the horizontal day timeline
  }

  $aLoggersFilter['online'] = true;

  if (empty($aLoggersFilter['startDate'])) {
    $aLoggersFilter['startDate'] = date('Y-m-d', time());
  }
  if (empty($aLoggersFilter['endDate'])) {
    $aLoggersFilter['endDate'] =  date('Y-m-d', strtotime(date("Y-m-d", strtotime($sStartDate)) . "+3 months"));
  }

  // only for filter select
  $aTmpFilter = $aLoggersFilter;
  if (isset($aTmpFilter['customerId'])) {
    unset($aTmpFilter['customerId']);
  }

  $aLoggersWithoutCustomerFilter        = LoggerManager::getLoggersByFilter($aTmpFilter);

  // all planning loggers
  $aLoggers        = LoggerManager::getLoggersByFilter($aLoggersFilter);
  $aLoggersDays    = LoggersDaysManager::getLoggersDaysByFilter();


  // add extra day, only for horizontal day timeline
  $aLoggersFilter['endDate'] = date('Y-m-d', strtotime($aDates[1] . "+1 day"));

  $period = new DatePeriod(
    new DateTime($aLoggersFilter['startDate']),
    new DateInterval('P1D'),
    new DateTime($aLoggersFilter['endDate'])
  );

  // only for filter select

  $aAllCustomers = [];
  foreach ($aLoggersWithoutCustomerFilter as $oTmpLogger) {
    if (empty($oTmpLogger->customerId)) {
      continue;
    }
    $aAllCustomers[$oTmpLogger->customerId] = $oTmpLogger->companyName;
  }

  // prepare data in arrays
  foreach ($aLoggers as $oLogger) {


    $aKlantNamen[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->companyName;
    if (strtotime($oLogger->endDate) < strtotime($aLoggersFilter['startDate'])) {
      $aKlantNamen[$oLogger->mainLoggerId][$oLogger->customerId] = '';
    }
    if (strtotime($oLogger->startDate) >= strtotime($aLoggersFilter['endDate'])) {
      $aKlantNamen[$oLogger->mainLoggerId][$oLogger->customerId] = '';
    }

    $aStartDate[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->startDate;
    $aAvailableFrom[$oLogger->mainLoggerId] = $oLogger->availableFrom;
    $aEndDate[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->endDate;
    $aDays[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->days;
    $aPlanningIds[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->planningId;
    $aParentPlanningIds[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->parentPlanningId;
    $aColors[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->color;
    $aComments[$oLogger->mainLoggerId][$oLogger->customerId] = $oLogger->comment;
  }

  asort($aAllCustomers);



  $oPageLayout->sViewPath = getAdminView('loggers/loggers_planning_overview', 'loggers');
}

# include template
include_once getAdminView('layout');

<?php

class Planning extends Model
{

  public  $planningId;
  public  $parentPlanningId;
  public  $loggerId;
  public  $customerId;
  public  $comment;
  public  $startDate;
  public  $endDate;
  public  $days          = 1;
  public  $color         = null;
  public  $created;
  public  $modified;

  public $accountmanagers = [];

  private $properties = [];

  public function __set($name, $value) {
      $this->properties[$name] = $value;
  }

  public function __get($name) {
      return $this->properties[$name] ?? null;
  }

  /**
   * validate object
   */

  public function validate()
  {

    if (empty($this->days)) {
      $this->setPropInvalid('days');
    }
    if (empty($this->startDate)) {
      $this->setPropInvalid('startDate');
    }
    if (!is_numeric($this->loggerId)) {
      $this->setPropInvalid('loggerId');
    }
    if (!is_numeric($this->customerId)) {
      $this->setPropInvalid('customerId');
    }
  }

  /**
   * check if item is editable
   *
   * @return Boolean
   */
  public function isEditable()
  {
    
    if (UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
      return true;
    }
    return false;
  }

  /**
   * check if item is deletable
   *
   * @return Boolean
   */
  public function isDeletable()
  {
    if (UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
      return true;
    }
    return false;
  
  }

  /**
   *
   */
  public function getLogger()
  {

    if ($this->loggerId) {
      return LoggerManager::getLoggerById($this->loggerId);
    }

    return false;
  }

  /**
   *
   */
  public function getLoggers()
  {
    $aLoggers = [];
    $aLoggers[$this->planningId] = $this->getLogger();
    $aPlanningsItems = PlanningManager::getChildsByParentId($this->planningId);
    foreach ($aPlanningsItems as $oChildPlanning) {

      $aLoggers[$oChildPlanning->planningId] = LoggerManager::getLoggerById($oChildPlanning->loggerId);
    }
    return $aLoggers;
  }


  /**
   *
   */
  public function getColor($i = null)
  {

    if ($this->color) {
      return 'soc-' . $this->color;
    }
    if ($i) {
      return 'soc-' . $i;
    }


    return null;
  }

  /**
   *
   */
  public function getParentPlanning()
  {

    if ($this->parentPlanningId) {
      return PlanningManager::getPlanningById($this->parentPlanningId);
    }
    return null;
  }

  /**
   *
   */
  public function getAccountManagers()
  {

    if (!empty($this->planningId) && empty($this->accountmanagers)) {
      $this->accountmanagers = PlanningManager::getAccountmanagersByPlanningId($this->planningId);
    }


    return $this->accountmanagers;
  }



  /**
   * set accountmanagers Users
   *
   * @param array of User objects
   */
  public function setAccountManagers(array $aUsers)
  {
    $this->accountmanagers = $aUsers;
  }



}

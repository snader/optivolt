<?php

class LoggersDay extends Model
{

  public  $loggerDayId;

  public  $name;
  public  $online          = 1;
  public  $dayNumber       = null;
  public  $date            = null;


  /**
   * validate object
   */

  public function validate()
  {

    if (empty($this->name)) {
      $this->setPropInvalid('name');
    }
    if (!is_numeric($this->online)) {
      $this->setPropInvalid('online');
    }
    if (!is_numeric($this->dayNumber) && empty($this->date)) {
      $this->setPropInvalid('dayNumber');
      $this->setPropInvalid('date');
    }
  }

  /**
   * check if item is editable
   *
   * @return Boolean
   */
  public function isEditable()
  {
    return true;
  }

  /**
   * check if item is deletable
   *
   * @return Boolean
   */
  public function isDeletable()
  {
    return true;
  }

  public function isOnlineChangeable()
  {
    return true;
  }

  /**
   * check if item is online (except with preview mode)
   *
   * @param bool $bPreviewMode
   *
   * @return bool
   */
  public function isOnline($bPreviewMode = false)
  {

    $bOnline = true;
    if (!$bPreviewMode) {
      if (!($this->online)) {
        $bOnline = false;
      }
    }


    return $bOnline;
  }
}

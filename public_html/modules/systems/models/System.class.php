<?php

class System extends Model
{

    const SYSTEM_TYPE_POWERLINER = 1;
    const SYSTEM_TYPE_MULTILINER = 2;
    const SYSTEM_TYPE_VLINER     = 3;

    public  $systemId;
    public  $floor;
    public  $locationId;
    public  $systemTypeId;
    public  $pos            = null;
    public  $name           = null;

    public  $model          = null;
    public  $columnA        = null;
    public  $machineNr      = null;
    public  $notice         = null;
    public  $online         = 1;
    public  $lastReportDate = null;
    public  $installDate    = null;

    public  $deleted        = 0;

    public  $created;
    public  $modified;

    /**
     * @var \Location
     */
    protected static $location;

    protected static $systemType;

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->locationId)) {
            $this->setPropInvalid('locationId');
        }
        if (!is_numeric($this->systemTypeId)) {
            $this->setPropInvalid('systemTypeId');
        }
        if (empty($this->pos)) {
            $this->setPropInvalid('pos');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (empty($this->model)) {
            $this->setPropInvalid('model');
        }


    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        //if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            if (!$this->isDeleted()) {
                return true;
            }
        //}
       // return false;
    }

    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        //if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            if (!$this->isDeleted()) {
                return true;
            }
        //}
        //return false;

    }

    /**
     * check if item is deleted
     *
     * @return Boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
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
                if (!($this->online) || $this->isDeleted()) {
                    $bOnline = false;
                }
            }
        }

        return $bOnline;
    }

    /**
     * @return \Location
     */
    public function getLocation()
    {
        if (!static::$location) {
            static::$location = LocationManager::getLocationById($this->locationId);
        }

        return static::$location;
    }

    /**
     * @return \SystemType
     */
    public function getSystemType()
    {
        if (!static::$systemType) {
            static::$systemType = SystemTypeManager::getSystemTypeById($this->systemTypeId);
        }

        return static::$systemType;
    }




}
<?php

class Location extends Model
{

    public  $locationId;
    public  $customerId;
    public  $name           = null;
    public  $notice         = null;
    public  $online         = 1;
    public  $deleted        = 0;

    public  $created;
    public  $modified;

    /**
     * @var \Customer
     */
    protected static $customer;


    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->customerId)) {
            $this->setPropInvalid('customerId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }


    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        if ($this->deleted) {
            return false;
        }
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            return true;
        }
        return false;
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
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {

        if ($this->deleted) {
            return false;
        }
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {

            if (count($this->getSystems()) > 0) {
                return false;
            }

            return true;
        }
        return false;
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
        if ($this->deleted) {
            return false;
        }
        $bOnline = true;
        if (!$bPreviewMode) {
            if (!($this->online)) {
                $bOnline = false;
            }
        }

        return $bOnline;
    }


    /**
     * @return \Customer
     */
    public function getCustomer()
    {
        if (!static::$customer) {
            static::$customer = CustomerManager::getCustomerById($this->customerId);
        }

        return static::$customer;
    }


    /**
     * @return \Systems
     */
    public function getSystems($aFilter = array())
    {

        $aFilter['locationId'] = $this->locationId;
        return SystemManager::getSystemsByFilter($aFilter);
    }

    /**
     *
     */
    public function countSystems($year = null) {
        return SystemManager::countSystemsByLocation($this->locationId, $year);
    }





}
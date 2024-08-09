<?php

class Customer extends Model
{

    public  $customerId                       = null;
    public  $debNr                            = null;
    public  $companyName                      = null;
    public  $companyAddress                   = null;
    public  $companyPostalCode                = null;
    public  $companyCity                      = null;
    public  $companyEmail                     = null;
    public  $companyPhone                     = null;
    public  $companyWebsite                   = null;
    public  $contactPersonName                = null;
    public  $contactPersonEmail               = null;
    public  $contactPersonPhone               = null;
    public  $password;
    public  $confirmCode                      = null;
    public  $online                           = 0;
    public  $deleted                          = 0;
    public  $locked; // locked timestamp
    public  $lockedReason; // reason for getting locked
    public  $lastLogin                        = null;
    public  $created                          = null;
    public  $modified                         = null;
    private $aCustomerGroups                  = null;
    private $aLocations                       = null;
    public  $countryId;
    public  $customerGroupId;

    private $properties = [];

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }
  
    public function __get($name) {
        return $this->properties[$name] ?? null;
    }

    /**
     * @var \Customer
     */
    protected static $customer;

    /**
     * @var \Country
     */
    protected static $country;

    /**
     * @return \Customer
     */
    public static function getCurrent()
    {
        if (!static::$customer) {
            static::$customer = Session::get(CustomerManager::SESSION) ?: null;
        }

        return static::$customer;
    }

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->debNr)) {
            $this->setPropInvalid('debNr');
        }

        if (empty($this->companyName)) {
            $this->setPropInvalid('companyName');
        }
        if (empty($this->companyAddress)) {
            $this->setPropInvalid('companyAddress');
        }
        if (empty($this->companyCity)) {
            $this->setPropInvalid('companyCity');
        }

        if (!empty($this->companyEmail) && !filter_var($this->companyEmail, FILTER_VALIDATE_EMAIL)) {
            $this->setPropInvalid('companyEmail');
        }
        //if (CustomerManager::emailExists($this->companyEmail, $this->customerId)) {
            //$this->setPropInvalid('emailExists');
        //}
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
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
        if (UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
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
        if (UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function isOnlineChangeable() {
        
        if ($this->deleted) {
            return false;
        }
        if (UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
            return true;
        }
        return false;

    }

    /**
     *
     */
    public function getSystems()
    {

        $aFilter['customerId'] = $this->customerId;
        $aFilter['orderBy'] = ['cast(`s`.`pos` as unsigned)' => 'ASC', '`s`.`pos`' => 'ASC'];

        $aFilter['showAll'] = 1;
        if (!UserManager::getCurrentUser()->isClientAdmin() && !UserManager::getCurrentUser()->isSuperAdmin()) {
            
            $aFilter['showAll'] = 0;
            
    
        }

        $aSystems = SystemManager::getSystemsByFilter($aFilter);
        return $aSystems;
    }

    /**
     * mask pasword for session use
     */
    function maskPass()
    {
        $this->password = 'XXX';
    }

    /**
     * get full name of client
     *
     * @return type string
     */
    function getFullName()
    {
        return _e($this->companyName . (!empty($this->companyCity) ? ', ' . $this->companyCity . ' ' : ' '));
    }

    /**
     * get all CustomGroup objects related to this Customer
     *
     * @return array CustomGroup $this->aCustomerGroups
     */
    public function getCustomerGroups()
    {
        if ($this->aCustomerGroups === null) {
            $this->aCustomerGroups = CustomerGroupManager::getCustomerGroupsByCustomerId($this->customerId);
        }

        return $this->aCustomerGroups;
    }

    /**
     * set customer CustomGroups
     *
     * @param array of CustomGroup objects
     */
    public function setCustomerGroups(array $aCustomerGroups)
    {
        $this->aCustomerGroups = $aCustomerGroups;
    }

    /**
     * get customer locations
     */
    public function getLocations() {

        $this->aLocations = LocationManager::getLocationsByFilter(['customerId' => $this->customerId]);
        return $this->aLocations;

    }

    /**
     *
     */
    public function getAppointments() {
        return CustomerManager::getAppointmentsByCustomerId($this->customerId);
    }

    /**
     * check whether customer is linked to the CustomerGroup
     *
     * @param int $iCustomerGroupId
     *
     * @return boolean
     */
    public function isLinkedToCustomerGroup($iCustomerGroupId)
    {
        # return by default false
        $bResult = false;

        # when not yet set, get linked CustomerGroups
        if (empty($this->aCustomerGroups)) {
            $this->getCustomerGroups();
        }

        # loop through linked CustomerGroups
        foreach ($this->aCustomerGroups as $oCustomerGroup) {
            # check whether user is linked to customGroup
            if ($oCustomerGroup->customerGroupId == $iCustomerGroupId) {
                $bResult = true;
            }
        }

        # return result
        return $bResult;
    }

    /**
     * set default required data for csv import
     */
    public function setDefaultCsvImportValues()
    {
        # set required data for database
        $this->companyName                      = 'U';
        $this->companyAddress                   = 'Unknown';
        $this->companyCity                      = 'Unknown';
        $this->companyEmail                     = '';
        $this->online                           = 1;
        $this->password                         = hashPasswordForDb(md5($this->companyEmail));
    }

    /**
     * @return \Country
     */
    public function getCountry()
    {
        if (!static::$country) {
            static::$country = CountryManager::getCountryById($this->countryId);
        }

        return static::$country;
    }




}

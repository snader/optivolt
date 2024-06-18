<?php

class Evaluation extends Model
{

    public  $evaluationId;
    public  $customerId;
    public  $installSat     = null;
    public  $anyDetails     = null;
    public  $conMeasured    = null;
    public  $workSat        = null;
    public  $answers        = null;
    public  $friendlyHelpfull = null;
    public  $remarks = null;
    public  $customerRelName = null;
    public  $signatureDate = null;
    public  $digitalSigned = 0;    

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
        if (empty($this->customerRelName)) {
            $this->setPropInvalid('customerRelName');
        }
        if (!is_numeric($this->digitalSigned)) {
            $this->setPropInvalid('digitalSigned');
        }
        


    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {

        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
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

        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            
            return true;
        }
        return false;
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

}
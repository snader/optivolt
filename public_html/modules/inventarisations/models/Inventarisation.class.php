<?php

class Inventarisation extends Model
{

    

    public  $inventarisationId;
    public  $parentInventarisationId;
    public  $loggerId       = null;
    public  $userId         = null;
    public  $customerId     = null;
    public  $customerName   = null;
    public  $name;
    public  $kva;
    public  $position;
    public  $freeFieldAmp    = null;
    public  $stroomTrafo     = null;
    public  $control;
    public  $type;
    public  $relaisNr;
    public  $engineKw;
    public  $turningHours;
    public  $photoNrs;
    public  $trafoNr;
    public  $mlProposed;
    public  $remarks;
   
    public  $created;
    public  $modified;

    private $aSubInventarisations;
    

    /**
     * validate object
     */

    public function validate()
    {
        
        
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

    /**
     * check if item is online (except with preview mode)
     *
     * @param bool $bPreviewMode
     *
     * @return bool
     */
    public function isOnline($bPreviewMode = false)
    {

        return true;
    }

 
    public function getSubInventarisations()
    {
        $this->aSubInventarisations = InventarisationManager::getSubInventarisations($this->inventarisationId);
        return $this->aSubInventarisations;
    }
    

    /*
     * Get customer
     * @return Customer
     */
    public function getCustomer()
    {
        return CustomerManager::getCustomerById($this->customerId);
    }
}
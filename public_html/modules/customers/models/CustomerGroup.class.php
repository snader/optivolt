<?php

class CustomerGroup extends Model
{

    const CUSTOMERGROUP_GENERAL = 'general';

    public  $customerGroupId = null;
    public  $title;
    public  $name; // unqiue name for customer group to use in code, for special handling, instead of ID
    public  $created         = null;
    public  $modified        = null;
    private $aClients;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }

        if (!empty($this->name) && ($oCustomerGroup = CustomerGroupManager::getCustomerGroupByName($this->name))) {
            if ($oCustomerGroup->customerGroupId != $this->customerGroupId) {
                $this->setPropInvalid('name');
            }
        }
    }

    /**
     * check if item is deletable. Main category is never deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return $this->name != self::CUSTOMERGROUP_GENERAL ? true : false;
    }

    /**
     * get all customer of this group
     *
     * @param $bFilterOnline
     * @param $bExcludeBounced
     * @param $bFilterBounced
     *
     * @return array Clients $this->aClients
     */
    public function getClients($bFilterOnline = false, $bExcludeBounced = false, $bFilterBounced = false)
    {
        $this->aClients = CustomerManager::getCustomersByCustomerGroupId($this->customerGroupId, $bFilterOnline, $bExcludeBounced, $bFilterBounced);

        return $this->aClients;
    }

    /**
     * return count result of customers of this group
     *
     * @param $bFilterOnline
     *
     * @return int $iResult
     */
    public function getAmountOfClients($bFilterOnline = false)
    {
        return CustomerManager::getAmountOfCustomersByCustomerGroupId($this->customerGroupId, $bFilterOnline);
    }

 

}

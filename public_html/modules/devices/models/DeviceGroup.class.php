<?php

class DeviceGroup extends Model
{

    const DEVICEGROUP_GENERAL = 'general';

    public  $deviceGroupId = null;
    public  $title;
    public  $name; // unqiue name for device group to use in code, for special handling, instead of ID
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

        if (!empty($this->name) && ($oDeviceGroup = DeviceGroupManager::getDeviceGroupByName($this->name))) {
            if ($oDeviceGroup->deviceGroupId != $this->deviceGroupId) {
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
        return $this->name != self::DEVICEGROUP_GENERAL ? true : false;
    }

    /**
     * get all device of this group
     *
     * @param $bFilterOnline
     * @param $bExcludeBounced
     * @param $bFilterBounced
     *
     * @return array Clients $this->aClients
     */
    public function getClients($bFilterOnline = false, $bExcludeBounced = false, $bFilterBounced = false)
    {
        $this->aClients = DeviceManager::getDevicesByDeviceGroupId($this->deviceGroupId, $bFilterOnline, $bExcludeBounced, $bFilterBounced);

        return $this->aClients;
    }

    /**
     * return count result of devices of this group
     *
     * @param $bFilterOnline
     *
     * @return int $iResult
     */
    public function getAmountOfClients($bFilterOnline = false)
    {
        return DeviceManager::getAmountOfDevicesByDeviceGroupId($this->deviceGroupId, $bFilterOnline);
    }

 

}

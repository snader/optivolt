<?php

class Device extends Model
{

    

    public  $deviceId;
        
    public  $name;
    public  $brand;
    public  $type;
    public  $serial;
    public  $online          = 1;    
    public  $created;
    public  $modified;
    
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
      
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
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

    
    /**
     * 
     * 
     *  */                             
    public function getCertificates() {

        if (is_numeric($this->deviceId)) {
            $aCertificates = CertificateManager::getCertificatesByDeviceId($this->deviceId);
        } else {
            return null;
        }

        return $aCertificates;
        
    }

}
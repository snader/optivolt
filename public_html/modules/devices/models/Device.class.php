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

    
}
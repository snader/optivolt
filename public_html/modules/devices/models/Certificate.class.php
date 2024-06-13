<?php

class Certificate extends Model
{

    public  $certificateId;
    public  $deviceId;
    public  $userId;
        
    public  $vbbNr;
    public  $testInstrument;
    public  $testSerialNr;
    public  $nextcheck;
    public  $visualCheck;    
    public  $weerstandBeLeRPE;    
    public  $isolatieWeRISO;    
    public  $lekstroomIEA;    
    public  $lekstroomTouch;    
    public  $created;
    public  $modified;
    

    /**
     * validate object
     */

    public function validate()
    {
      
        if (empty($this->deviceId)) {
            $this->setPropInvalid('deviceId');
        }
        if (!is_numeric($this->userId)) {
            $this->setPropInvalid('userId');
        }
        if (empty($this->visualCheck)) {
            $this->setPropInvalid('visualCheck');
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

    
}

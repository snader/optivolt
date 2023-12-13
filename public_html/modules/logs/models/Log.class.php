<?php

class Log extends Model
{

    

    public  $logId;
    public  $userId      = null;
    public  $title;
    public  $name;
    public  $link = null;
    public  $content;
    public  $online          = 1;
    public  $created;
    public  $modified;
    
    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->userId)) {
            $this->setPropInvalid('userId');
        }
        if (empty($this->title)) {
            $this->setPropInvalid('title');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (empty($this->link)) {
            $this->setPropInvalid('link');
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

   
}
<?php

class Setting extends Model
{

    const IMAGES_PATH = '/uploads/images/settings';

    public $settingId;
    public $name; //name of the user
    public $value; //username for login
    public $created; //created timestamp
    public $modified; //last modified timestamp

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

}

?>
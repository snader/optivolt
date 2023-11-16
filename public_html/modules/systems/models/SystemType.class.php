<?php

class SystemType extends Model
{

    public  $systemTypeId;
    public  $typeName       = null;


    /**
     * validate object
     */

    public function validate()
    {

        if (empty($this->typeName)) {
            $this->setPropInvalid('typeName');
        }
    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        return false;
    }

    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return false;
    }

    public function name()
    {
        return $this->typeName;
    }
}

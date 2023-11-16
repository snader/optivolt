<?php

class CountryTranslation extends Model
{

    public $countryTranslationId;
    public $countryId;
    public $localeId;
    public $name; //name of the countryTranslation

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->countryId)) {
            $this->setPropInvalid('countryId');
        }
        if (empty($this->localeId)) {
            $this->setPropInvalid('localeId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
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

}

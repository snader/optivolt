<?php

class Country extends Model
{

    public $countryId;
    public $code; // ISO 3166-1 alpha-2 country code
    public $nativeName; // native name
    public $currency; // currency of country
    public $languages; // official countrys spoken in country (not sure if list is reliable)
    public $code3; // ISO 3166-1 alpha-3 country code

    protected $aTranslations = [];

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->code)) {
            $this->setPropInvalid('code');
        }
        if (empty($this->nativeName)) {
            $this->setPropInvalid('nativeName');
        }
        if (empty($this->currency)) {
            $this->setPropInvalid('currency');
        }
        if (empty($this->code3)) {
            $this->setPropInvalid('code3');
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
    /**
     * @param string $mList
     *
     * @return \CountryTranslation|\CountryTranslation[]
     */
    public function getTranslations($mList = 'auto')
    {
        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $oTranslation = CountryTranslationManager::getCountryTranslationByCountryId($this->countryId, $mList);
                $this->aTranslations[$mList] = $oTranslation ?: null;
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $oTranslation = CountryTranslationManager::getCountryTranslationByCountryId($this->countryId, Locales::getAdminLocale()->localeId);
                        $this->aTranslations[$mList] = $oTranslation ?: null;
                        break;
                    case 'auto':
                    case 'default':
                        $oTranslation = CountryTranslationManager::getCountryTranslationByCountryId($this->countryId, Locales::locale());
                        $this->aTranslations[$mList] = $oTranslation ?: null;
                        break;
                    default:
                        die('no option');
                        break;
                }
            }
        }

        return $this->aTranslations[$mList];
    }

}

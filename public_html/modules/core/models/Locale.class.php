<?php

namespace ACMS;

class Locale extends \Model
{

    const DATE_FORMAT_NL = '%d-%m-%Y';
    const DATE_FORMAT_EN = '%m-%d-%Y';

    public  $localeId;
    public  $languageId;
    public  $countryId;
    public  $domain;
    public  $subdomain;
    public  $prefix1;
    public  $prefix2;
    public  $urlFormat; // saved url format based on domain, subdomain and prefix properties
    public  $online    = 0;
    public  $order     = 9999;
    public  $dateFormat;
    public  $created;
    public  $createdBy;
    public  $modified;
    public  $modifiedBy;
    private $oLanguage = null;
    private $oCountry  = null;

    public function __construct($aData = [], $bStripTags = true)
    {
        parent::__construct($aData, $bStripTags);
        if (empty($this->domain)) {
            $this->domain = \Server::get('SERVER_NAME');
        }
    }

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (!is_numeric($this->countryId)) {
            $this->setPropInvalid('countryId');
        }
        if (empty($this->domain)) {
            $this->setPropInvalid('domain');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }

        // check URL format already exists for other locale
        if (!$this->getURLFormat(true) || \LocaleManager::urlFormatExists($this->getURLFormat(), $this->localeId)) {
            $this->setPropInvalid('urlFormat');
        }

        // check if language and country combination already exists for other locale
        if ($this->languageId && $this->countryId && \LocaleManager::languageAndCountryExists($this->languageId, $this->countryId, $this->localeId)) {
            $this->setPropInvalid('languageId');
            $this->setPropInvalid('countryId');
        }

        if (empty($this->dateFormat)) {
            $this->setPropInvalid('dateFormat');
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
     * get URL Format
     *
     * @param boolean $bReset
     *
     * @return string
     */
    public function getURLFormat($bReset = false)
    {
        if (empty($this->urlFormat) || $bReset) {
            $this->urlFormat = $this->generateURLFormat();
        }

        return $this->urlFormat;
    }

    /**
     * generate URL Format based on some properties
     *
     * @return string
     */
    public function generateURLFormat()
    {
        $sURLFormat = '';
        if ($this->subdomain) {
            $sURLFormat .= ($this->subdomain == 'language' ? $this->getLanguage()->code : $this->getCountry()->code) . '.';
        }
        $sURLFormat .= $this->domain;
        if ($this->prefix1) {
            $sURLFormat .= '/' . ($this->prefix1 == 'language' ? $this->getLanguage()->code : $this->getCountry()->code);
        }
        if ($this->prefix2) {
            $sURLFormat .= '_' . $this->getCountry()->code;
        }

        return strtolower($sURLFormat);
    }

    /**
     * get URL prefix
     *
     * @return string
     */
    public function getURLPrefix()
    {
        $sURLPrefix = '';
        if ($this->prefix1) {
            $sURLPrefix .= ($this->prefix1 == 'language' ? $this->getLanguage()->code : $this->getCountry()->code);
        }
        if ($this->prefix2) {
            $sURLPrefix .= '_' . $this->getCountry()->code;
        }

        return strtolower($sURLPrefix);
    }

    /**
     * get language
     *
     * @return \Language
     */
    public function getLanguage()
    {
        if ($this->oLanguage === null) {
            $this->oLanguage = \LanguageManager::getLanguageById($this->languageId);
        }

        return $this->oLanguage;
    }

    /**
     * get country
     *
     * @return \Country
     */
    public function getCountry()
    {
        if ($this->oCountry === null) {
            $this->oCountry = \CountryManager::getCountryById($this->countryId);
        }

        return $this->oCountry;
    }

    /**
     * get locale formatted info
     *
     * @return string
     */
    public function getLocale($iType = 2)
    {
        switch ($iType) {
            case 2:
                return $this->getLanguage()->code . '_' . $this->getCountry()->code;
            case 3:
                return $this->getLanguage()->code3 . '_' . $this->getCountry()->code3;
            default:
                return null;
        }
    }

    /**
     * get locales list with needed locales to use PHP setlocale()
     *
     * @return array
     */
    public function getLocaleList()
    {
        return [
            $this->getLocale(2) . '.utf8', $this->getLocale(2), $this->getLocale(3) . '.utf8',
            $this->getLocale(3),
        ];
    }

    /**
     * return display name
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getCountry()->nativeName . ' - ' . $this->getLanguage()->nativeName;
    }

}

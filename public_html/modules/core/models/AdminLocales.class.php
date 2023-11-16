<?php

class AdminLocales
{

    private static $oAdminLocale = null;
    private static $iLocaleId    = null;
    private static $iLanguageId  = null;
    private static $iCountryId   = null;
    private static $aLanguages   = null;

    public static function initialize()
    {

        self::getAdminLocale();

        // set locale, language and country
        self::$iLocaleId   = self::getAdminLocale()->localeId;
        self::$iLanguageId = self::getAdminLocale()->languageId;
        self::$iCountryId  = self::getAdminLocale()->countryId;
        self::$aLanguages  = self::getLanguages();

        return true;
    }

    /**
     * get Admin Locale
     *
     * @return ACMS\Locale
     */
    public static function getAdminLocale()
    {

        if (http_post('action') == 'setAdminLocale' && is_numeric(http_post('localeId'))) {
            $_SESSION['oCurrentAdminLocale'] = LocaleManager::getLocaleById(http_post('localeId'));
            SiteTranslations::reset();
            http_redirect(getCurrentUrl());
        } elseif (!isset($_SESSION['oCurrentAdminLocale'])) {
            $aLocales                        = LocaleManager::getLocalesByFilter();
            $_SESSION['oCurrentAdminLocale'] = $aLocales[0];
        }

        // Locale not set in object, do once from session
        if (empty(self::$oAdminLocale)) {
            self::$oAdminLocale = $_SESSION['oCurrentAdminLocale'];
        }

        return self::$oAdminLocale;
    }

    /**
     * get languages
     *
     * @return array with $oLanguage
     */
    public static function getLanguages()
    {
        if (empty(self::$aLanguages)) {
            self::$aLanguages = LanguageManager::getLanguagesByFilter(['hasLocale' => true]);
        }

        return self::$aLanguages;
    }

    /**
     * returns the localeId
     *
     * @return int
     */
    public static function locale()
    {
        return !empty(self::$iLocaleId) ? self::$iLocaleId : -1;
    }

    /**
     * returns the languageId
     *
     * @return int
     */
    public static function language()
    {
        return !empty(self::$iLanguageId) ? self::$iLanguageId : -1;
    }

    /**
     * returns the countryId
     *
     * @return int
     */
    public static function country()
    {
        return !empty(self::$iCountryId) ? self::$iCountryId : -1;
    }

}

?>

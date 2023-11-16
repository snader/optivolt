<?php

class Locales
{

    private static $oLocale     = null;
    private static $sURLFormat  = null;
    private static $iLanguageId = null;
    private static $iCountryId  = null;
    private static $iLocaleId   = null;

    public static function initialize()
    {
        if (self::getLocale()) {
            self::checkLocale();
        }

        // set language, locale and country
        self::$iLanguageId = self::getLocale()->languageId;
        self::$iCountryId  = self::getLocale()->countryId;
        self::$iLocaleId   = self::getLocale()->localeId;

        // set locales LC_TIME
        setlocale(
            LC_TIME,
            self::getLocale()
                ->getLocaleList()
        );

        return true;
    }

    /**
     * get locale for admin
     *
     * @return \ACMS\Locale|\ACMS\Locale
     */
    public static function getAdminLocale()
    {
        $iLocaleId = UserManager::getCurrentUser()
            ->getLanguage()->languageId;

        $oLocale = LocaleManager::getLocalesByFilter(['languageId' => $iLocaleId], 1);

        if (!$oLocale) {
            $oLocale = LocaleManager::getLocalesByFilter([], 1);
        }

        return $oLocale;
    }

    /**
     * get Locale
     *
     * @return ACMS\Locale
     */
    public static function getLocale()
    {
        // Locale not yet in sessions, get from database
        if (!isset($_SESSION['oCurrentLocale'])) {
            $oLocale = LocaleManager::getLocaleByURLFormat(self::getCurrentURLFormat());
            if ($oLocale) {
                $_SESSION['oCurrentLocale'] = $oLocale;
            } else {
                $oLocale         = LocaleManager::getLocalesByFilter([], 1);
                $oLocale->domain = self::getCurrentURLFormat();
                $oLocale->generateURLFormat();
                LocaleManager::saveLocale($oLocale);
                if (Request::getURL() == '/admin') {
                    Router::redirect('/admin');
                } else {
                    Router::redirect('/');
                }
            }
        }

        // Locale not set in object, do once from session
        if (empty(self::$oLocale)) {
            self::$oLocale = $_SESSION['oCurrentLocale'];
        }

        // Locale is not valid for requested URL
        if (!self::checkLocale()) {
            // reset current locale
            self::resetLocale();

            // get new Locale
            return self::getLocale();
        }

        return self::$oLocale;
    }

    /**
     * get current URL Format for requested URL
     *
     * @return string
     */
    public static function getCurrentURLFormat()
    {
        if (empty(self::$sURLFormat)) {
            $sURLFormat = '';

            $sCurrUrlPath = getCurrentUrlPath(false, false, true);
            preg_match('#^(?:/([a-zA-Z]{2})(?:_([a-zA-Z]{2}))?)(?:$|/)#', $sCurrUrlPath, $aMatches);

            if (Server::get('SERVER_NAME')) {
                $sURLFormat .= Server::get('SERVER_NAME');
            }

            if (isset($aMatches[1])) {
                $sURLFormat .= '/' . $aMatches[1];
            }

            if (isset($aMatches[2])) {
                $sURLFormat .= '_' . $aMatches[2];
            }
            self::$sURLFormat = $sURLFormat;
        }

        return self::$sURLFormat;
    }

    /**
     * check if locale is still the same as requested
     */
    private static function checkLocale()
    {
        // URL format changed so reset Locale
        if (self::$oLocale->getURLFormat() !== self::getCurrentURLFormat()) {
            return false;
        }

        return true;
    }

    /**
     * reset locale in object and session
     */
    private static function resetLocale()
    {
        self::$oLocale = null;
        unset($_SESSION['oCurrentLocale']);
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

    /**
     * returns the localeId
     *
     * @return int
     */
    public static function locale()
    {
        return !empty(self::$iLocaleId) ? self::$iLocaleId : -1;
    }

}

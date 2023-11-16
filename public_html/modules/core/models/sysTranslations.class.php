<?php

class sysTranslations
{

    private static $systemTranslations = null;
    private static $systemLanguageId   = null;
    private static $tmpLanguageIdSet   = false; // temp language id is set, prevent some default stuff

    /**
     * Get the translation for a text
     *
     * @param string $sLabel
     *
     * @return string
     */
    public static function get($sLabel, $sList = 'all')
    {

        /** start language stuff * */
        // system language not set, try to get from session
        if (empty(self::$systemLanguageId)) {
            self::$systemLanguageId = http_session('systemLanguageId', null);
        }

        // system language still not set, get from user
        if (empty(self::$systemLanguageId)) {
            $oCurrentUser = http_session('oCurrentUser', false);
            if ($oCurrentUser) {
                self::$systemLanguageId = $oCurrentUser->systemLanguageId;
            } else {
                self::$systemLanguageId = SystemLanguage::default_languageId;
            } // default languageId

            if (!self::$tmpLanguageIdSet) {
                // temp language id is not set so save in session
                $_SESSION['systemLanguageId'] = self::$systemLanguageId; // set in session for next request
            }
        }

        /** start translations stuff * */
        // system translations not set, do set
        if (empty(self::$systemTranslations[$sList][self::$systemLanguageId])) {
            self::setSystemTranslations($sList);
        }

        // translations should be set by now so try to get text by label
        if (array_key_exists($sLabel, self::$systemTranslations[$sList][self::$systemLanguageId])) {
            return self::$systemTranslations[$sList][self::$systemLanguageId][$sLabel];
        }

        return '[' . $sLabel . ']';
    }

    /**
     * check if the translation exists for a text
     *
     * @param string $sLabel
     *
     * @return string
     */
    public static function exists($sLabel, $sList = 'all')
    {
        // label should not be the as requested value
        return '[' . $sLabel . ']' != self::get($sLabel, $sList = 'all');
    }

    /**
     * set system translations, first from session and otherwise from database
     *
     * @param string $sList
     */
    private static function setSystemTranslations($sList = 'all')
    {
        // system translations are not set, get from session
        self::$systemTranslations = http_session('systemTranslations', null);

        // system translations still not set, get from database
        if (empty(self::$systemTranslations[$sList][self::$systemLanguageId])) {
            if (empty(self::$systemTranslations)) {
                self::$systemTranslations = [];
            }
            self::$systemTranslations[$sList]                          = [];
            self::$systemTranslations[$sList][self::$systemLanguageId] = [];

            switch ($sList) {
                case 'all':
                    $aSystemTranslations = SystemTranslationManager::getTranslationsByFilter(['systemLanguageId' => self::$systemLanguageId]);
                    break;
                case 'js':
                    $aSystemTranslations = SystemTranslationManager::getTranslationsByFilter([
                        'systemLanguageId' => self::$systemLanguageId,
                        'prefix'           => 'js_',
                    ]);
                    break;
                default:
                    $aSystemTranslations = [];
                    break;
            }

            foreach ($aSystemTranslations as $oSystemTranslation) {
                self::$systemTranslations[$sList][self::$systemLanguageId][$oSystemTranslation->label] = $oSystemTranslation->text;
            }
            $_SESSION['systemTranslations'] = self::$systemTranslations;
        }
    }

    /**
     * used for javascript translations
     *
     * @param string $sPrefix
     */
    public static function getTranslations($sList)
    {
        if (empty(self::$systemTranslations[$sList][self::$systemLanguageId])) {
            self::setSystemTranslations($sList);
        }

        return self::$systemTranslations[$sList][self::$systemLanguageId];
    }

    /**
     * reset system translations to get new ones from database
     */
    public static function reset()
    {
        self::$systemLanguageId   = null;
        self::$systemTranslations = null;
        unset($_SESSION['systemLanguageId']);
        unset($_SESSION['systemTranslations']);
        self::$tmpLanguageIdSet = false;
    }

    /**
     * set temp languageId
     *
     * @param type $iLanguageId
     */
    public static function setTmpLanguageId($iLanguageId)
    {
        self::$systemLanguageId = $iLanguageId;
        self::$tmpLanguageIdSet = true;
    }

    /**
     * reset temp languageId
     */
    public static function resetTmpLanguageId()
    {
        self::$systemLanguageId = null;
        self::$tmpLanguageIdSet = false;
    }

}

?>

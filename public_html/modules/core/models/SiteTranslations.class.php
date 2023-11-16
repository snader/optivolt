<?php

class SiteTranslations
{

    private static $siteTranslations = null;
    private static $languageId       = null;
    private static $tmpLanguageIdSet = false; // temp language id is set, prevent some default stuff

    /**
     * Get the translation for a text
     *
     * @param string $sLabel
     * @param string $sList
     * @param boolean $bRandomizeOutput
     *
     * @return string
     */

    public static function get($sLabel, $sList = 'all', $bRandomizeOutput  = true)
    {
        /** start language stuff * */
        // site language not set, try to get from session
        if (empty(self::$languageId)) {
            self::$languageId = Locales::language();
        }
        if (!self::$tmpLanguageIdSet) {
            // temp language id is not set so save in session
            $_SESSION['languageId'] = self::$languageId;
        }
        /** start translations stuff * */
        // site translations not set, do set
        if (empty(self::$siteTranslations[$sList][self::$languageId])) {
            self::setSiteTranslations($sList);
        }
        // translations should be set by now so try to get text by label
        if (array_key_exists($sLabel, self::$siteTranslations[$sList][self::$languageId])) {
            $sSiteTranslation = self::$siteTranslations[$sList][self::$languageId][$sLabel];
            // explode for random output
            if ($bRandomizeOutput) {
                $sSiteTranslationVariations = explode("|", $sSiteTranslation);
                $sSiteTranslation = $sSiteTranslationVariations[rand(0,count($sSiteTranslationVariations)-1)];
            }
            return $sSiteTranslation;
        }
        return '[' . $sLabel . ']';
    }

    /**
     * set site translations, first from session and otherwise from database
     *
     * @param string $sList
     */
    private static function setSiteTranslations($sList = 'all')
    {
        // site translations are not set, get from session
        self::$siteTranslations = http_session('siteTranslations', null);

        // site translations still not set, get from database
        if (empty(self::$siteTranslations[$sList][self::$languageId])) {
            if (empty(self::$siteTranslations)) {
                self::$siteTranslations = [];
            }
            self::$siteTranslations[$sList]                    = [];
            self::$siteTranslations[$sList][self::$languageId] = [];

            switch ($sList) {
                case 'all':
                    $aSiteTranslations = SiteTranslationManager::getTranslationsByFilter(['languageId' => self::$languageId, 'showEditable' => 1]);
                    break;
                case 'js':
                    $aSiteTranslations = SiteTranslationManager::getTranslationsByFilter(['languageId' => self::$languageId, 'prefix' => 'js_']);
                    break;
                default:
                    $aSiteTranslations = [];
                    break;
            }

            foreach ($aSiteTranslations AS $oSiteTranslation) {
                self::$siteTranslations[$sList][self::$languageId][$oSiteTranslation->label] = $oSiteTranslation->text;
            }
            $_SESSION['siteTranslations'] = self::$siteTranslations;
        }
    }

    /**
     * used for javascript translations
     *
     * @param string $sList
     */
    public static function getTranslations($sList)
    {
        if (empty(self::$siteTranslations[$sList][self::$languageId])) {
            self::setSiteTranslations($sList);
        }

        return self::$siteTranslations[$sList][self::$languageId];
    }

    /**
     * reset site translations to get new ones from database
     */
    public static function reset()
    {
        self::$languageId       = null;
        self::$siteTranslations = null;
        unset($_SESSION['languageId']);
        unset($_SESSION['siteTranslations']);
        self::$tmpLanguageIdSet = false;
    }

    /**
     * set temp languageId
     *
     * @param type $iLanguageId
     */
    public static function setTmpLanguageId($iLanguageId)
    {
        self::$languageId       = $iLanguageId;
        self::$tmpLanguageIdSet = true;
    }

    /**
     * reset temp languageId
     */
    public static function resetTmpLanguageId()
    {
        self::$languageId       = null;
        self::$tmpLanguageIdSet = false;
    }

}

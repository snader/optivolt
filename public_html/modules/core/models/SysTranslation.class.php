<?php

class sysTranslation
{

    /**
     * Get the translation for a text
     *
     * @param string $sTextId
     *
     * @return string
     */
    public static function get($sTextId)
    {
        if (!isset($_SESSION['sysLang'])) {
            self::loadSysTranslation();
        }

        if (!empty($_SESSION[$_SESSION['sysLang']])) {
            if (!empty($_SESSION[$_SESSION['sysLang']][$sTextId])) {
                return $_SESSION[$_SESSION['sysLang']][$sTextId];
            }
        }

        return '[' . $sTextId . ']';
    }

    public static function loadSysTranslation()
    {
        # Load all the labels and texts used in the admin panel into the session
        $_SESSION['sysLang'] = 'systemTranslation_' . Settings::get('systemLanguage');
        if (!isset($_SESSION[$_SESSION['sysLang']])) {
            $_SESSION[$_SESSION['sysLang']] = SystemTranslationManager::getLanguageTexts(Settings::get('systemLanguage'));
        }
    }

}

?>


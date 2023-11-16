<?php

class Settings
{

    private static $aSettings = null;

    /**
     * get setting, get all if not yet done
     *
     * @param string $sName
     *
     * @return string|null
     */
    public static function get($sName, $bForceQuery = false)
    {
        if (self::$aSettings === null || $bForceQuery) {
            $aSettings = [];
            foreach (SettingManager::getAllSettings() AS $oSetting) {
                $aSettings[$oSetting->name] = $oSetting->value;
            }
            self::$aSettings = $aSettings;
        }

        if (array_key_exists($sName, self::$aSettings)) {
            return self::$aSettings[$sName];
        }

        $oSetting = new Setting(['name' => $sName]);
        SettingManager::saveSetting($oSetting);

        return null;
    }

    /**
     * @param string $sName
     * @param string $sDefault
     * @param bool   $bForceQuery
     *
     * @return string|null
     */
    public static function getDefault($sName, $sDefault, $bForceQuery = false)
    {
        return static::get($sName, $bForceQuery) ?: $sDefault;
    }

    /**
     * @param string $sName
     * @param bool   $bForceQuery
     *
     * @return bool
     */
    public static function exists($sName, $bForceQuery = false)
    {
        if (self::$aSettings === null || $bForceQuery) {
            $aSettings = [];
            foreach (SettingManager::getAllSettings() AS $oSetting) {
                $aSettings[$oSetting->name] = $oSetting->value;
            }
            self::$aSettings = $aSettings;
        }

        if (array_key_exists($sName, self::$aSettings)) {
            return true;
        } else {
            return false;
        }
    }

}

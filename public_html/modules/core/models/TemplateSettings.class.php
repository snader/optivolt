<?php

class TemplateSettings
{

    private static $aSettings = null;

    /**
     * get template setting, get all if not yet done
     *
     * @param string $sModule     name of the module to get settings file from
     * @param string $sSettings   optional path to setting (e.g. /images/sizes)
     * @param string $bForceQuery will get settings from file even if in prop already
     *
     * @return mixed
     */
    public static function get($sModule = 'core', $sSettings = null, $bForceQuery = false)
    {
        if (self::$aSettings === null || !array_key_exists($sModule, self::$aSettings) || $bForceQuery) {
            $aSettings = self::getTemplateSettings($sModule);
            if ($aSettings !== null) {
                self::$aSettings[$sModule] = $aSettings;
            } else {
//                _d('unknown module name template settings: `' . $sModule . '`');
            }
        }

        if (self::$aSettings !== null && array_key_exists($sModule, self::$aSettings)) {
            if (!empty($sSettings)) {
                $aSettings = self::$aSettings[$sModule];
                foreach (explode('/', $sSettings) AS $sKey) {
                    if (array_key_exists($sKey, $aSettings)) {
                        $aSettings = $aSettings[$sKey];
                    } else {
//                        _d('unknown template settings: `' . $sSettings . '`');
                    }
                }

                return $aSettings;
            } elseif (empty($sSettings)) {
                return self::$aSettings[$sModule];
            }
        }
//        _d('unknown template settings: `' . $sSettings . '`');
    }

    /**
     * check if specific setting exists
     *
     * @param string  $sModule
     * @param string  $sSettings
     * @param boolean $bForceQuery
     *
     * @return boolean
     */
    public static function exists($sModule = 'core', $sSettings = null, $bForceQuery = false)
    {
        if (self::$aSettings === null || !array_key_exists($sModule, self::$aSettings) || $bForceQuery) {
            $aSettings = self::getTemplateSettings($sModule);
            if ($aSettings !== null) {
                self::$aSettings[$sModule] = $aSettings;
            } else {
                return false;
            }
        }

        if (self::$aSettings !== null && array_key_exists($sModule, self::$aSettings)) {
            if (!empty($sSettings)) {
                $aSettings = self::$aSettings[$sModule];
                foreach (explode('/', $sSettings) AS $sKey) {
                    if (array_key_exists($sKey, $aSettings)) {
                        $aSettings = $aSettings[$sKey];
                    } else {
                        return false;
                    }
                }

                return true;
            } elseif (empty($sSettings)) {
                return true;
            }
        }

        return false;
    }

    /**
     * get template settings
     *
     * @param string $sModule
     * @param string $sSettings optional to get specific settings only
     *
     * @return string
     */
    private static function getTemplateSettings($sModule = 'core')
    {
        $sFileName = SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/' . $sModule . '/settings.json';

        if (file_exists($sFileName)) {
            $sFileContents = @file_get_contents($sFileName);
            if ($sFileContents !== false) {
                $aSettings = json_decode($sFileContents, true);
                if ($aSettings !== false) {
                    return $aSettings;
                }
            }

            return [];
        }

        return null;
    }

}

?>
<?php

class ThemeHelper
{
    //Modules that are needed in every theme
    public static $baseModules = [
        'core',
        'autocomplete',
        'browserwarning',
        'fileManager',
        'formBackups',
        'imageManager',
        'linkManager',
        'redirect',
        'sitemaps',
        'pages',
        'themes',
        'videoLinkManager',
        'webservices',
    ];

    //Modules that are supported in every template, but not essential
    public static $supportedModules = [
        'brandboxItems',
        'cookieConsent',
        'contact',
        'conversions',
        'ipLocationWebservice',
        'paymentCXPay',
        'paymentMollie',
        'postalCodeWebservice',
        'redirect',
        'sitemaps',
        'USPs',
    ];

    /**
     * Remove all module folders, except the ones you exclude
     *
     * @param array $aKeepModules
     */
    public static function removeModules($aKeepModules = [])
    {
        $oScanDir = scandir(SYSTEM_MODULES_FOLDER);
        foreach ($oScanDir as $sModuleName) {
            // not a file name, continue
            if ($sModuleName == '.' || $sModuleName == '..' || in_array($sModuleName, static::$baseModules)) {
                continue;
            }

            if (!key_exists($sModuleName, $aKeepModules)) {
                static::removeFolderRecursive(DOCUMENT_ROOT . SITE_MODULES_FOLDER . '/' . $sModuleName);

                //Delete folder from themes
                if (file_exists(SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/' . $sModuleName)) {
                    static::removeFolderRecursive(SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/' . $sModuleName);
                }

            }
        }
    }

    /**
     * Remove all theme folders, except current one
     *
     * @param array $aKeepModules
     */
    public static function removeThemes()
    {
        $oScanDir = scandir(SYSTEM_THEMES_FOLDER);
        foreach ($oScanDir as $sTheme) {
            // not a file name, continue
            if ($sTheme == '.' || $sTheme == '..' || $sTheme == SITE_TEMPLATE) {
                continue;
            }

            //Delete themes folder
            if (file_exists(SYSTEM_THEMES_FOLDER . '/' . $sTheme)) {
                static::removeFolderRecursive(SYSTEM_THEMES_FOLDER . '/' . $sTheme);
            }
        }
    }

    /**
     * Remove a directory and its contents
     *
     * @param $sDirectory
     */
    public static function removeFolderRecursive($sDirectory)
    {
        $oIterator     = new RecursiveDirectoryIterator($sDirectory, RecursiveDirectoryIterator::SKIP_DOTS);
        $oFileIterator = new RecursiveIteratorIterator(
            $oIterator,
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($oFileIterator as $oFile) {
            if ($oFile->isDir()) {
                rmdir($oFile->getRealPath());
            } else {
                unlink($oFile->getRealPath());
            }
        }
        rmdir($sDirectory);
    }
}
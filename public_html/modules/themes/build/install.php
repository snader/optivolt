<?php

//Set supported modules
$aSupportedModules = ThemeHelper::$supportedModules;

//Check if template exists, and include needed install
if (in_array(SITE_TEMPLATE, SITE_SUPPORTED_TEMPLATES)) {
    include_once DOCUMENT_ROOT . SITE_MODULES_FOLDER . '/themes/build/install_' . SITE_TEMPLATE . '.php';

//Scan the modules folder
    $aDirs = scandir(DOCUMENT_ROOT . SITE_MODULES_FOLDER);

//Loop through modules folder
    foreach ($aDirs as $sDir) {
        //Check if module folder has a /site folder
        if (file_exists($sSiteDir = DOCUMENT_ROOT . SITE_MODULES_FOLDER . '/' . $sDir . '/site')) {
            foreach (scandir($sSiteDir) as $sTemplate) {
                //Check if folder is a theme folder, while not being the current theme
                if (in_array($sTemplate, SITE_SUPPORTED_TEMPLATES) && $sTemplate !== SITE_TEMPLATE) {
                    $aLogs[$sModuleName]['errors'][] = 'Template folder ' . $sTemplate . ' in ' . $sDir . ' needs to be removed';
                    //On install, removed unneeded template dirs
                    if ($bInstall) {
                        ThemeHelper::removeFolderRecursive($sSiteDir . '/' . $sTemplate);
                    }
                }
            }
        }
    }
} else {
    $aLogs[$sModuleName]['errors'][] = 'Template ' . SITE_TEMPLATE . ' does not exist';
}

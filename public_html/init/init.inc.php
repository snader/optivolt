<?php

//timezone settings
date_default_timezone_set('Europe/Amsterdam');

// load local config settings
include_once 'loc.init.inc.php';

// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 3600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600*3);

// set locales to NL by default
setlocale(LC_TIME, 'nl_NL');

// set UTF-8 encoding with header
header('Content-Type:text/html; charset=UTF-8');

# show errors before setting it to false after custom error handler is set
ini_set('display_errors', true);

# show all errors by default before custom error handler is set
error_reporting(E_ALL & ~E_NOTICE);

// locale details
define('DEFAULT_LANGUAGE_ID', 39); // default language ID to install NL
define('DEFAULT_COUNTRY_ID', 166); // default country ID to install NL

// set modules and themes folders
define('SYSTEM_MODULES_FOLDER', DOCUMENT_ROOT . '/modules');
define('SYSTEM_THEMES_FOLDER', DOCUMENT_ROOT . '/themes');
define('SYSTEM_PLUGINS_FOLDER', DOCUMENT_ROOT . '/plugins');
define('SITE_THEMES_FOLDER', '/themes');
define('SITE_MODULES_FOLDER', '/modules');

// snippets folder for admin core
define('SYSTEM_ADMIN_CORE_FOLDER', SYSTEM_MODULES_FOLDER . '/core/admin');
define('SITE_ADMIN_CORE_FOLDER', SITE_MODULES_FOLDER . '/core/admin');

// set admin folder
define('ADMIN_FOLDER', '/dashboard');

// set include folder
define('INC_FOLDER', DOCUMENT_ROOT . '/inc');

// include the base functions
require_once(INC_FOLDER . '/base_functions.inc.php');

// controller routes
$aSiteControllerRoutes = Engine::getSiteControllerManifest();
// controller routes
$aAdminControllerRoutes = Engine::getAdminControllerManifest();
// installed modules
$aInstalledModules = Engine::getInstalledModulesManifest();

// if environment is not set, try to set automatically
if (!defined('ENVIRONMENT')) {
    $sGITHEAD = DOCUMENT_ROOT . '/../.git/HEAD';
    if (file_exists($sGITHEAD)) {
        $sFileContents = file_get_contents($sGITHEAD);
        if (preg_match('#ref: refs/heads/(.*)#', $sFileContents, $aMatches)) {
            define('ENVIRONMENT', $aMatches[1]);
        }
    } elseif (getenv('ENVIRONMENT')) {
        define('ENVIRONMENT', getenv('ENVIRONMENT'));
    } elseif (isset($argv['ENVIRONMENT'])) {
        define('ENVIRONMENT', $argv['ENVIRONMENT']);
    }
}

// error reporting
error_reporting(E_ALL & ~E_NOTICE);
set_error_handler('error_handler', E_ALL & ~E_NOTICE);
register_shutdown_function('shutdown_handler');

# hide errors after custom error handler is set
ini_set('display_errors', false);



// prefix for template version
define('SITE_CORE_TEMPLATE_FOLDER', SITE_MODULES_FOLDER . '/core/site/' . SITE_TEMPLATE);

// initialize the engine
Engine::init();

// start session inc SWF upload fix
Session::start(Request::postVar('PHPSESSID'));

// define 2step IP's
$sWhitelistedIp = '';
if (Settings::exists('2StepWhitelistIps')) {
    $sWhitelistedIp = Settings::get('2StepWhitelistIps');
}
define('TWO_STEP_VERIFICATION_WHITELIST_IPS', $sWhitelistedIp);

// define LandgoedVoorn IP's
$sLandgoedVoornWhitelistedIp = '';
if (Settings::exists('landgoedvoornWhitelistIps')) {
    $sLandgoedVoornWhitelistedIp = Settings::get('landgoedvoornWhitelistIps');
}
define('LANDGOEDVOORN_WHITELIST_IPS', $sLandgoedVoornWhitelistedIp);

define('LOG_FOLDER', DOCUMENT_ROOT . '/logs');

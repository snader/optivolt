<?php

/*
 * This file contains local settings, never add this file to you GIT repository
 */

// no environment?
if (!defined('ENVIRONMENT')) {
    die('NO ENVIRONMENT FOUND');
}

// Set the supported templates
define('SITE_SUPPORTED_TEMPLATES', ['default', 'legacy', 'strawberry', 'vesper']);

// prefix for template version
define('SITE_TEMPLATE', 'default');

/*
 * Database connection details
 */
define('DB_HOST', 'x');
define('DB_USER', 'x');
define('DB_PASS', 'x');
define('DB_DATABASE', 'x');

// configurable port numbers for HTTP and HTTPS
define('HTTP_PORT_NUMBER', 80);
define('HTTPS_PORT_NUMBER', 443);

// client details
define('CLIENT_NAME', 'x'); //@todo change name
define('CLIENT_URL', 'x'); //@todo change url
define('CLIENT_IP', 'x'); // client IP(s) komma seperated (x.x.x.x,x.x.x.x)
define('CLIENT_HTTP_URL', 'http://' . CLIENT_URL);
define('LIVE_HTTP_URL', 'x');
define('CLIENT_DEFAULT_EMAIL_TO', 'x');
define('DEFAULT_EMAIL_FROM', CLIENT_NAME . ' <x>'); //@todo change email
define('DEFAULT_EMAIL_REPLY_TO', CLIENT_NAME . ' <x>'); //@todo change email

define('LANDGOEDVOORN_OFFICE_NL', 'LANDGOEDVOORNNL');
define('LANDGOEDVOORN_OFFICE_CUR', 'LANDGOEDVOORNCUR');
define('LANDGOEDVOORN_OFFICE_LATAM', 'LANDGOEDVOORNLATAM');

define('DEVELOPMENT_LOCATION', LANDGOEDVOORN_OFFICE_NL);

define('APPLICATION_RELEASE_DATE', '11-04-2025');
// calculates the amount of days since the website went LIVE compared to today
$iDaysSinceLIVE = Date::strToDate(APPLICATION_RELEASE_DATE)
    ->daysDiff(new Date('today')); // @todo enter the date the website went LIVE (first date)

// To echo query's and other debug stuff, true. Otherwise false
define('DEBUG', (isDeveloper() ? 1 : 0)); // @todo set else to 0
define('DEBUG_QUERIES', 0);
define('DO_NOT_EMAIL_HTTP_ERRORS', $iDaysSinceLIVE >= 31); // optionally change the length of time, in days, of the http error emails
define('DEVELOPER_NAME', 'Arjan'); //name of the maker(s)

define('DEFAULT_ERROR_EMAIL', 'x'); //error@landgoedvoorn.nl @todo

// salt for access logs
define('ACCESS_LOGS_SALT', 'x'); // random string for salting access logs data

// CSS Cache settings (combined CSS files)
define('CSS_CACHE_TIME', 60 * 60); // time in seconds

// JS Cache settings (combined JS files)
define('JS_CACHE_TIME', 60 * 60); // time in seconds
<?php

/**
 * Basic functions for all needs
 *
 * @param String $sClassName name of class that should be autoloaded
 */

/**
 * get site link of view
 *
 * @param string $sView
 * @param string $sModule
 *
 * @return string
 */
function getSiteView($sView, $sModule = 'core')
{
    $sFileName = SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/' . $sModule . '/views/' . $sView . '.inc.php';

    if (file_exists($sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing site view file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get site link of snippet
 *
 * @param string $sSnippet
 * @param string $sModule
 *
 * @return string
 */
function getSiteSnippet($sSnippet, $sModule = 'core')
{
    $sFileName = SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/' . $sModule . '/snippets/' . $sSnippet . '.inc.php';
    if (file_exists($sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing site snippet file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get site link of component
 *
 * @param string $sComponent
 * @param string $sModule
 *
 * @return string
 */
function getSiteComponent($sComponent)
{
    $sFileName = SYSTEM_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/components/components/' . $sComponent . '.html';
    if (file_exists($sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing site component file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get site link of css
 *
 * @param string $sCss
 *
 * @return string
 */
function getSiteCss($sCss)
{
    $sFileName = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/css/' . $sCss . '.css';
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    } else {
    }
    Debug::logError('0', 'Missing site css file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get site link of for a path with template in it
 *
 * @param string $sPath
 * @param string $sModule
 *
 * @return string
 */
function getSiteTemplatePath($sPath, $sModule = 'core')
{
    $sFileName = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/templates/' . $sModule . '/' . $sPath;
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing site template path file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get vendor path
 *
 * @param string $sPath
 *
 * @return string
 */
function getVendorPath($sPath)
{
    $sFileName = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/vendor/' . $sPath;
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing site vendor path file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get site link of for a path
 *
 * @param string $sPath
 *
 * @return string
 */
function getSitePath($sPath)
{
    $sFileName = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/' . $sPath;
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing site path file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get site link of image
 *
 * @param string $sImage
 * @param string $sModuleFolder
 *
 * @return string
 */
function getSiteImage($sImage, $sModuleFolder = null)
{
    if ($sModuleFolder) {
        $sFileName = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/images/' . $sModuleFolder . '/' . $sImage;
    } else {
        $sFileName = SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/images/' . $sImage;
    }
    /*
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        if (in_array(pathinfo($sFileName, PATHINFO_EXTENSION), ImageFile::$allowedWebPExtensions) && !file_exists(DOCUMENT_ROOT . $sFileName . '.webp')) {
            createWebPImage($sFileName);
        }

        return $sFileName;
    } */

    return $sFileName;

    Debug::logError('0', 'Missing site image file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);

    return '';
}

/**
 * get admin link of view
 *
 * @param string $sView
 * @param string $sModule
 *
 * @return string
 */
function getAdminView($sView, $sModule = 'core')
{
    $sFileName = SYSTEM_MODULES_FOLDER . '/' . $sModule . '/admin/views/' . $sView . '.inc.php';
    if (file_exists($sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing admin view file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get admin link of snippet
 *
 * @param string $sSnippet
 * @param string $sModule
 *
 * @return string
 */
function getAdminSnippet($sSnippet, $sModule = 'core')
{
    $sFileName = SYSTEM_MODULES_FOLDER . '/' . $sModule . '/admin/snippets/' . $sSnippet . '.inc.php';
    if (file_exists($sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing admin snippet file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get admin link of css
 *
 * @param string $sCss
 * @param string $sModule
 *
 * @return string
 */
function getAdminCss($sCss, $sModule = 'core')
{
    $sFileName = SITE_MODULES_FOLDER . '/' . $sModule . '/admin/css/' . $sCss . '.css';
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing admin css file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get admin link of js
 *
 * @param string $sJs
 * @param string $sModule
 *
 * @return string
 */
function getAdminJs($sJs, $sModule = 'core')
{
    $sFileName = SITE_MODULES_FOLDER . '/' . $sModule . '/admin/js/' . $sJs . '.js';
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing admin js file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get admin link of for a path
 *
 * @param string $sPath
 * @param string $sModule
 *
 * @return string
 */
function getAdminPath($sPath, $sModule = 'core')
{
    $sFileName = SITE_MODULES_FOLDER . '/' . $sModule . '/admin/' . $sPath;
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing admin path file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * get admin link of image
 *
 * @param string $sImage
 * @param string $sModule
 *
 * @return string
 */
function getAdminImage($sImage, $sModule = 'core')
{
    $sFileName = SITE_MODULES_FOLDER . '/' . $sModule . '/admin/images/' . $sImage;
    if (file_exists(DOCUMENT_ROOT . $sFileName)) {
        return $sFileName;
    }
    Debug::logError('0', 'Missing admin image file: `' . $sFileName . '`', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
}

/**
 * check if module exists
 *
 * @param string $sModule
 *
 * @return boolean
 */
function moduleExists($sModule)
{
    global $aInstalledModules;

    return array_key_exists($sModule, $aInstalledModules);
}

/**
 * Do the same as mysql_real_escape_string
 *
 * @param String  $mValue            (waarde om te escapen)
 * @param Boolean $bEMptyString2NULL if value is empty string, return 'NULL'
 *
 * @return String escaped string
 */
function db_str($mValue, $bEmptyString2NULL = true)
{

    if ($mValue === null) {
        return 'NULL';
    }
    if ($mValue === '' && $bEmptyString2NULL) {
        return 'NULL';
    }

    return "'" . str_replace([
            '\\',
            "\0",
            "\n",
            "\r",
            "'",
            '"',
            "\x1a",
            "\x00",
        ], [
            '\\\\',
            '\\0',
            '\\n',
            '\\r',
            "\\'",
            '\\"',
            '\\Z',
            '',
        ], trim($mValue)) . "'";
}

/**
 * Try to make a real integer of this value, else return null
 *
 * @param Int $iValue (value to parse)
 *
 * @return Int or null
 */
function db_int($iValue)
{
    
    if (is_numeric($iValue)) {
        return (int) $iValue;
    }
    if ($iValue === null || $iValue === "") {
        return "NULL";
    }
    

    return null;
}

function db_bool($bValue)
{
    return (int) (bool) $bValue;
}

/**
 * Make value a real float/double
 *
 * @param Float $fValue (value to parse)
 *
 * @return Int or null
 */
function db_deci($fValue)
{
    if ($fValue === null || $fValue === "") {
        return "NULL";
    } else {
        # remove ,-
        $fValue = preg_replace("#([0-9])(,-)$#", "$1.00", $fValue);

        # remove .-
        $fValue = preg_replace("#([0-9])(\.-)$#", "$1.00", $fValue);

        # remove all dashes at the end
        $fValue = preg_replace("#-$#", "", $fValue);

        # komma to decimal sign
        $fValue = preg_replace("#,#", ".", $fValue);

        # If it is a float or a numeric value, make decimal
        if (is_float($fValue) || is_numeric($fValue)) {
            return number_format((float) $fValue, 2, '.', '');
        } else {
            return null;
        }
    }
}

/**
 * Try to return a date in the right format
 *
 * @param String  $sDate           (value to parse)
 * @param Boolean $bOnlyReturnDate return a date without the time
 *
 * @return String or null
 */
function db_date($sDate, $bOnlyReturnDate = false)
{
    if ($sDate === null || $sDate === "") {
        return "NULL";
    }
    # Covert format from d-m-Y to Y-m-d
    if (preg_match("#^\d{1,2}[\/\-]{1}\d{1,2}[\/\-]{1}\d{4}#", $sDate)) {
        $sDate = preg_replace("#^(\d{1,2})[\/\-]{1}(\d{1,2})[\/\-]{1}(\d{4})#", "$3-$2-$1", $sDate);
    }
    # Format is Y-m-d so this is ok
    if (preg_match("#^\d{4}[\/\-]{1}\d{1,2}[\/\-]{1}\d{1,2}#", $sDate)) {
        if ($bOnlyReturnDate) {
            $sDate = preg_replace("#^(\d{4}[\/\-]{1}\d{1,2}[\/\-]{1}\d{1,2})(.*)$#", "$1", $sDate);
        }

        return "'" . trim($sDate) . "'";
    } else {
        return null;
    }
}

/**
 * Try to return a time in the right format
 *
 * @param string $sTime
 *
 * @return string
 * @return String or null
 */
function db_time($sTime)
{
    if ($sTime === null || $sTime === "") {
        return "NULL";
    }

    /* format is ok */
    if (preg_match("#^\d{1,2}:\d{2}(:\d{2})?$#", $sTime)) {
        return "'" . trim($sTime) . "'";
    } else {
        return null;
    }
}

/**
 *
 */
function days_from_now($sDate) {
    $datetime2 = date('Y-m-d', time());
    $datetime1 = $sDate;

    return date_diff(
        date_create($datetime2),
        date_create($datetime1)
    )->format('%r%a');

}


/**
 * Dump variables to the screen or return result
 *
 * @param mixed   $mValue        value to dump
 * @param boolean $bShowHTML     do uses htmlentities
 * @param boolean $sReturnResult return value rather than printing it
 *
 * @returns string/void
 */
function _d($mValue, $bShowHTML = true, $sReturnResult = false)
{
    $sPrefix = '';
    $sSuffix = '---';
    if (Request::isNotCli()) {
        $sPrefix = "<pre>";
        $sSuffix = "</pre>";
    }
    $sDump = gettype($mValue) . ": ";
    if (Request::isNotCli() && $bShowHTML === true) {
        $sDump .= htmlentities(print_r(_v($mValue), 1));
    } else {
        $sDump .= print_r(_v($mValue), 1);
    }

    if ($sReturnResult) {
        return $sDump;
    } else {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
        $trace = array_pop($trace);
        printf(
            "%1\$s\n%2\$s\n%3\$s\n%4\$s\n",
            $sPrefix,
            sprintf('%1$s (%2$s)', $trace['file'], $trace['line']),
            $sDump,
            $sSuffix
        );
    }
}

/**
 * Get a proper return value for values
 *
 * @param mixed $mValue
 *
 * @return int
 */
function _v($mValue)
{
    switch (true) {
        case is_bool($mValue):
            return (int) $mValue;
        default:
            return $mValue;
    }
}

/**
 * Get a value from the $_GET Array
 *
 * @param String $sKey
 * @param Mixed  $mDefault
 *
 * @return Mixed
 *
 * use Request::getVar
 * @note       to get the controller use Request::getControllerSegment
 * @note       to get the action use Request::getActionSegment
 */
function http_get($sKey, $mDefault = null)
{
    Request::init();
    switch ($sKey) {
        case 'controller':
            return Request::getControllerSegment();
        case 'param1':
            if (!Request::isActionIndex()) {
                return Request::getActionSegment();
            }

            return Request::param('ID');

        case 'param2':
            if (!Request::isActionIndex()) {
                return Request::param('ID');
            }

            return Request::param('OtherID');
        case 'param3':
            if (!Request::isActionIndex()) {
                return Request::param('OtherID');
            }

            return Request::param('AnotherID');
        case 'param4':
            if (!Request::isActionIndex()) {
                return Request::param('AnotherID');
            }

            return Request::param('LastID');
        case 'param5':
            if (!Request::isActionIndex()) {
                return Request::param('LastID');
            }

            return null;
        default:
            return array_key_exists($sKey, $_GET) ? $_GET[$sKey] : $mDefault;
    }
}

/**
 * Get a value from the $_POST Array
 *
 * @param String $sKey
 * @param Mixed  $mDefault
 *
 * @return Mixed
 *
 * @deprecated use Request::postVar()
 */
function http_post($sKey, $mDefault = null)
{
    return !is_null(Request::postVar($sKey)) ? Request::postVar($sKey) : $mDefault;
}

/**
 * Get a value from the $_SESSION Array
 *
 * @param String $sKey
 * @param Mixed  $mDefault
 *
 * @return Mixed
 *
 * @deprecated use Session::get()
 */
function http_session($sKey, $mDefault = null)
{
    return Session::get($sKey) ?: $mDefault;
}

/**
 * Get a value from the $_COOKIE Array
 *
 * @param String $sKey
 * @param Mixed  $mDefault
 *
 * @return Mixed
 * @deprecated use Cookie::get()
 */
function http_cookie($sKey, $mDefault = null)
{
    return array_key_exists($sKey, $_COOKIE) ? $_COOKIE[$sKey] : $mDefault;
}

/**
 * redirect to a given location and stop script from executing
 *
 * @param         $sRedirectURL  URL to redirect to
 * @param Boolean $bUtm          (true to keep Google Analytics UTM parameters working)
 *
 */
function http_redirect($sRedirectURL, $bUtm = false, $b301 = false)
{



    Router::redirect($sRedirectURL, $bUtm, $b301);
}

/**
 * Make a given part of a URL a pretty (SEO friendly) look
 *
 * @param String $sUrlPart
 * @param String $bConvertToUTF8 convert $sUrlPart to UTF-8
 *
 * @return String (SEO friendly) pretty URL part
 */
function prettyUrlPart($sUrlPart, $bConvertToUTF8 = false)
{

    if ($bConvertToUTF8) {
        $sUrlPart = utf8_encode($sUrlPart);
    }

    $sUrlPart = htmlentities($sUrlPart, ENT_COMPAT, "UTF-8", false); //make HTMLentities to remove accents
    $sUrlPart = preg_replace('#&szlig;#i', 'ss', $sUrlPart); //replace `ß` for `ss` (next row is not enough, replaces `ß` for `sz`)
    $sUrlPart = preg_replace('#&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);#i', '\1', $sUrlPart); //remove accents
    $sUrlPart = html_entity_decode($sUrlPart, ENT_COMPAT, "UTF-8"); //decode to normal character again

    $sUrlPart = str_replace('\'', '', $sUrlPart); //remove all single quotes
    $sUrlPart = preg_replace('#[^a-z0-9-]+#i', '-', $sUrlPart); //replace all non alphanumeric characters and hyphens, with a hyphen
    $sUrlPart = preg_replace('#-+#', '-', $sUrlPart); //remove all double hyphens
    $sUrlPart = trim($sUrlPart, '-'); //remove hyphens at beginning and end of string

    $sUrlPart = strtolower($sUrlPart); //string to lower case characters

    return $sUrlPart;
}

/*
*/
function slugify($text, string $divider = '-')
{
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

/**
 * prepend a given string with http:// if necessary (optionally keeps HTTPS in tact)
 *
 * @param string  $sValue (www.google.nl or http://www.google.nl or https://www.google.nl)
 * @param boolean $bForceHttp
 *
 * @return string
 */
function addHttp($sValue, $bForceHttp = false)
{
    if (!preg_match("#^http(s)?:\/\/#", $sValue) && $sValue) {
        $sValue = "http://" . $sValue;
    }
    if ($bForceHttp) {
        return preg_replace('#^(https://)#i', 'http://', $sValue);
    }

    return $sValue;
}

/**
 * prepend a given string with https:// if necessary (optionally keeps HTTP in tact)
 *
 * @param string  $sValue (www.google.nl or http://www.google.nl or https://www.google.nl)
 * @param boolean $bForceHttps
 *
 * @return string
 */
function addHttps($sValue, $bForceHttps = false)
{
    if (!preg_match("#^http(s)?:\/\/#", $sValue) && $sValue) {
        $sValue = "https://" . $sValue;
    }
    if ($bForceHttps) {
        return preg_replace('#^(http://)#i', 'https://', $sValue);
    }

    return $sValue;
}

/**
 * show http error page
 *
 */

function showHttpError($iErrorNr)
{
    echo Router::httpError($iErrorNr, true);
    exit;
}

/** returns the url protocol of the website
 *
 */
function getUrlProtocol()
{
    return Request::getProtocol(true);
}

/**
 * returns the base url
 *
 * @param int $iLocaleId
 */
function getBaseUrl(ACMS\Locale $oLocale = null)
{
    $sProtocol = getUrlProtocol();

    if ($oLocale) {
        return $sProtocol . $oLocale->getURLFormat();
    }

    return $sProtocol . Locales::getLocale()
            ->getURLFormat();
}

/**
 * returns the current url
 *
 * @return string
 */
function getCurrentUrl()
{
    $bIsHTTPS = Request::isSecure();
    $sUrl = Request::getProtocol(true) . Server::get("SERVER_NAME");

    if (!Request::isForwardedSecure()) {
        if (Server::get("SERVER_PORT") && (!$bIsHTTPS && Server::get("SERVER_PORT") != HTTP_PORT_NUMBER) || ($bIsHTTPS && Server::get("SERVER_PORT") != HTTPS_PORT_NUMBER)) {
            $sUrl .= ':' . Server::get("SERVER_PORT");
        }
    }

    return $sUrl . Server::get("REQUEST_URI");
}

/**
 * return relative url path of the current page without extension optional with querystring
 *
 * @param Boolean $bWithQRS           also keep query string attached
 * @param Boolean $bWithExtension     keep extension
 * @param Boolean $bWithLocale        keep locale if included in url path
 * @param Boolean $bWithTrailingSlash keep trailing slash in url path
 * @param bool    $bForceAmp          force amp segment in URL
 *
 * @return string
 */
function getCurrentUrlPath($bWithQRS = false, $bWithExtension = false, $bWithLocale = false, $bWithTrailingSlash = false, $bForceAmp = false)
{
    $aCurrentUrlPath = explode('/', Server::get('REQUEST_URI'));
    if (!$bForceAmp) {
        array_shift($aCurrentUrlPath);
        if (!(($sScope = array_shift($aCurrentUrlPath)) && $sScope == Request::MODE_AMP)) {
            array_unshift($aCurrentUrlPath, $sScope);
        }
        array_unshift($aCurrentUrlPath, '');
    }

    return cleanUrlPath(implode('/', $aCurrentUrlPath), $bWithQRS, $bWithExtension, $bWithLocale);
}

/**
 * cleans url path from extension and or querystring and slash at the end
 *
 * @param string  $sUrlPath           relative path of the url after the domain name starting with slash
 * @param boolean $bWithQRS           keep querystring
 * @param boolean $bWithExtension     keep extension
 * @param boolean $bWithLocale        keep locale
 * @param boolean $bWithTrailingSlash keep trailing slash
 *
 * @return string
 */
function cleanUrlPath($sUrlPath, $bWithQRS = false, $bWithExtension = false, $bWithLocale = false, $bWithTrailingSlash = false)
{

    # cut urlPart in pieces
    preg_match("/(^\/[a-zA-Z]{2}_[a-zA-Z]{2}|^\/[a-zA-Z]{2}|)(?![a-zA-Z0-9\-])((?:\/|\/?$|^\/|)[^\?\&\.]*)([^\?\&]*)(.*)$/", $sUrlPath, $aMatches);

    $sUrlPath      = $aMatches[0];
    $sPrefix       = $aMatches[1];
    $sUrlPathClean = $aMatches[2];
    $sExtension    = $aMatches[3];
    $sQRYString    = $aMatches[4];

    # part needs to start with a slash
    if (!preg_match("#^\/#", $sUrlPathClean)) {
        $sUrlPathClean = "/" . $sUrlPathClean;
    }

    # remove slash at the end
    if (!$bWithTrailingSlash && preg_match("#\/$#", $sUrlPathClean)) {
        $sUrlPathClean = substr($sUrlPathClean, 0, -1); // remove slash at end
    }

    # add locale prefixes
    if ($bWithLocale) {
        $sUrlPathClean = $sPrefix . $sUrlPathClean;
    }

    # add extension
    if ($bWithExtension) {
        $sUrlPathClean .= $sExtension;
    }

    # add QRYString
    if ($bWithQRS) {
        $sUrlPathClean .= $sQRYString;
    }

    #if empty, add slash
    if (empty($sUrlPathClean)) {
        $sUrlPathClean = '/';
    }

    return $sUrlPathClean;
}

/**
 * remove query string
 *
 * @param string $sUrlPart
 *
 * @return string
 */
function removeQRYStringFromUrlPath($sUrlPath)
{
    if (preg_match("/([^\?\&]*)[\?\&]/", $sUrlPath, $aMatches)) {
        $sUrlPath = $aMatches[1];
    }

    return $sUrlPath;
}

/**
 * remove extension
 *
 * @param string $sUrlPath
 *
 * @return string
 */
function removeExtensionFormUrlPath($sUrlPath)
{
    if (preg_match("/([^\?\&\.]*)([^\?\&]*)(.*)/", $sUrlPath, $aMatches)) {
        $sUrlPath = $aMatches[1];
        $sUrlPath .= $aMatches[3];
    }

    return $sUrlPath;
}

/**
 * remove extension
 *
 * @param string $sUrlPath
 *
 * @return string
 */
function removeLocaleFormUrlPath($sUrlPath)
{
    if (preg_match("/(?:(^\/[a-zA-Z]{2}_[a-zA-Z]{2}|^\/[a-zA-Z]{2})(\/.*)$|(^\/[a-zA-Z]{2}_[a-zA-Z]{2}|^\/[a-zA-Z]{2})\/?$)/", $sUrlPath, $aMatches)) {
        $sUrlPath = $aMatches[2];
    }

    // no path left, add slash for homepage
    if (empty($sUrlPath)) {
        $sUrlPath = '/';
    }

    return empty($sUrlPath) ? $sUrlPath : '/';
}

/**
 * prepare password for database with sha 512
 *
 * @param string $sPass (passwordt to prepare)
 *
 * @return string hashed and max 100 chars
 */
function hashPasswordForDb($sPass)
{
    return substr(hash('sha512', $sPass), 0, 100);
}

/**
 * first x words from a string
 *
 * @param string $sText
 * @param int    $iMaxWords  max amount of words
 * @param string $sKeep_tags keep given tags default = '<a><b><i><u><strong><italic><underline>'
 *
 * @return string
 */
function firstXWords($sText, $iMaxWords, $sKeep_tags = '<a><b><i><u><strong><italic><underline>', $sMoreSign = '&hellip;')
{

# strip tags
    $sText = strip_tags($sText, $sKeep_tags);

# match all tags, whitespace and words seperately (special thanks to `The Djuke`)
    preg_match_all('#<[^\>]+>|[^\<\>\s]+|\s+#', $sText, $aTextElements);
    $aTextElements = $aTextElements[0];

# count words and on limit set splice index
    $iWords       = 0;
    $iSpliceIndex = count($aTextElements);
    foreach ($aTextElements as $iIndex => $sElement) {
        if (preg_match('#^[^\s\<]#', $sElement)) {
            $iWords++;
        }
        # words limit is reached, set splice index
        if ($iWords > $iMaxWords) {
            $iSpliceIndex = $iIndex;
            break;
        }
    }

# splice array on splice index
    array_splice($aTextElements, $iSpliceIndex);

# close all unclosed HTML tags
    return closeHTMLTags(implode("", $aTextElements)) . ($iWords > $iMaxWords ? $sMoreSign : '');
}

/**
 * First x characters from a string (UTF-8 compatible!)
 *
 * @param string $sText
 * @param int    $iChars
 */
function firstXCharacters($sText, $iChars, $sMoreSign = '&hellip;')
{

    $sText = preg_replace('#(\<br\ ?\/?>)#i', ' ', $sText); // replace html and line breaks with a space

    /* verwijder eerst de ongewenste tags */
    $sText = strip_tags($sText);

    if (mb_strlen($sText) <= $iChars) {
        return $sText;
    } else {
        return mb_strcut($sText, 0, $iChars) . $sMoreSign;
    }
}

/**
 * close all unclosed html tags
 *
 * @param string $sHtml
 *
 * @return string
 */
function closeHTMLTags($sHtml)
{

# match all start tags
    preg_match_all("#<((?!br|li)[a-z]+)(?: (?:[a-z0-9-:_\.]+ ?\= ?((?:\")[^\"]+\"|\'[^\']+\')|[^/]+))*(?!/)>#iU", $sHtml, $result);
    $aOpenedTags = $result[1];

# match all close tags
    preg_match_all("#</([a-z]+)>#iU", $sHtml, $result);
    $aClosedTags   = $result[1];
    $iLengthOpened = count($aOpenedTags);

# check if all tags are closed
    if (count($aClosedTags) == $iLengthOpened) {
        return $sHtml;
    }

# reverse opened tags for closing
    $aOpenedTags = array_reverse($aOpenedTags);

# close tags
    for ($iC = 0; $iC < $iLengthOpened; $iC++) {
        if (!in_array($aOpenedTags[$iC], $aClosedTags)) {
            $sHtml .= "</" . $aOpenedTags[$iC] . ">";
        } else {
            unset($aClosedTags[array_search($aOpenedTags[$iC], $aClosedTags)]);
        }
    }

    return $sHtml;
}

/**
 * Generate HTML code for displaying pagination
 *
 * @param int    $iPageCount     total amount of pages
 * @param int    $iCurrPage      curent page number
 * @param string $sURLFormat     the url to place in de <a href="$sURLFormat"></a>, page number must be placed by %s
 * @param string $iPrevPage      string to represent the previous page link/button
 * @param string $iNextPage      string to represent the next page link/button
 * @param int    $iPageOffsetBef amount of pages shown before the current page
 * @param int    $iPageOffsetAft amount of pages shown after the current page
 * @param int    $sDotSep        seperator between pageLinks
 *
 * @return string
 */
function generatePaginationHTML($iPageCount, $iCurrPage, $sURLFormat = '?page=%s', $iPrevPage = '', $iNextPage = '', $iPageOffsetBef = 1, $iPageOffsetAft = 1, $sDotSep = '&hellip;')
{
    $sHtml = '';

    if ($iPageCount > 1) {

        /* if difference between (currPage-offset-1) equals 2, make offset one larger */
        if (($iCurrPage - $iPageOffsetBef - 1) == 2) {
            $iPageOffsetBef += 1;
        }

        /* if difference between (currPage+offset-lastPage) equals 2, make offset one larger */
        if (($iPageCount - ($iCurrPage + $iPageOffsetAft)) == 2) {
            $iPageOffsetAft += 1;
        }

        $sHtml .= '<nav class="pagination is-centered" role="navigation" aria-label="pagination">';

        $iPrevPage = $iPrevPage === '' ? SiteTranslations::get('site_previous') : '&lt;';
        $iNextPage = $iNextPage === '' ? SiteTranslations::get('site_next') : '&gt;';

        if ($iCurrPage > 1) {
            $sHtml .= '<a class="pagination-previous" href="' . sprintf($sURLFormat, ($iCurrPage - 1)) . '">' . $iPrevPage . '</a>';
        }
        if ($iCurrPage < $iPageCount) {
            $sHtml .= '<a class="pagination-next" href="' . sprintf($sURLFormat, ($iCurrPage + 1)) . '">' . $iNextPage . '</a>';
        }

        $sHtml .= '<ul class="pagination-list">';

        if (($iCurrPage - 1) > $iPageOffsetBef) {
            $sHtml .= '<li><a class="pagination-link" href="' . sprintf($sURLFormat, "1") . '">1</a></li>';
            if (($iCurrPage - $iPageOffsetBef) > 2) {
                $sHtml .= '<li><span class="pagination-ellipsis">' . $sDotSep . '</span></li>';
            }
        }

        /* for loop to make links around current page */
        $iOffsetCounter = (-$iPageOffsetBef); //extra counter for offset counting

        for ($i = ($iCurrPage - $iPageOffsetBef); $i <= ($iCurrPage + $iPageOffsetAft); $i++) {
            if ($i >= 1 && $i <= ($iPageCount)) {
                if ($i != $iCurrPage) {
                    $sHtml .= '<li><a class="pagination-link offset' . $iOffsetCounter . '" href="' . sprintf($sURLFormat, $i) . '">' . $i . '</a></li>';
                } else {
                    $sHtml .= '<li><a class="pagination-link is-current" href="' . sprintf($sURLFormat, $i) . '">' . $i . '</a></li>';
                }
            }
            $iOffsetCounter++;
        }

        if (($iCurrPage + $iPageOffsetAft) < $iPageCount) {
            if (($iCurrPage + $iPageOffsetAft) < ($iPageCount - 1)) {
                $sHtml .= '<li><span class="pagination-ellipsis">' . $sDotSep . '</span></li>';
            }
            $sHtml .= '<li><a class="pagination-link" href="' . sprintf($sURLFormat, $iPageCount) . '">' . $iPageCount . '</a></li>';
        }

        $sHtml .= '</ul>';
        $sHtml .= '</nav>';
    }

    return $sHtml;
}

/**
 * Generate HTML code for displaying pagination
 *
 * @param int    $iPageCount     total amount of pages
 * @param int    $iCurrPage      curent page number
 * @param string $sURLFormat     the url to place in de <a href="$sURLFormat"></a>, page number must be placed by %s
 * @param string $iPrevPage      string to represent the previous page link/button
 * @param string $iNextPage      string to represent the next page link/button
 * @param int    $iPageOffsetBef amount of pages shown before the current page
 * @param int    $iPageOffsetAft amount of pages shown after the current page
 * @param int    $sDotSep        seperator between pageLinks
 *
 * @return string
 */
function generatePaginationHTMLAdminLTE($iPageCount, $iCurrPage, $sURLFormat = '?page=%s', $iPrevPage = '', $iNextPage = '', $iPageOffsetBef = 1, $iPageOffsetAft = 1, $sDotSep = '&hellip;')
{
    $sHtml = '';

    if ($iPageCount > 1) {

        /* if difference between (currPage-offset-1) equals 2, make offset one larger */
        if (($iCurrPage - $iPageOffsetBef - 1) == 2) {
            $iPageOffsetBef += 1;
        }

        /* if difference between (currPage+offset-lastPage) equals 2, make offset one larger */
        if (($iPageCount - ($iCurrPage + $iPageOffsetAft)) == 2) {
            $iPageOffsetAft += 1;
        }

        $sHtml .= '<ul class="pagination pagination-sm m-0 float-right">';

        $iPrevPage = $iPrevPage === '' ? '&lt;' : '&lt;';
        $iNextPage = $iNextPage === '' ? '&gt;' : '&gt;';

        if ($iCurrPage > 1) {
            $sHtml .= '<a class="page-link"href="' . sprintf($sURLFormat, ($iCurrPage - 1)) . '">' . $iPrevPage . '</a>';
        }

        if (($iCurrPage - 1) > $iPageOffsetBef) {
            $sHtml .= '<li class="page-item"><a class="page-link"href="' . sprintf($sURLFormat, "1") . '">1</a></li>';
        }

        /* for loop to make links around current page */
        $iOffsetCounter = (-$iPageOffsetBef); //extra counter for offset counting

        for ($i = ($iCurrPage - $iPageOffsetBef); $i <= ($iCurrPage + $iPageOffsetAft); $i++) {
            if ($i >= 1 && $i <= ($iPageCount)) {
                if ($i != $iCurrPage) {
                    $sHtml .= '<li class="page-item"><a class="page-link offset' . $iOffsetCounter . '" href="' . sprintf($sURLFormat, $i) . '">' . $i . '</a></li>';
                } else {
                    $sHtml .= '<li class="page-item active"><a class="page-link" href="' . sprintf($sURLFormat, $i) . '">' . $i . '</a></li>';
                }
            }
            $iOffsetCounter++;
        }

        if (($iCurrPage + $iPageOffsetAft) < $iPageCount) {
            $sHtml .= '<li class="page-item"><a class="page-link" href="' . sprintf($sURLFormat, $iPageCount) . '">' . $iPageCount . '</a></li>';
        }

        if ($iCurrPage < $iPageCount) {
            $sHtml .= '<a class="page-link" href="' . sprintf($sURLFormat, ($iCurrPage + 1)) . '">' . $iNextPage . '</a>';
        }

        $sHtml .= '</ul>';
        
    }

    return $sHtml;
}




/**
 * check if string is a valid password
 * minimal 8 characters, 1 uppercase, 1 digit, 1 special character
 *
 * @return boolean
 */
function isValidPassword($sPass)
{
    return preg_match('#^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\W])(?=.*[\d]).*$#', $sPass);
}

/**
 * remove a directory and all in it
 *
 * @param string $sPath
 */
function emptyAndRemoveDir($sPath)
{
    if (empty($sPath)) {
        return false;
    } else {
        $sPath = preg_replace('#(/)$#i', '', $sPath); // remove slash if has one
        if (!$rDir = @opendir($sPath)) {
            return false;
        }
        while (false !== ($sFileDir = @readdir($rDir))) {
            # not a file name, continue
            if ($sFileDir == '.' || $sFileDir == '..') {
                continue;
            }
            # try to unlink, otherwise, try to empty
            if (!@unlink($sPath . '/' . $sFileDir)) {
                emptyAndRemoveDir($sPath . '/' . $sFileDir, true);
            }
        }

        # close dir
        @closedir($rDir);

        # remove dir
        @rmdir($sPath);
    }
}

/**
 * get link target based on the server host (target='')
 *
 * @param string $sLink
 *
 * @return string ('_blank', '_self')
 */
function getLinkTarget($sLink)
{
    if (isset($_SERVER['HTTP_HOST'])) {
        if (preg_match('#' . $_SERVER['HTTP_HOST'] . '#', $sLink) || preg_match('#^\/(?!\/)#', $sLink)) {
            return '_self';
        }
    }

    return '_blank';
}

/**
 * check if IP addres is aside
 *
 * @deprecated use isDeveloper() instead
 */
function isAside()
{
    return isDeveloper();
}

/**
 * check if user comes from a development IP location
 *
 * @return bool
 */
function isDeveloper()
{

    return isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '83.128.190.103' || ($_SERVER['SERVER_ADDR'] == '127.0.0.1' && $_SERVER['REMOTE_ADDR'] == '127.0.0.1') || ($_SERVER['REMOTE_ADDR'] == '::1' && $_SERVER['SERVER_ADDR'] == '::1') || $_SERVER['REMOTE_ADDR'] == '83.128.190.103');
}

/**
 * @return bool
 */
function isLocal(){
    return Server::get('REMOTE_ADDR') == Server::get('SERVER_ADDR');
}

/**
 * check if IP addres is client IP
 */
function isClient()
{
    return isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], explode(',', CLIENT_IP));
}

/**
 * handle PHP errors
 *
 * @param int    $iErrorno
 * @param string $sError
 * @param string $sFile
 * @param int    $iLine
 */
function error_handler($iErrorLevel, $sError, $sFile, $iLine)
{
    if (!error_reporting()) {
        return false;
    }
    $sErrorMsg = '<br />';
    switch ($iErrorLevel) {
        case E_WARNING:
        case E_USER_WARNING:
            $sErrorMsg .= '<b>Warning: </b>';
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $sErrorMsg .= '<b>Notice: </b>';
            break;
        default:
            $sErrorMsg .= '<b>Error: </b>';
            break;
    }
    $sErrorMsg .= $sError . ' in <b>' . $sFile . '</b>' . ' on line ' . $iLine . '<br />';
    if (defined('DEBUG') && DEBUG) {
        echo $sErrorMsg;
    } else {
        Debug::logError($iErrorLevel, $sErrorMsg, $sFile, $iLine, null, Debug::LOG_IN_EMAIL);
        Debug::logError($iErrorLevel, $sErrorMsg, $sFile, $iLine, null, Debug::LOG_IN_DATABASE);
    }
}

/**
 * handle fatal error or other errors that shutdown the script
 */
function shutdown_handler()
{
    $aErrorDetails = error_get_last();
    if ($aErrorDetails === null) {
        return;
    }
    if (!error_reporting()) {
        return false;
    }
    $sErrorMsg = '<br />';
    switch ($aErrorDetails['type']) {
        case E_ERROR:
            $sErrorMsg .= '<b>Fatal error: </b>';
            break;
        default:
            return;
    }
    $sErrorMsg .= $aErrorDetails['message'] . ' in <b>' . $aErrorDetails['file'] . '</b>' . ' on line ' . $aErrorDetails['line'] . '<br />';

    if (DEBUG) {
        echo $sErrorMsg;
    } else {
        Debug::logError($aErrorDetails['type'], $sErrorMsg, $aErrorDetails['file'], $aErrorDetails['line'], '', Debug::LOG_IN_EMAIL);
        Debug::logError($aErrorDetails['type'], $sErrorMsg, $aErrorDetails['file'], $aErrorDetails['line'], '', Debug::LOG_IN_DATABASE);

        
        echo 'Er is een fatale fout opgetreden. Neem a.u.b. contact met ons op.';
    }
}

/**
 * check string for anchor tags and BB code links (SPAM content check)
 *
 * @param string $sContent
 *
 * @return boolean
 */
function hasLinks($sContent)
{
    return preg_match('#(<a([^>]*)href=|\[link=|\[url=|\[hyperlink=)#i', $sContent);
}

/**
 * generate a random password
 *
 * @param int    $iLength          length of the password
 * @param int    $iMinLowerChars   minimum number of lowercase characters
 * @param int    $iMinUpperChars   minimum number of uppercase characters
 * @param int    $iMinNumbers      minimum number of numbers
 * @param int    $iMinSpecialChars minimum number of special characters
 * @param string $sLowerChars      usable lowercase characters
 * @param string $sUpperChars      usable uppercase characters
 * @param string $sNumbers         usable numbers
 * @param string $sSpecialChars    usable special characters
 *
 * @return string
 */
function randomPassword(
    $iLength = 10,
    $iMinLowerChars = 1,
    $iMinUpperChars = 1,
    $iMinNumbers = 1,
    $iMinSpecialChars = 1,
    $sLowerChars = 'abcdefghijkmnpqrstuvwxyz',
    $sUpperChars = 'ABCDEFGHJKLMNPQRSTUVWXYZ',
    $sNumbers = '23456789',
    $sSpecialChars = '!@#$%^&*()?'
) {
# count all the required characters
    $iSumRequiredChars = $iMinLowerChars + $iMinUpperChars + $iMinNumbers + $iMinSpecialChars;

# make sure the length is never lower than the number of required characters
    if ($iLength < ($iSumRequiredChars)) {
        $iLength = $iSumRequiredChars;
    }

    $sPassword = '';

# add the minimun number of lowercase characters
    for ($i = 0; $i < $iMinLowerChars; $i++) {
        $sPassword .= substr($sLowerChars, rand(0, strlen($sLowerChars) - 1), 1);
    }

# add the minimun number of uppercase characters
    for ($i = 0; $i < $iMinUpperChars; $i++) {
        $sPassword .= substr($sUpperChars, rand(0, strlen($sUpperChars) - 1), 1);
    }

# add the minimun number of numbers
    for ($i = 0; $i < $iMinNumbers; $i++) {
        $sPassword .= substr($sNumbers, rand(0, strlen($sNumbers) - 1), 1);
    }

# add the minimun number of special characters
    for ($i = 0; $i < $iMinSpecialChars; $i++) {
        $sPassword .= substr($sSpecialChars, rand(0, strlen($sSpecialChars) - 1), 1);
    }

# put all usable characters together for the rest
    $sAllUsableChars = $sLowerChars . $sUpperChars . $sNumbers . $sSpecialChars;

# add the rest
    for ($i = 0; $i < ($iLength - $iSumRequiredChars); $i++) {
        $sPassword .= substr($sAllUsableChars, rand(0, strlen($sAllUsableChars) - 1), 1);
    }

    return str_shuffle($sPassword);
}

/**
 * generate a meta description based on given content
 * replaces </p>,<br />,<br>,<br/> with spaces
 * takes first 20 words and 156 chars from the 20 words
 * makes html entities for strange chars and quotes
 * trims whitespace
 *
 * @param string $sContent
 *
 * @return string
 */
function generateMetaDescription($sContent)
{
    return trim(htmlspecialchars(firstXCharacters(firstXWords(preg_replace('#(\<\/p\>|\<br ?\/?\>)#i', ' ', preg_replace('#(\n|\r)#i', '', $sContent)), 20, '', ''), 156, ''), ENT_QUOTES, 'UTF-8', false));
}

/**
 * get string between two given strings
 *
 * @param string $sString haystack
 * @param string $sStart  string where to start searching, for example "<p>@kolom1@</p>"
 * @param string $sEnd    string where to stop searching, for example "<p>@end_kolom1@</p>"
 * @param array  $aMatches
 */
function matchBetweenStrings($sString, $sStart, $sEnd, &$aMatches)
{
    $sStart = preg_replace('#([\^\$\(\)\<\>\|\\\{\}\[\]\.\*\+\?\#]+?)#i', '\\\\$1', $sStart); // remove all characters that need to be escaped for regular expressions
    $sEnd   = preg_replace('#([\^\$\(\)\<\>\|\\\{\}\[\]\.\*\+\?\#]+?)#i', '\\\\$1', $sEnd); // remove all characters that need to be escaped for regular expressions
    preg_match_all('#' . $sStart . '(.*?)' . $sEnd . '#si', $sString, $aMatches); // match all characters between the start and end
    $aMatches = $aMatches[1]; // only set individual matches
}

/**
 * Insert the address and return the lat long
 *
 * @param string $sAddress
 * @param bool   $bUseIPv4
 *
 * @return array (latitude / longitude)
 */
function getLatLong($sAddress, $bUseIPv4 = false)
{

    if (!SettingManager::getSettingByName('googleGeoMapsKey') || empty(SettingManager::getSettingByName('googleGeoMapsKey')->value)) {
        Debug::logError(
            "",
            __FUNCTION__ . ' on website ' . CLIENT_HTTP_URL . ' has no/invalid googleGeoMapsKey setting',
            __FILE__,
            __LINE__,
            "Tried to run " . __FUNCTION__ . ", but an error occurred. Reason: missing setting or no API key is given<br />",
            Debug::LOG_IN_EMAIL
        );
        die('You need an API key to run this action');
    }

    $sGoogleGeoMapsKey = Settings::get('googleGeoMapsKey');
    $sUrl              = 'https://maps.google.com/maps/api/geocode/json?address=' . urlencode($sAddress) . '&key=' . $sGoogleGeoMapsKey;

    $ch       = curl_init();
    $iTimeout = 1;
    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $iTimeout);

    // force ipv4 if ipv6 is not working
    if ($bUseIPv4) {
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }

    $sGeocode = curl_exec($ch);
    $aGetInfo = curl_getinfo($ch);

    if (isset($aGetInfo["http_code"]) && $aGetInfo["http_code"] == 200 && !curl_errno($ch)) {
        $oOutput = json_decode($sGeocode);
# Check if there is a lattitude and longitude
        if (!empty($oOutput->results[0]->geometry->location->lat) && !empty($oOutput->results[0]->geometry->location->lng)) {
            $aLocation              = [];
            $aLocation['latitude']  = $oOutput->results[0]->geometry->location->lat;
            $aLocation['longitude'] = $oOutput->results[0]->geometry->location->lng;
        }
    }

    curl_close($ch);
    if (isset($aLocation)) {
        return $aLocation;
    }

    return null;
}

/**
 * return decimal converted to valuta optional with currency symbol
 *
 * @param float  $fDecimal
 * @param string $sLang               language to determine currency symbol and decimal signs etc DEFAULT 'nl'
 * @param bool   $bWithCurrencySymbol optionally show currency symbol dEFAULT true
 *
 * @return string
 */
function decimal2valuta($fDecimal, $sLang = 'nl', $bWithCurrencySymbol = true)
{
    if (!is_numeric($fDecimal)) {
        return '';
    }

    // i18n
    $i18nCurrency          = Settings::getDefault('currency-symbol', '€');
    $i18nDecimals          = (int) Settings::getDefault('currency-decimals', 2);
    $i18nDecimalPoint      = Settings::getDefault('currency-decimal-point', ',');
    $i18nThousandSeparator = Settings::getDefault('currency-thousand-separator', '.');

    switch ($sLang) {
        case 'nl':
            return ($bWithCurrencySymbol ? $i18nCurrency . ' ' : '') . number_format($fDecimal, $i18nDecimals, $i18nDecimalPoint, $i18nThousandSeparator);
            break;
    }
}

/**
 * converts HTML characters to HTML entities (double encode disables)
 *
 * @param mixed $mValue
 *
 * @return string
 */
function _e($mValue)
{
    return htmlentities($mValue, ENT_QUOTES, 'UTF-8', false);
}

/**
 * compare the sSortValue property with two objects (this can be used to create a user defined sort)
 *
 * @param object $oX
 * @param object $oY
 *
 * @return 0/-1/1
 */
function compareSortValue($oX, $oY)
{
    $oX->sSortValue = strtolower($oX->sSortValue);
    $oY->sSortValue = strtolower($oY->sSortValue);

    if ($oX->sSortValue == $oY->sSortValue) {
        return 0;
    } else {
        if ($oX->sSortValue < $oY->sSortValue) {
            return -1;
        } else {
            return 1;
        }
    }
}

/**
 * convert hex to rgb
 *
 * @param string $sHex
 *
 * @return array
 */
function hex2rgb($sHex)
{
    $sHex = str_replace("#", "", $sHex);
    if (strlen($sHex) == 3) {
        $iR = hexdec(substr($sHex, 0, 1) . substr($sHex, 0, 1));
        $iG = hexdec(substr($sHex, 1, 1) . substr($sHex, 1, 1));
        $iB = hexdec(substr($sHex, 2, 1) . substr($sHex, 2, 1));
    } else {
        $iR = hexdec(substr($sHex, 0, 2));
        $iG = hexdec(substr($sHex, 2, 2));
        $iB = hexdec(substr($sHex, 4, 2));
    }

    return [
        'r' => $iR,
        'g' => $iG,
        'b' => $iB,
    ];
}

/**
 * removes all emoji from the given string.
 *
 * @param string $sContent
 *
 * @return strin $sContent
 */
function removeEmoji($sContent)
{
    return preg_replace(
        '/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u',
        '',
        $sContent
    );
}

/**
 * Remove punctuations from a string
 *
 * @return string
 */
function removePunctuation($sString)
{
    /* verwijder accenten op characters */
    $sString = htmlentities($sString, null, "UTF-8");
    $sString = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $sString);

    return html_entity_decode($sString);
}

/**
 * Retrieve the id of given model
 *
 * @param \Model $oObject
 *
 * @return int
 */
function getId(Model $oObject)
{
    $sIdField = getIdField($oObject);

    return $oObject->$sIdField;
}

/**
 * Retrieve the id field of given model
 *
 * @param \Model|string $mObject
 *
 * @return int
 */
function getIdField($mObject)
{
    if (is_object($mObject)) {
        $mObject = get_class($mObject);
    }

    return sprintf('%1$sId', StringHelper::toCamelCase($mObject));
}

/**
 * return title in given model
 *
 * @param Model $oObject
 *
 * @return string
 */
function getTitle(Model $oObject)
{
    if (method_exists($oObject, 'getTranslations')) {
        return getTitle($oObject->getTranslations());
    }
    if (method_exists($oObject, 'getShortTitle')) {
        return $oObject->getShortTitle();
    }
    if (isset($oObject->title) && $oObject->title) {
        return $oObject->title;
    }
    if (isset($oObject->name) && $oObject->name) {
        return $oObject->name;
    }
    if (isset($oObject->question) && $oObject->question) {
        return $oObject->question;
    }

    return false;
}

/**
 * return content in given model
 *
 * @param Model $oObject
 *
 * @return string
 */
function getContent(Model $oObject)
{
    if (method_exists($oObject, 'getTranslations')) {
        return getContent($oObject->getTranslations());
    }
    if (isset($oObject->content) && $oObject->content) {
        return $oObject->content;
    }
    if (isset($oObject->intro) && $oObject->intro) {
        return $oObject->intro;
    }
    if (isset($oObject->description) && $oObject->description) {
        return $oObject->description;
    }

    return '';
}

/** Polyfills */

/**
 * Polyfill for the apache mod_php and php_cgi function getallheaders
 * Designed to replicate the functionality in php_fpm
 */
if (!function_exists('getallheaders')) {
    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @return string[string] The HTTP header key/value pairs.
     */
    function getallheaders()
    {
        $headers     = [];
        $copy_server = [
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5',
        ];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key           = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }
        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass               = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }
}

if (!function_exists('spl_object_id')) {
    function spl_object_id($oObject)
    {
        return spl_object_hash($oObject);
    }
}

/**
 * return global validate error
 *
 * @param type $sLabel
 *
 * @return type string
 */
function getGlobalValidateError($sLabel)
{
    return _e(sprintf(sysTranslations::get('global_label_field_not_filled_in_correctly'), $sLabel));
}

/**
 * Replace tags for AMP equivalents for dynamic content
 *
 * @param type $html
 *
 * @return type string
 */

function ampify($html = '')
{

    # Replace iframe, img, audio, and video elements with amp custom elements
    # Please implement proper AMP views and template with it corresponding components
    # see https://www.ampproject.org/docs/reference/components for more information

    $aReplace      = [
        '<iframe',
        '<img',
        '<video',
        '/video>',
        '<audio',
        '/audio>',
        'border="0"',
    ];
    $aReplaceWidth = [
        '<amp-iframe',
        '<amp-img',
        '<amp-video',
        '/amp-video>',
        '<amp-audio',
        '/amp-audio>',
        '',
    ];

    $html = str_ireplace(
        $aReplace,
        $aReplaceWidth,
        $html
    );
    # Add closing tags to amp-img custom element
    $html = preg_replace('/<amp-img(.*?)>/', '<amp-img layout="responsive"$1></amp-img>', $html);
    $html = preg_replace('/<amp-iframe(.*?)>/', '<amp-iframe sandbox="allow-scripts allow-same-origin" layout="responsive"$1></amp-iframe>', $html);
    # Whitelist of HTML tags allowed by AMP
    $html = strip_tags(
        $html,
        '<h1><h2><h3><h4><h5><h6><a><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><amp-iframe><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>'
    );
    $html = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);

    return $html;
}

/**
 * create IP range
 *
 * @param $sStartIp
 * @param $sLastIp
 *
 * @return array
 */
function createIpRange($sStartIp, $sLastIp)
{
    $aIp2LongIps     = range(ip2long($sStartIp), ip2long($sLastIp));
    $aWhitelistedIps = [];
    foreach ($aIp2LongIps as $sIp2LongIp) {
        $aWhitelistedIps[] = long2ip($sIp2LongIp);
    }

    return $aWhitelistedIps;
}

/**
 * search for replace tag and replace content
 *
 * @param string      $sContent
 * @param \Form|null  $oForm
 * @param \PageLayout $oPageLayout
 *
 * @return string $sContent
 */
function applyFormLogic($sContent, &$oForm, PageLayout $oPageLayout)
{
    if (moduleExists('forms') && $oForm instanceof Form) {
        if (strpos($sContent, '{{form}}') !== false) {
            ob_start();
            include getSiteSnippet('form', 'forms');
            $sSnippetOutput = ob_get_clean();
            $sContent       = preg_replace('/<p>(.*)({{form}})(.*)<\/p>/', $sSnippetOutput, $sContent);
            $oForm          = null;
        }
    }

    return $sContent;
}

/**
 * replace (clickable) absolute urls with relative urls in html content
 *
 * @param string $sContent
 *
 * @return string $sContent
 */
function convertAbsToRelLinks($sContent)
{
    // process all clickable links
    $sContent = preg_replace_callback('/<a(.*?)href="(.*?)"(.*?)>/',
        function ($matches) {
            // replace internal client http url
            $matches[2] = str_ireplace(CLIENT_HTTP_URL, '', $matches[2]);
            // prevent empty urls (ie root url)
            if (empty($matches[2])) {
                $matches[2] = '/';
            }

            return '<a' . (isset($matches[1]) ? $matches[1] : '') . 'href="' . $matches[2] . '"' . (isset($matches[3]) ? $matches[3] : '') . '>';
        }
        , $sContent);

    return $sContent;
}

/**
 * Create WebP from site images
 *
 * @param $sFileName
 */
function createWebPImage($sFileName)
{
    $sFile = file_get_contents(DOCUMENT_ROOT . $sFileName);
    if ($sFile) {
        $aImages[$sFileName] = base64_encode($sFile);
        $aWebpFiles          = ImageEditorWebserviceManager::getWebPImages($aImages, []);

        if ($aWebpFiles) {
            foreach ($aWebpFiles['images'] as $sFilePath => $sEncodedFile) {
                if (file_exists(DOCUMENT_ROOT . $sFilePath . '.webp')) {
                    continue;
                }

                file_put_contents(DOCUMENT_ROOT . $sFilePath . '.webp', base64_decode($sEncodedFile));
            }
        }
    }
}

/**
 * Get Resize link from any image
 *
 * @param      $sLink
 * @param null $iWidth
 * @param null $iHeight
 *
 * @return string
 */
function getResizeLink($sLink, $iWidth = null, $iHeight = null)
{

    if (!$iWidth && !$iHeight) {
        return $sLink;
    }

    $iW                 = $iWidth;
    $iH                 = $iHeight;
    $sOriginalFolder    = pathinfo($sLink, PATHINFO_DIRNAME);
    $sAutoresizedFolder = 'autoresized';
    $sFileName          = pathinfo($sLink, PATHINFO_BASENAME);
    $sOriginalLocation  = $sOriginalFolder . '/' . $sFileName;
    $bRemoveTmpFile     = false;
    $sNewFileName       = pathinfo($sLink, PATHINFO_FILENAME) . ($iWidth ? '_w' . $iWidth : '') . ($iHeight ? '_h' . $iHeight : '') . '.' . pathinfo($sLink, PATHINFO_EXTENSION);

    if (!file_exists(DOCUMENT_ROOT . '/' . $sOriginalLocation)) {
        // file not found on local machine, try to find on remote
        if (LIVE_HTTP_URL != '' && LIVE_HTTP_URL != 'x') {
            $sFileContentsOriginal = @file_get_contents(LIVE_HTTP_URL . '/' . $sOriginalLocation);
        } else {
            $sFileContentsOriginal = false;
        }

        if ($sFileContentsOriginal !== false) {
            // file downloaded, put in temp folder to use for autoresize
            if (!file_exists(DOCUMENT_ROOT . '/private_files/tmp/autoresize')) {
                // folder does not exist but parent folder is writable so create it
                if (!is_writable(DOCUMENT_ROOT . '/private_files') || !mkdir(DOCUMENT_ROOT . '/private_files/tmp/autoresize', 0777, true)) {
                    return $sLink;

                }
            }

            // original location is location where tmp file is saved
            $sOriginalLocation = 'private_files/tmp/autoresize/' . $sFileName;
            // destination location is same as original because it's all temporary
            $sDestinationLocation = $sOriginalLocation;
            $bRemoveTmpFile       = true; // unlink file when finished
            if (!file_put_contents($sOriginalLocation, $sFileContentsOriginal)) {
                return $sLink;

            }
        }
    } else {

        // file found on local file system, autoresize will be saved locally
        if (!file_exists(DOCUMENT_ROOT . '/' . $sOriginalFolder . '/' . $sAutoresizedFolder)) {

            // folder does not exist but parent folder is writable so create it
            if (!is_writable($sOriginalFolder) || !mkdir($sOriginalFolder . '/' . $sAutoresizedFolder, 0777, true)) {
                return $sLink;

            }
        }

        // set destination to requested file name and location on local file system
        $sDestinationLocation = $sOriginalFolder . '/' . $sAutoresizedFolder . '/' . $sNewFileName;
    }

    if (file_exists(DOCUMENT_ROOT . $sDestinationLocation)) {
        return $sDestinationLocation;
    }

    if (!empty($sOriginalLocation) && !empty($sDestinationLocation)) {
        // original found on local so create resize on local
        if (!empty($iW) && !empty($iH)) {
            ImageManager::autoCropAndResizeImage(DOCUMENT_ROOT . '/' . $sOriginalLocation, DOCUMENT_ROOT . '/' . $sDestinationLocation, $iW, $iH, $sErrorMsg);
        } elseif (!empty($iW)) {
            ImageManager::resizeImageW(DOCUMENT_ROOT . '/' . $sOriginalLocation, DOCUMENT_ROOT . '/' . $sDestinationLocation, $iW, $sErrorMsg);
        } elseif (!empty($iH)) {
            ImageManager::resizeImageH(DOCUMENT_ROOT . '/' . $sOriginalLocation, DOCUMENT_ROOT . '/' . $sDestinationLocation, $iH, $sErrorMsg);
        }

        // get file contents, next time the file will be returned directly by the server
        if ($bRemoveTmpFile) {
            // remove destination file if it is a temporary file
            @unlink(DOCUMENT_ROOT . '/' . $sDestinationLocation);
        }
    }

    return $sDestinationLocation;
}

/**
 * Get the image attributes for this imageFile
 *
 * @param      $sLink
 * @param bool $bBackground is image a normal image, or background image
 * @param int  $iResizeWidth
 *
 * @return string
 */
function getImageAttr($sLink, $bBackground = false, $iResizeWidth = ImageFile::LAZYLOAD_DEFAULT_RESIZE)
{

    return sprintf(
        $bBackground ? 'style="background-image: url(\'%1$s\')" data-bg="%2$s"' : 'src="%1$s" data-src="%2$s"',
        getResizeLink($sLink, $iResizeWidth),
        $sLink
    );
}

/**
 * get editor (logged in user of contact)
 * @return string
 */
function getEditor()
{
    if (!empty(UserManager::getCurrentUser())) {
        return UserManager::getCurrentUser()
            ->getDisplayName();
    }

    return 'Unknown';
}

/**
 *
 */
function ddate($oDateTime, $sNotation = 'M')
{

    $months = [
        'Jan' => 'jan',
        'Feb' => 'feb',
        'Mar' => 'mrt',
        'Apr' => 'apr',
        'May' => 'mei',
        'Jun' => 'jun',
        'Jul' => 'jul',
        'Aug' => 'aug',
        'Sep' => 'sep',
        'Oct' => 'okt',
        'Nov' => 'nov',
        'Dec' => 'dec'
    ];

    $weekdays = [
        'Mon' => 'ma',
        'Tue' => 'di',
        'Wed' => 'wo',
        'Thu' => 'do',
        'Fri' => 'vr',
        'Sat' => 'za',
        'Sun' => 'zo'
    ];

    $sDdatetime = $oDateTime->format($sNotation);

    $sDdatetime = str_replace(array_keys($months),   array_values($months),   $sDdatetime);
    $sDdatetime = str_replace(array_keys($weekdays), array_values($weekdays), $sDdatetime);

    return $sDdatetime;
}

function dayOfWeek($iDayNumber = 0)
{
    $weekdays = [
        0 => 'Zondag',
        1 => 'Maandag',
        2 => 'Dinsdag',
        3 => 'Woensdag',
        4 => 'Donderdag',
        5 => 'Vrijdag',
        6 => 'Zaterdag'
    ];

    return $weekdays[$iDayNumber];
}

/**
 *
 */
function customerSelect($aCustomerArray = array(), $aPlanningIds = array(), $aColors = array())
{

    if (empty($aCustomerArray)) {
        return '';
    }
    $sReturn = '';
    $iCount = 0;
    $iCustomers = 0;
    foreach ($aCustomerArray as $iCustomerId => $sCompanyName) {
        $iCount++;
        if (!empty($sCompanyName)) {
            $iCustomers++;
            $val = $aPlanningIds[$iCustomerId];
            $sReturn .= '<option class="soc-' . ($aColors[$iCustomerId] ? $aColors[$iCustomerId] : $iCount) . '" value="' . $val . '">' . $sCompanyName . '</option>';
        }
    }


    if (trim($sReturn) == '') {
        return ('');
    }

    $sReturn = '<select class="customerSelect"><option value="">' . $iCustomers . ' klant' . ($iCustomers > 1 ? 'en' : '') . '</option>' . $sReturn . '</select>';
    return $sReturn;
}

/**
 *
 */
function daysBetween($sBeginDate, $sEndDate)
{
    $earlier = new DateTime($sBeginDate);
    $later = new DateTime($sEndDate);

    if ($earlier < $later) {
        return $later->diff($earlier)->format("%a");
    } else {
        return
            $earlier->diff($later)->format("%r%a");
    }
}

/**
 * 
 */
function arrayToReadableText($aArray) {

    $a = [];
    foreach ($aArray as $key => $value) {
        $a[] = "$key: $value";
    }
    $sResult = implode(PHP_EOL, $a);
    return $sResult;

}


/**
 * @param array|object $data
 * @return array
 */
function object_to_array($data)
{
    $result = [];
    foreach ($data as $key => $value)
    {
        $result[$key] = (is_array($value) || is_object($value)) ? object_to_array($value) : $value;
    }
    return $result;
}

/**
 * 
 */
function saveLog($link, $title, $content) {

    $oLog = new Log();
    $oLog->name = UserManager::getCurrentUser()->name;
    $oLog->userId = UserManager::getCurrentUser()->userId;    
    $oLog->link = $link;
    $oLog->title = $title;
    $oLog->content = $content;
   
    LogManager::saveLog($oLog);

}
<?php

// set document root
define("DOCUMENT_ROOT", realpath(dirname(__FILE__) . '/../'));

// @note legacy
define("ACCESS", 'xXx');

# include configuration file
require_once DOCUMENT_ROOT . '/modules/core/service/Autoloader.class.php';
// composer autoloader, though highly likely to exist, is not required to exist
if (file_exists(DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php')) {
    require_once DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}

require_once DOCUMENT_ROOT . '/init/init.inc.php';

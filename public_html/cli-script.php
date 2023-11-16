<?php

// set document root
require_once 'init/bootstrap.inc.php';

if (Request::isNotCli()) {
    throw new Exception('Error');
}

echo Router::route($argv);
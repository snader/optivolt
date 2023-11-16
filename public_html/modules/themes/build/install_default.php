<?php
//Set all modules as supported, except for the "." and ".." that scandir returns
$aSupportedModules = array_diff(scandir(DOCUMENT_ROOT . SITE_MODULES_FOLDER), ['.', '..']);

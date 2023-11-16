<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'fileManagement' => [
        'module'     => 'fileManager',
        'controller' => 'fileManagement',
    ],
];

$aNeededClassRoutes = [
    'File'            => [
        'module' => 'fileManager',
    ],
    'FileManager'     => [
        'module' => 'fileManager',
    ],
    'FileManagerHTML' => [
        'module' => 'fileManager',
    ],
];

$aNeededSiteControllerRoutes = [
];

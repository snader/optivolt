<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'linkManagement' => [
        'module'     => 'linkManager',
        'controller' => 'linkManagement',
    ],
];

$aNeededClassRoutes = [
    'Link'            => [
        'module' => 'linkManager',
    ],
    'LinkManager'     => [
        'module' => 'linkManager',
    ],
    'LinkManagerHTML' => [
        'module' => 'linkManager',
    ],
];

$aNeededSiteControllerRoutes = [
];

<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
    'fileManager',
];

$aNeededAdminControllerRoutes = [
    'imageManagement' => [
        'module'     => 'imageManager',
        'controller' => 'imageManagement',
    ],
];

$aNeededClassRoutes = [
    'Image'            => [
        'module' => 'imageManager',
    ],
    'ImageFile'        => [
        'module' => 'imageManager',
    ],
    'ImageManager'     => [
        'module' => 'imageManager',
    ],
    'ImageManagerHTML' => [
        'module' => 'imageManager',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededTranslations = [
    'en' => [

        ['label' => 'imagemanager_webservices_error_notice_title', 'text' => 'Status notification (Web service)'],
        ['label' => 'imagemanager_webservices_isUp_errror_notice', 'text' => 'Please note, the web service for optimizing images does not work (properly).'],
        ['label' => 'imagemanager_webservices_isUp_errror_notice_end_user', 'text' => 'You cannot upload images at this time, contact us.'],
    ],
    'nl' => [
        ['label' => 'imagemanager_sizes_setting_missing', 'text' => 'Afmeting instelling mist'],
        ['label' => 'imagemanager_images_path_setting_missing', 'text' => 'Pad instelling mist'],
        ['label' => 'imagemanager_webservices_error_notice_title', 'text' => 'Status melding (Webservice)'],
        ['label' => 'imagemanager_webservices_isUp_errror_notice', 'text' => 'Let op, de webservice voor het optimaliseren van afbeeldingen werkt niet (goed).'],
        ['label' => 'imagemanager_webservices_isUp_errror_notice_end_user', 'text' => 'U kunt op dit moment geen afbeeldingen uploaden, neem contact op.'],
    ],
];
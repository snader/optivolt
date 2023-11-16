<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'sitemap-uitgebreid' => [
        'module'     => 'sitemaps',
        'controller' => 'advancedSitemap',
    ],
];

$aNeededClassRoutes = [
];

$aNeededSiteControllerRoutes = [
    'sitemap'                   => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-news'              => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-agenda'            => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-pages'             => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-newscategories'    => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-photoalbums'       => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-catalog'           => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-catalogcategories' => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-employees'         => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-cases'             => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
    'sitemap-casecategories'    => [
        'module'     => 'sitemaps',
        'controller' => 'sitemap',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'             => 'sitemap-uitgebreid',
        'icon'             => 'fa-sitemap',
        'linkName'         => 'sitemap_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'sitemap_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'sitemap_cron_performed', 'text' => 'Cron uitgevoerd'],
        ['label' => 'sitemap_menu', 'text' => 'Sitemap'],
    ],
    'es' => [
        ['label' => 'sitemap_cron_performed', 'text' => 'Ejecutar Cron'],
        ['label' => 'sitemap_menu', 'text' => 'Sitemap'],
    ],
    'en' => [
        ['label' => 'sitemap_cron_performed', 'text' => 'Cron performed'],
        ['label' => 'sitemap_menu', 'text' => 'Sitemap'],
    ],
];

<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'module-generator' => [
        'module'     => 'moduleGenerator',
        'controller' => 'moduleGenerator',
    ],
];

$aNeededClassRoutes = [
    'ModuleGeneratorItem'        => [
        'module' => 'moduleGenerator',
    ],
    'ModuleGeneratorItemManager' => [
        'module' => 'moduleGenerator',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'             => 'module-generator',
        'icon'             => 'fa-code',
        'linkName'         => 'moduleGenerator_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'moduleGenerator_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'moduleGenerator_hasFiles', 'text' => 'Module heeft bestanden'],
        ['label' => 'moduleGenerator_hasFiles_tooltip', 'text' => 'Geeft aan of de module bestanden nodig heeft'],
        ['label' => 'moduleGenerator_hasImages', 'text' => 'Module heeft afbeeldingen'],
        ['label' => 'moduleGenerator_hasImages_tooltip', 'text' => 'Geeft aan of de module afbeeldingen nodig heeft'],
        ['label' => 'moduleGenerator_hasVideos', 'text' => "Module heeft video's"],
        ['label' => 'moduleGenerator_hasVideos_tooltip', 'text' => "Geeft aan of de module video's nodig heeft"],
        ['label' => 'moduleGenerator_hasLinks', 'text' => 'Module heeft links'],
        ['label' => 'moduleGenerator_hasLinks_tooltip', 'text' => 'Geeft aan of de module links nodig heeft'],
        ['label' => 'moduleGenerator_hasCategories', 'text' => 'Module heeft categorieën'],
        ['label' => 'moduleGenerator_hasCategories_tooltip', 'text' => 'Geeft aan of de module categorieën nodig heeft'],
        ['label' => 'moduleGenerator_hasUrls', 'text' => 'Module heeft frontend views'],
        ['label' => 'moduleGenerator_hasUrls_tooltip', 'text' => 'Geeft aan of de module frontend views nodig heeft'],
        ['label' => 'whats_the_name', 'text' => 'Hoe gaat de module heten?'],

        ['label' => 'moduleGenerator_menu', 'text' => 'Module generator'],
        ['label' => 'moduleGenerator_hasFiles', 'text' => 'Module heeft bestanden'],
        ['label' => 'moduleGenerator_hasImages', 'text' => 'Module heeft afbeeldingen'],
        ['label' => 'moduleGenerator_hasVideos', 'text' => "Module heeft video's"],
        ['label' => 'moduleGenerator_hasLinks', 'text' => "Module heeft links"],
        ['label' => 'moduleGenerator_hasCategories', 'text' => "Module heeft categorieën"],
        ['label' => 'moduleGenerator_hasUrls', 'text' => "Module heeft URL's"],
        ['label' => 'moduleGenerator_moduleFolderName', 'text' => 'Module mapnaam (meervoud, camelCased)'],
        ['label' => 'moduleGenerator_classFileName', 'text' => 'Class naam (enkelvoud, PascalCased)'],
        ['label' => 'moduleGenerator_moduleDescription', 'text' => 'Module beschrijving (readme.txt)'],
        ['label' => 'moduleGenerator_controllerRoute', 'text' => 'URL module'],
        ['label' => 'moduleGenerator_controllerFileName', 'text' => 'Controller bestandsnaam (enkelvoud, camelCased)'],
        ['label' => 'moduleGenerator_defaultLocalePageControllerRoute', 'text' => 'URL path (default locale)'],
        ['label' => 'moduleGenerator_notDefaultLocalePageControllerRoute', 'text' => 'URL path (not default locale)'],
        ['label' => 'moduleGenerator_defaultLocalePageTitle', 'text' => 'Pagina titel (default locale)'],
        ['label' => 'moduleGenerator_notDefaultLocalePageTitle', 'text' => 'Pagina titel (not default locale)'],
        ['label' => 'moduleGenerator_pageSystemName', 'text' => 'Pagina systeemnaam (lowercase)'],
        ['label' => 'moduleGenerator_singleSystemFileName', 'text' => 'Bestandsnaam (enkelvoud, camelCased)'],
        ['label' => 'moduleGenerator_multipleSystemFileName', 'text' => 'Bestandsnaam (meervoud, camelCased)'],
        ['label' => 'moduleGenerator_defaultLocaleTranslationItem', 'text' => 'Item vertaling (enkelvoud, default locale)'],
        ['label' => 'moduleGenerator_defaultLocaleTranslationItems', 'text' => 'Item vertaling (meervoud, default locale)'],
        ['label' => 'moduleGenerator_notDefaultLocaleTranslationItem', 'text' => 'Item vertaling (enkelvoud, not default locale)'],
        ['label' => 'moduleGenerator_notDefaultLocaleTranslationItems', 'text' => 'Item vertaling (meervoud, not default locale)'],
        ['label' => 'moduleGenerator_relationTableNamePrefix', 'text' => 'Database tabelnaam (enkelvoud, lowercase)'],
        ['label' => 'moduleGenerator_databaseAlias', 'text' => 'Module database alias'],
        ['label' => 'Module generator', 'text' => 'Module generator'],
        ['label' => 'moduleGenerator_not_saved', 'text' => 'Module niet gegenereerd'],
        ['label' => 'moduleGenerator_saved', 'text' => 'Module gegenereerd, deze is nu installeerbaar'],
        ['label' => 'moduleGenerator_no_defaultLocaleTranslationItem', 'text' => 'Item vertaling opgegeven (enkelvoud)'],
        ['label' => 'moduleGenerator_no_defaultLocaleTranslationItems', 'text' => 'Item vertaling opgegeven (meervoud)'],
        ['label' => 'moduleGenerator_no_notDefaultLocaleTranslationItem', 'text' => 'Item vertaling opgegeven (enkelvoud)'],
        ['label' => 'moduleGenerator_no_notDefaultLocaleTranslationItems', 'text' => 'Item vertaling opgegeven (meervoud)'],
        ['label' => 'moduleGenerator_no_moduleFolderName', 'text' => 'Module foldernaam opgegeven'],
        ['label' => 'moduleGenerator_no_classFileName', 'text' => 'Class naam opgegeven'],
        ['label' => 'moduleGenerator_no_moduleDescription', 'text' => 'Module omschrijving opgegeven'],
        ['label' => 'moduleGenerator_no_controllerFileName', 'text' => 'Controller naam opgegeven'],
        ['label' => 'moduleGenerator_no_singleSystemFileName', 'text' => 'Bestandsnaam opgegeven'],
        ['label' => 'moduleGenerator_no_multipleSystemFileName', 'text' => 'Bestandsnaam opgegeven'],
        ['label' => 'moduleGenerator_no_relationTableNamePrefix', 'text' => 'Tabelnaam opgegeven'],
        ['label' => 'moduleGenerator_no_defaultLocalePageControllerRoute', 'text' => 'Page controller route opgegeven'],
        ['label' => 'moduleGenerator_no_notDefaultLocalePageControllerRoute', 'text' => 'Page controller route opgegeven'],
        ['label' => 'moduleGenerator_no_defaultLocalePageTitle', 'text' => 'Pagina titel opgegeven'],
        ['label' => 'moduleGenerator_no_notDefaultLocalePageTitle', 'text' => 'Pagina titel opgegeven'],
        ['label' => 'moduleGenerator_no_pageSystemName', 'text' => 'Pagina naam opgegeven'],
        ['label' => 'moduleGenerator_autofill_explain', 'text' => 'Op basis van de module naam, worden de velden hieronder automatisch vooringevuld. Je kunt daarna zelf nog wijzigingen maken.'],
        ['label' => 'moduleGenerator_generate_module', 'text' => 'Genereer module'],
        ['label' => 'moduleGenerator_module_naming', 'text' => 'Naamgeving'],
        ['label' => 'moduleGenerator_page_settings', 'text' => 'Pagina instellingen'],
        ['label' => 'moduleGenerator_module_options', 'text' => 'Module opties'],
        ['label' => 'moduleGenerator_generating_module', 'text' => 'Module wordt gegenereerd...'],
        ['label' => 'moduleGenerator_fontawesomeIcon', 'text' => 'FontAwesome icoon'],
        ['label' => 'moduleGenerator_no_fontawesomeIcon', 'text' => 'Geen FontAwesome icoon gedefinieerd'],
        ['label' => 'fontawesome_icon_link', 'text' => '<a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">Zoek icoon</a>'],
    ]
];

<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'inventarisations' => [
        'module'     => 'inventarisations',
        'controller' => 'inventarisation',
    ],
    
];

$aNeededClassRoutes = [
    'Inventarisation'        => [
        'module' => 'inventarisations',
    ],
    'InventarisationManager' => [
        'module' => 'inventarisations',
    ]
];

$aNeededModulesForMenu = [
    [
        'name'          => 'inventarisations',
        'icon'          => 'fa-check-double',
        'linkName'      => 'inventarisations_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'inventarisations_full'],
        ]
    ]
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'inventarisation_not_deletable', 'text' => 'Inventarisatie is niet verwijderbaar'],
        ['label' => 'inventarisations_menu', 'text' => 'Inventarisatie'],
        ['label' => 'inventarisation_deleted', 'text' => 'Inventarisatie is verwijderd'],
        ['label' => 'inventarisation_not_deleted', 'text' => 'Inventarisatie kan niet worden verwijderd'],
        ['label' => 'inventarisation_not_saved', 'text' => 'Inventarisatie is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'inventarisation_saved', 'text' => 'Inventarisatie is opgeslagen'],
        ['label' => 'inventarisation_not_edited', 'text' => 'Inventarisatie kan niet worden bewerkt'],
        ['label' => 'inventarisation_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'inventarisation_content', 'text' => 'Content'],
        ['label' => 'inventarisation_video_warning', 'text' => "Video's kunnen worden toegevoegd nadat het item eerst is opgeslagen"],
        ['label' => 'inventarisation_links_warning', 'text' => 'Links kunnen worden toegevoegd nadat het item eerst is opgeslagen'],
        ['label' => 'inventarisation_files_warning', 'text' => 'Bestanden kunnen worden geüpload nadat het item eerst is opgeslagen'],
        ['label' => 'inventarisation_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat het item eerst is opgeslagen'],
        ['label' => 'inventarisation_content_tooltip', 'text' => 'Vul hier uw content in.'],
        ['label' => 'inventarisation_intro_tooltip', 'text'  => 'Vul hier een korte introductie tekst in.'],
        ['label' => 'inventarisation_intro', 'text' => 'Intro (korte intro tekst)'],
        ['label' => 'inventarisation_enter_title_tooltip', 'text' => 'Vul de titel in'],
        ['label' => 'inventarisation_title_tooltip', 'text' => 'De titel van uw item'],
        ['label' => 'inventarisation_set_online_tooltip', 'text' => 'Zet het inventarisatie online OF offline'],
        ['label' => 'inventarisation', 'text' => 'Inventarisatie'],
        ['label' => 'inventarisation_not_changed', 'text' => 'Inventarisatie niet gewijzigd'],
        ['label' => 'inventarisation_is_offline', 'text' => 'Inventarisatie offline gezet'],
        ['label' => 'inventarisation_is_online', 'text' => 'Inventarisatie online gezet'],
        ['label' => 'inventarisation_no_inventarisation', 'text' => 'Er zijn geen inventarisatie om weer te geven'],
        ['label' => 'inventarisation_delete', 'text' => 'Verwijder inventarisatie'],
        ['label' => 'inventarisation_edit', 'text' => 'Bewerk inventarisatie'],
        ['label' => 'inventarisation_set_offline', 'text' => 'Inventarisatie offline zetten'],
        ['label' => 'inventarisation_set_online', 'text' => 'Inventarisatie online zetten'],
        ['label' => 'inventarisation_add', 'text' => 'Inventarisatie toevoegen'],
        ['label' => 'inventarisation_add_tooltip', 'text' => 'Nieuw inventarisatie toevoegen'],
        ['label' => 'inventarisation_all', 'text' => 'Alle inventarisatie'],
        ['label' => 'inventarisation_filter', 'text' => 'Filter inventarisatie'],
    ],
    'en' => [
        ['label' => 'inventarisation_not_deletable', 'text' => 'Inventarisatie is not deletable'],
        ['label' => 'inventarisations_menu', 'text' => 'Inventarisation'],
        ['label' => 'inventarisation_not_deleted', 'text' => 'Inventarisation cannot be deleted'],
        ['label' => 'inventarisation_not_saved', 'text' => 'Inventarisation is not saved, not all fields are (correctly) filled in'],
        ['label' => 'inventarisation_saved', 'text' => 'Inventarisation is saved'],
        ['label' => 'inventarisation_not_edited', 'text' => 'Inventarisation cannot be edited'],
        ['label' => 'inventarisation_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'inventarisation_content', 'text' => 'Content'],
        ['label' => 'inventarisation_video_warning', 'text' => 'Videolinks can be added after the item is saved'],
        ['label' => 'inventarisation_links_warning', 'text' => 'Links can be added after the item is saved'],
        ['label' => 'inventarisation_files_warning', 'text' => 'Files can be uploaded after the item page is saved'],
        ['label' => 'inventarisation_images_warning', 'text' => 'Images can be uploaded after the item is saved'],
        ['label' => 'inventarisation_content_tooltip', 'text' => 'Enter the text.'],
        ['label' => 'inventarisation_intro_tooltip', 'text'  => 'Fill in here a short introduction text.'],
        ['label' => 'inventarisation_intro', 'text' => 'Intro (short intro text)'],
        ['label' => 'inventarisation_enter_title_tooltip', 'text' => 'Fill in the title of the inventarisation'],
        ['label' => 'inventarisation_title_tooltip', 'text' => 'The title of the inventarisation'],
        ['label' => 'inventarisation_set_online_tooltip', 'text' => 'Place the inventarisation online or offline'],
        ['label' => 'inventarisation', 'text' => 'Inventarisation'],
        ['label' => 'inventarisation_not_changed', 'text' => 'Inventarisation not changed'],
        ['label' => 'inventarisation_is_offline', 'text' => 'Inventarisation placed offline'],
        ['label' => 'inventarisation_is_online', 'text' => 'Inventarisation placed online'],
        ['label' => 'inventarisation_no_inventarisation', 'text' => 'There are no inventarisations to display'],
        ['label' => 'inventarisation_delete', 'text' => 'Delete inventarisation'],
        ['label' => 'inventarisation_edit', 'text' => 'Edit inventarisation'],
        ['label' => 'inventarisation_set_offline', 'text' => 'Set inventarisation offline'],
        ['label' => 'inventarisation_set_online', 'text' => 'Set inventarisation online'],
        ['label' => 'inventarisation_add', 'text' => 'Add inventarisation'],
        ['label' => 'inventarisation_add_tooltip', 'text' => 'Add a inventarisation'],
        ['label' => 'inventarisation_all', 'text' => 'All inventarisations'],
        ['label' => 'inventarisation_filter', 'text' => 'Filter inventarisations'],
    ]
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        
        
        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_back_to_overview', 'text' => 'Terug naar het overzicht', 'editable' => 1]
    ],
];

if (!$oDb->tableExists('inventarisations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `inventarisations`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `inventarisations` (
          `inventarisationId` int(11) NOT NULL AUTO_INCREMENT,  
          `parentInventarisationId` int(11) NULL DEFAULT NULL,        
          `loggerId` int(11) NULL DEFAULT NULL,    
          `customerId` int(11) NULL DEFAULT NULL,
          `customerName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `userId` int(11) NULL DEFAULT NULL,  
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `kva` int(11) NULL DEFAULT NULL,   
          `position` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,   
          `freeFieldAmp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,    
          `stroomTrafo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `control` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `relaisNr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `engineKw` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `turningHours` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `photoNrs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `trafoNr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `mlProposed` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  
          `remarks` text COLLATE utf8_unicode_ci,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`inventarisationId`),
          KEY `loggerId` (`loggerId`),
          KEY `customerId` (`customerId`),
          KEY `parentInventarisationId` (`parentInventarisationId`),
          KEY `userId` (`userId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('inventarisations')) {

    if ($oDb->tableExists('loggers')) {
        // check languages constraint
        if (!$oDb->constraintExists('inventarisations', 'loggerId', 'loggers', 'loggerId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `inventarisations`.`loggerId` => `loggers`.`loggerId`';
            if ($bInstall) {
                $oDb->addConstraint('inventarisations', 'loggerId', 'loggers', 'loggerId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('customers')) {
        // check languages constraint
        if (!$oDb->constraintExists('inventarisations', 'customerId', 'customers', 'customerId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `inventarisations`.`customerId` => `customers`.`customerId`';
            if ($bInstall) {
                $oDb->addConstraint('inventarisations', 'customerId', 'customers', 'customerId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('users')) {
        // check languages constraint
        if (!$oDb->constraintExists('inventarisations', 'userId', 'users', 'userId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `inventarisations`.`userId` => `users`.`userId`';
            if ($bInstall) {
                $oDb->addConstraint('inventarisations', 'userId', 'users', 'userId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('inventarisations')) {
        // check languages constraint
        if (!$oDb->constraintExists('inventarisations', 'parentInventarisationId', 'inventarisations', 'inventarisationId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `inventarisations`.`inventarisationId` => `inventarisations`.`parentInventarisationId`';
            if ($bInstall) {
                $oDb->addConstraint('inventarisations', 'parentInventarisationId', 'inventarisations', 'inventarisationId', 'CASCADE', 'CASCADE');
            }
        }
    }
}


<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'systems' => [
        'module'     => 'systems',
        'controller' => 'system',
    ],
    'system-reports' => [
        'module'     => 'systems',
        'controller' => 'systemReports',
    ],

];

$aNeededClassRoutes = [
    'System'        => [
        'module' => 'systems',
    ],
    'SystemManager' => [
        'module' => 'systems',
    ],
    'SystemType'        => [
        'module' => 'systems',
    ],
    'SystemTypeManager' => [
        'module' => 'systems',
    ],
    'SystemReport'        => [
        'module' => 'systems',
    ],
    'SystemReportManager' => [
        'module' => 'systems',
    ],
    'SystemReportLine'        => [
        'module' => 'systems',
    ],
    'SystemReportLineManager' => [
        'module' => 'systems',
    ],
    `AppointmentManager` => [
        'module' => 'systems',
    ],


];

$aNeededModulesForMenu = [
    [
        'name'          => 'systems',
        'icon'          => 'fa-shapes',
        'linkName'      => 'systems_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'systems_full'],
        ]
    ],
    [
        'name'             => 'system-reports',
        'icon'             => 'fa-file-contract',
        'linkName'         => 'system_reports_menu',
        'parentModuleName' => 'systems',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'systemReports_full'],
        ],
    ]

];

$aNeededTranslations = [
    'nl' => [

        
        
        ['label' => 'system_report_deleted', 'text' => 'Metingen verwijderd'],
        ['label' => 'system_report_not_deleted', 'text' => 'Metingen niet verwijderd'],
        ['label' => 'system_system_report', 'text' => 'Meting'],
        ['label' => 'system_not_deletable', 'text' => 'Systeem is niet verwijderbaar'],
        ['label' => 'systems_menu', 'text' => 'Systemen'],
        ['label' => 'system_deleted', 'text' => 'Systeem is verwijderd'],
        ['label' => 'system_not_deleted', 'text' => 'Systeem kan niet worden verwijderd'],
        ['label' => 'system_not_saved', 'text' => 'Systeem is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'system_saved', 'text' => 'Systeem is opgeslagen'],
        ['label' => 'system_not_edited', 'text' => 'Systeem kan niet worden bewerkt'],

        ['label' => 'system_set_online_tooltip', 'text' => 'Zet het systeem online OF offline'],
        ['label' => 'system', 'text' => 'Systeem'],
        ['label' => 'system_not_changed', 'text' => 'Systeem niet gewijzigd'],
        ['label' => 'system_is_offline', 'text' => 'Systeem offline gezet'],
        ['label' => 'system_is_online', 'text' => 'Systeem online gezet'],
        ['label' => 'system_no_system', 'text' => 'Er zijn geen systemen om weer te geven'],
        ['label' => 'system_delete', 'text' => 'Verwijder systeem'],
        ['label' => 'system_edit', 'text' => 'Bewerk systeem'],
        ['label' => 'system_set_offline', 'text' => 'Systeem offline zetten'],
        ['label' => 'system_set_online', 'text' => 'Systeem online zetten'],
        ['label' => 'system_add', 'text' => 'Systeem toevoegen'],
        ['label' => 'system_add_tooltip', 'text' => 'Nieuw systeem toevoegen'],
        ['label' => 'system_all', 'text' => 'Alle systemen'],
        ['label' => 'system_filter', 'text' => 'Filter systemen'],

        ['label' => 'systemreport_not_deletable', 'text' => 'Rapport is niet verwijderbaar'],
        ['label' => 'systems_menu', 'text' => 'Systemen'],
        ['label' => 'systemreport_deleted', 'text' => 'Rapport is verwijderd'],
        ['label' => 'systemreport_not_deleted', 'text' => 'Rapport kan niet worden verwijderd'],
        ['label' => 'systemreport_not_saved', 'text' => 'Rapport is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'systemreport_saved', 'text' => 'Rapport is opgeslagen'],
        ['label' => 'systemreport_not_edited', 'text' => 'Rapport kan niet worden bewerkt'],
        ['label' => 'systemreport_content', 'text' => 'Content'],

        ['label' => 'systemreport_set_online_tooltip', 'text' => 'Zet het Rapport online OF offline'],
        ['label' => 'system', 'text' => 'Rapport'],
        ['label' => 'systemreport_not_changed', 'text' => 'Rapport niet gewijzigd'],
        ['label' => 'systemreport_is_offline', 'text' => 'Rapport offline gezet'],
        ['label' => 'systemreport_is_online', 'text' => 'Rapport online gezet'],
        ['label' => 'systemreport_no_system', 'text' => 'Er zijn geen rapporten om weer te geven'],
        ['label' => 'systemreport_delete', 'text' => 'Verwijder Rapport'],
        ['label' => 'systemreport_edit', 'text' => 'Bewerk Rapport'],
        ['label' => 'systemreport_set_offline', 'text' => 'Rapport offline zetten'],
        ['label' => 'systemreport_set_online', 'text' => 'Rapport online zetten'],
        ['label' => 'systemreport_add', 'text' => 'Rapport toevoegen'],
        ['label' => 'systemreport_add_tooltip', 'text' => 'Nieuw Rapport toevoegen'],
        ['label' => 'systemreport_all', 'text' => 'Alle systemen'],
        ['label' => 'systemreport_filter', 'text' => 'Filter systemen'],
        ['label' => 'systems_type', 'text' => 'Type'],
        ['label' => 'systemtype_select_tooltip', 'text' => 'Selecteer type'],

    ],
    'en' => [

        ['label' => 'system_not_deletable', 'text' => 'Systeem is not deletable'],
        ['label' => 'systems_menu', 'text' => 'System'],
        ['label' => 'system_not_deleted', 'text' => 'System cannot be deleted'],
        ['label' => 'system_not_saved', 'text' => 'System is not saved, not all fields are (correctly) filled in'],
        ['label' => 'system_saved', 'text' => 'System is saved'],
        ['label' => 'system_not_edited', 'text' => 'System cannot be edited'],
        ['label' => 'system_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'system_content', 'text' => 'Content'],
        ['label' => 'system_video_warning', 'text' => 'Videolinks can be added after the item is saved'],
        ['label' => 'system_links_warning', 'text' => 'Links can be added after the item is saved'],
        ['label' => 'system_files_warning', 'text' => 'Files can be uploaded after the item page is saved'],
        ['label' => 'system_images_warning', 'text' => 'Images can be uploaded after the item is saved'],
        ['label' => 'system_content_tooltip', 'text' => 'Enter the text.'],
        ['label' => 'system_intro_tooltip', 'text'  => 'Fill in here a short introduction text.'],
        ['label' => 'system_intro', 'text' => 'Intro (short intro text)'],
        ['label' => 'system_enter_title_tooltip', 'text' => 'Fill in the title of the system'],
        ['label' => 'system_title_tooltip', 'text' => 'The title of the system'],
        ['label' => 'system_set_online_tooltip', 'text' => 'Place the system online or offline'],
        ['label' => 'system', 'text' => 'System'],
        ['label' => 'system_not_changed', 'text' => 'System not changed'],
        ['label' => 'system_is_offline', 'text' => 'System placed offline'],
        ['label' => 'system_is_online', 'text' => 'System placed online'],
        ['label' => 'system_no_system', 'text' => 'There are no systems to display'],
        ['label' => 'system_delete', 'text' => 'Delete system'],
        ['label' => 'system_edit', 'text' => 'Edit system'],
        ['label' => 'system_set_offline', 'text' => 'Set system offline'],
        ['label' => 'system_set_online', 'text' => 'Set system online'],
        ['label' => 'system_add', 'text' => 'Add system'],
        ['label' => 'system_add_tooltip', 'text' => 'Add a system'],
        ['label' => 'system_all', 'text' => 'All systems'],
        ['label' => 'system_filter', 'text' => 'Filter systems'],
        ['label' => 'systems_type', 'text' => 'Type'],
    ]
];

// site translations (front end)
$aNeededSiteTranslations = [

];

if (!$oDb->tableExists('systems')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `systems`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `systems` (
          `systemId` int(11) NOT NULL AUTO_INCREMENT,
          `locationId` int(11) DEFAULT NULL,
          `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `model` varchar(50) COLLATE utf8_unicode_ci,
          `machineNr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          `notice` text COLLATE utf8_unicode_ci,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`systemId`),
          KEY `systems_locationId` (`locationId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}


if ($oDb->tableExists('systems') && $oDb->tableExists('locations')) {
    // check catalog_product_property_types constraint
    if (!$oDb->constraintExists('systems', 'locationId', 'locations', 'locationId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `systems`.`locationId` => `locations`.`locationId`';
        if ($bInstall) {
            $oDb->addConstraint('systems', 'locationId', 'locations', 'locationId', 'CASCADE', 'CASCADE');
        }
    }
}


if (!$oDb->tableExists('system_reports')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `system_reports`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `system_reports` (
          `systemReportId` int(11) NOT NULL AUTO_INCREMENT,
          `systemId` int(11) NOT NULL,
          `columnA` varchar(100) COLLATE utf8_unicode_ci NULL,
          `faseA` decimal(10,4) NULL,
          `faseB` decimal(10,4) NULL,
          `faseC` decimal(10,4) NULL,
          `notice` varchar(255) COLLATE utf8_unicode_ci,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`systemReportId`),
          KEY `system_reports_systemId` (`systemId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
} else {

    if (!$oDb->columnExists('system_reports', 'userId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing columns `system_reports`.`userId`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `system_reports` ADD `userId` INT NULL DEFAULT NULL AFTER `systemId`, ADD INDEX (`userId`);";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->columnExists('system_reports', 'wideInfo')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing columns `system_reports`.`wideInfo`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `system_reports` ADD `wideInfo` VARCHAR(100) NULL DEFAULT NULL AFTER `userId`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
    if (!$oDb->columnExists('system_reports', 'parentId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing columns `system_reports`.`parentId`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `system_reports` ADD `parentId` INT NULL DEFAULT NULL AFTER `systemReportId`, ADD INDEX (`parentId`);";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->columnExists('systems', 'deleted')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `systems`.`deleted`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `systems` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `online`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->columnExists('system_reports', 'deleted')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `system_reports`.`deleted`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `system_reports` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `userId`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->constraintExists('system_reports', 'parentId', 'system_reports', 'systemReportId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `system_reports`.`parentId` => `system_reports`.`systemReportId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports', 'parentId', 'system_reports', 'systemReportId', 'CASCADE', 'CASCADE');
        }
    }


    if (!$oDb->constraintExists('system_reports', 'userId', 'users', 'userId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `system_reports`.`userId` => `users`.`userId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports', 'userId', 'users', 'userId', 'SET NULL', 'CASCADE');
        }
    }

    if (!$oDb->columnExists('system_reports', 'faseD')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing columns `system_reports`.`faseD`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `system_reports` ADD `faseD` decimal(10,1) NULL DEFAULT NULL AFTER `faseC`;";
            $oDb->query($sQuery, QRY_NORESULT);
            $sQuery = "ALTER TABLE `system_reports` ADD `faseE` decimal(10,1) NULL DEFAULT NULL AFTER `faseD`;";
            $oDb->query($sQuery, QRY_NORESULT);
            $sQuery = "ALTER TABLE `system_reports` ADD `faseF` decimal(10,1) NULL DEFAULT NULL AFTER `faseE`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    // add system report page
    
    if ($oDb->tableExists('pages') && !($oPageSystemReports = PageManager::getPageByName('system_reports', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `system_reports`';
        if ($bInstall) {
            $oPageSystemReports               = new Page();
            $oPageSystemReports->languageId   = DEFAULT_LANGUAGE_ID;
    
            $oPageSystemReports->name         = 'system_reports';
            $oPageSystemReports->title        = 'Onderhoudsformulieren';
            $oPageSystemReports->content      = '<p>Hier vindt u uw onderhoudsformulieren.</p>';
            $oPageSystemReports->shortTitle   = 'Onderhoudsformulieren';
            $oPageSystemReports->forceUrlPath('/oh-formulieren');
            $oPageSystemReports->setControllerPath('/modules/systems/site/controllers/systemReport.cont.php');
            $oPageSystemReports->setInMenu(1);
            $oPageSystemReports->setIndexable(0);
            $oPageSystemReports->setOnlineChangeable(0);
            $oPageSystemReports->setDeletable(0);
            $oPageSystemReports->setMayHaveSub(0);
            $oPageSystemReports->setLockUrlPath(1);
            $oPageSystemReports->setLockParent(1);
            $oPageSystemReports->setHideImageManagement(1);
            $oPageSystemReports->setHideFileManagement(1);
            $oPageSystemReports->setHideLinkManagement(1);
            $oPageSystemReports->setHideVideoLinkManagement(1);
            if ($oPageSystemReports->isValid()) {
                PageManager::savePage($oPageSystemReports);
            } else {
                _d($oPageSystemReports->getInvalidProps());
                die('Can\'t create page `system_reports`');
            }
        }
    }
  


}


if ($oDb->tableExists('systems') && $oDb->tableExists('system_reports')) {
    // check catalog_product_property_types constraint
    if (!$oDb->constraintExists('system_reports', 'systemId', 'systems', 'systemId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `system_reports`.`systemId` => `systems`.`systemId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports', 'systemId', 'systems', 'systemId', 'CASCADE', 'CASCADE');
        }
    }
}





if (moduleExists('systems')) {
    $aCheckRightFolders = [
        SystemReport::FILES_PATH => true,
        SystemReport::IMAGES_PATH => true,
    ];
}

// start file relations
if (!$oDb->tableExists('system_reports_files')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `system_reports_files`';
    if ($bInstall) {

        // add table
        $sQuery = '
            CREATE TABLE `system_reports_files` (
              `systemReportId` int(11) NOT NULL,
              `mediaId` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('system_reports_files') && $oDb->tableExists('system_reports')) {

    // check constraint
    if (!$oDb->constraintExists('system_reports_files', 'systemReportId', 'system_reports', 'systemReportId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `system_reports_files`.`systemReportId` => `system_reports`.`systemReportId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports_files', 'systemReportId', 'system_reports', 'systemReportId', 'RESTRICT', 'CASCADE');
        }
    }

    // check files constraint
    if (!$oDb->constraintExists('system_reports_files', 'mediaId', 'files', 'mediaId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `systems_files`.`mediaId` => `files`.`mediaId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports_files', 'mediaId', 'files', 'mediaId', 'CASCADE', 'CASCADE');
        }
    }
}
// check folders existance and writing rights
if (moduleExists('systems')) {
    // get settings for module and template and create all images folders
    $aImageSettings = TemplateSettings::get('system_reports', 'images');
    if (!empty($aImageSettings['imagesPath'])) {
        // set main images folder
        $aCheckRightFolders[$aImageSettings['imagesPath']] = true;
        if (!empty($aImageSettings['sizes'])) {
            foreach ($aImageSettings['sizes'] AS $sReference => $aSizeData) {
                // set image file folders
                $aCheckRightFolders[$aImageSettings['imagesPath'] . '/' . $sReference] = true;
            }
        }
    }

    if (!$oDb->tableExists('system_types')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `system_types`';
        if ($bInstall) {

            $sQuery =
                "

CREATE TABLE `system_types` (
  `systemTypeId` int(11) NOT NULL,
  `typeName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
            $oDb->query($sQuery, QRY_NORESULT);

            $sQuery =
            "
INSERT INTO `system_types` (`systemTypeId`, `typeName`) VALUES
(2, 'MultiLiner'),
(1, 'PowerLiner'),
(3, 'V-Liner');
";
            $oDb->query($sQuery, QRY_NORESULT);
            $sQuery =
            "
ALTER TABLE `system_types`
  ADD PRIMARY KEY (`systemTypeId`);
";
            $oDb->query($sQuery, QRY_NORESULT);
            $sQuery =
            "
ALTER TABLE `system_types`
  MODIFY `systemTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;";

            $oDb->query($sQuery, QRY_NORESULT);
        }
    } else {

        if (!$oDb->columnExists('systems', 'systemTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing column `systems`.`systemTypeId`';
            if ($bInstall) {
                $sQuery = "ALTER TABLE `systems` ADD `systemTypeId` INT NULL DEFAULT NULL AFTER `locationId`, ADD INDEX (`systemTypeId`);";
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }

        if (!$oDb->constraintExists('systems', 'systemTypeId', 'system_types', 'systemTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `systems`.`systemTypeId` => `system_types`.`systemTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('systems', 'systemTypeId', 'system_types', 'systemTypeId', 'RESTRICT', 'CASCADE');
            }

            $sQuery = 'UPDATE `systems` SET `systemTypeId` = 2 WHERE `systemTypeId` IS NULL;';
            $oDb->query($sQuery, QRY_NORESULT);
        }

        if (!$oDb->columnExists('systems', 'floor')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing column `systems`.`floor`';
            if ($bInstall) {
                $sQuery = "ALTER TABLE `systems` ADD `floor` VARCHAR(75) NULL DEFAULT NULL AFTER `systemId`;";
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }

        if (!$oDb->columnExists('systems', 'installDate')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing column `systems`.`installDate`';
            if ($bInstall) {
                $sQuery = "ALTER TABLE `systems` ADD `installDate` timestamp NULL DEFAULT NULL AFTER `machineNr`;";
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }


    }

}


// start image relations
if (!$oDb->tableExists('system_reports_images')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `system_reports_images`';
    if ($bInstall) {

        // add table
        $sQuery = '
            CREATE TABLE `system_reports_images` (
              `systemReportId` int(11) NOT NULL,
              `imageId` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('system_reports_images') && $oDb->tableExists('system_reports')) {

    // check  constraint
    if (!$oDb->constraintExists('system_reports_images', 'systemReportId', 'system_reports', 'systemReportId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `system_reports_images`.`systemReportId` => `system_reports`.`systemReportId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports_images', 'systemReportId', 'system_reports', 'systemReportId', 'RESTRICT', 'CASCADE');
        }
    }

    // check images constraint
    if (!$oDb->constraintExists('system_reports_images', 'imageId', 'images', 'imageId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `system_reports_images`.`imageId` => `images`.`imageId`';
        if ($bInstall) {
            $oDb->addConstraint('system_reports_images', 'imageId', 'images', 'imageId', 'CASCADE', 'CASCADE');
        }
    }

    $aFilter['q'] = 'CV';
    $aSystemTypes = SystemTypeManager::getSystemTypesByFilter($aFilter);
    if (count($aSystemTypes)==1) {
        $aLogs[$sModuleName]['errors'][] = 'Need to rename CV to Actief Harmonisch Filter (AHF)';
  
        if ($bInstall) {
            $aSystemTypes[0]->typeName = 'Actief Harmonisch Filter (AHF)';
            SystemTypeManager::saveSystemType($aSystemTypes[0]);
        }
    }
}

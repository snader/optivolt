<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'planning' => [
        'module'     => 'loggers',
        'controller' => 'planning',
    ],
    'loggers' => [
        'module'     => 'loggers',
        'controller' => 'logger',
    ],
    'loggersdagen'        => [
        'module'     => 'loggers',
        'controller' => 'loggerDays',
    ],
    'loggersdefaults'        => [
        'module'     => 'loggers',
        'controller' => 'loggerDefaults',
    ],

];

$aNeededClassRoutes = [
    'Logger'        => [
        'module' => 'loggers',
    ],
    'LoggerManager' => [
        'module' => 'loggers',
    ],
    'LoggersDay'        => [
        'module' => 'loggers',
    ],
    'LoggersDaysManager' => [
        'module' => 'loggers',
    ],
    'LoggersDefault'        => [
        'module' => 'loggers',
    ],
    'LoggersDefaultsManager' => [
        'module' => 'loggers',
    ],
    'Planning'        => [
        'module' => 'loggers',
    ],
    'PlanningManager' => [
        'module' => 'loggers',
    ]
];

$aNeededModulesForMenu = [

    [
        'name'          => 'loggers',
        'icon'          => 'fa-suitcase',
        'linkName'      => 'loggers_overview',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'loggers_full'],
        ]
    ],
    [
        'name'          => 'planning',
        'icon'          => 'fa-calendar',
        'linkName'      => 'loggers_planning',
        'parentModuleName' => 'loggers',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'loggersPlanning_full'],
        ]
    ],

    [
        'name'          => 'loggersdagen',
        'icon'          => 'fa-calendar',
        'linkName'      => 'loggersdagen_menu',
        'parentModuleName' => 'loggers',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'loggersDays_full'],
        ],
    ],
    [
        'name'          => 'loggersdefaults',
        'icon'          => 'fa-thumbtack',
        'linkName'      => 'loggersdefaults_menu',
        'parentModuleName' => 'loggers',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'loggersDefaults_full'],
        ],
    ]
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'to_overview', 'text' => 'Naar overzicht'],

        ['label' => 'planning_saved', 'text' => 'Planning opgeslagen'],
        ['label' => 'planning_not_saved', 'text' => 'Planning kon niet worden opgeslagen'],

        ['label' => 'loggers_planning', 'text' => 'Planning'],
        ['label' => 'loggers_overview', 'text' => 'Overzicht'],
        ['label' => 'loggers_collapse', 'text' => 'Loggers'],
        ['label' => 'loggersdagen_full', 'text' => 'Uitzonderingsdagen'],
        ['label' => 'loggersdagen_menu', 'text' => 'Uitzonderingsdagen'],
        ['label' => 'loggersdefaults_full', 'text' => 'Logger defaults'],
        ['label' => 'loggersdefaults_menu', 'text' => 'Logger defaults'],
        ['label' => 'loggersplanning_full', 'text' => 'Planning'],
        ['label' => 'loggers_full', 'text' => 'Loggers'],

        ['label' => 'logger_not_deletable', 'text' => 'Logger is niet verwijderbaar'],
        ['label' => 'loggers_menu', 'text' => 'Loggers'],
        ['label' => 'logger_deleted', 'text' => 'Logger is verwijderd'],
        ['label' => 'logger_not_deleted', 'text' => 'Logger kan niet worden verwijderd'],
        ['label' => 'logger_not_saved', 'text' => 'Logger is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'logger_saved', 'text' => 'Logger is opgeslagen'],
        ['label' => 'logger_not_edited', 'text' => 'Logger kan niet worden bewerkt'],
        ['label' => 'logger_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'logger_enter_title_tooltip', 'text' => 'Vul de titel in'],
        ['label' => 'logger_title_tooltip', 'text' => 'De titel van uw item'],
        ['label' => 'logger_set_online_tooltip', 'text' => 'Zet het logger online OF offline'],
        ['label' => 'logger', 'text' => 'Logger'],
        ['label' => 'logger_not_changed', 'text' => 'Logger niet gewijzigd'],
        ['label' => 'logger_is_offline', 'text' => 'Logger offline gezet'],
        ['label' => 'logger_is_online', 'text' => 'Logger online gezet'],
        ['label' => 'logger_no_logger', 'text' => 'Er zijn geen loggers om weer te geven'],
        ['label' => 'logger_delete', 'text' => 'Verwijder logger'],
        ['label' => 'logger_edit', 'text' => 'Bewerk logger'],
        ['label' => 'logger_set_offline', 'text' => 'Logger offline zetten'],
        ['label' => 'logger_set_online', 'text' => 'Logger online zetten'],
        ['label' => 'logger_add', 'text' => 'Logger toevoegen'],
        ['label' => 'logger_add_tooltip', 'text' => 'Nieuw logger toevoegen'],
        ['label' => 'logger_all', 'text' => 'Alle loggers'],
        ['label' => 'logger_filter', 'text' => 'Filter loggers'],
    ],
    'en' => [
        ['label' => 'logger_not_deletable', 'text' => 'Logger is not deletable'],
        ['label' => 'loggers_menu', 'text' => 'Logger'],
        ['label' => 'logger_not_deleted', 'text' => 'Logger cannot be deleted'],
        ['label' => 'logger_not_saved', 'text' => 'Logger is not saved, not all fields are (correctly) filled in'],
        ['label' => 'logger_saved', 'text' => 'Logger is saved'],
        ['label' => 'logger_not_edited', 'text' => 'Logger cannot be edited'],
        ['label' => 'logger_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'logger_enter_title_tooltip', 'text' => 'Fill in the title of the logger'],
        ['label' => 'logger_title_tooltip', 'text' => 'The title of the logger'],
        ['label' => 'logger_set_online_tooltip', 'text' => 'Place the logger online or offline'],
        ['label' => 'logger', 'text' => 'Logger'],
        ['label' => 'logger_not_changed', 'text' => 'Logger not changed'],
        ['label' => 'logger_is_offline', 'text' => 'Logger placed offline'],
        ['label' => 'logger_is_online', 'text' => 'Logger placed online'],
        ['label' => 'logger_no_logger', 'text' => 'There are no loggers to display'],
        ['label' => 'logger_delete', 'text' => 'Delete logger'],
        ['label' => 'logger_edit', 'text' => 'Edit logger'],
        ['label' => 'logger_set_offline', 'text' => 'Set logger offline'],
        ['label' => 'logger_set_online', 'text' => 'Set logger online'],
        ['label' => 'logger_add', 'text' => 'Add logger'],
        ['label' => 'logger_add_tooltip', 'text' => 'Add a logger'],
        ['label' => 'logger_all', 'text' => 'All loggers'],
        ['label' => 'logger_filter', 'text' => 'Filter loggers'],
    ]
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [


        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_back_to_overview', 'text' => 'Terug naar het overzicht', 'editable' => 1]
    ],
];

if (!$oDb->tableExists('loggers')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `loggers`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `loggers` (
          `loggerId` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `availableFrom` timestamp NULL DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`loggerId`),
          UNIQUE KEY `u_loggers_name` (`name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('loggers')) {

    if (!$oDb->columnExists('loggers', 'deleted')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `loggers`.`deleted`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `loggers` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `availableFrom`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->tableExists('logger_defaults')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `logger_defaults`';
        if ($bInstall) {
            // add table
            $sQuery = '
        CREATE TABLE `logger_defaults` (
          `loggerDefaultId` int(11) NOT NULL AUTO_INCREMENT,
          `days` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `specialDayWarning` tinyint(1) NOT NULL DEFAULT \'0\',
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          PRIMARY KEY (`loggerDefaultId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
            $oDb->query($sQuery, QRY_NORESULT);
        }

        $sQuery = "INSERT INTO `logger_defaults` (`loggerDefaultId`, `days`, `name`, `specialDayWarning`, `online`, `order`) VALUES (NULL, '1', 'Opleveren (1 dag)', '0', '1', '88888'), (NULL, '8', 'Logger (7 dagen + 1 dag brengen/ophalen)', '1', '1', '99999');";
        $oDb->query($sQuery, QRY_NORESULT);
    }

    if (!$oDb->tableExists('logger_days')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `logger_days`';
        if ($bInstall) {
            // add table
            $sQuery = '
            CREATE TABLE `logger_days` (
            `loggerDayId` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `dayNumber` int(11) NULL DEFAULT NULL,
            `date` timestamp NULL DEFAULT NULL,
            `online` tinyint(1) NOT NULL DEFAULT \'1\',
            PRIMARY KEY (`loggerDayId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
            $oDb->query($sQuery, QRY_NORESULT);
        }

        $sQuery = "INSERT INTO `logger_days` (`loggerDayId`, `name`, `dayNumber`, `date`, `online`) VALUES (NULL, 'Zaterdag', '6', NULL, '1'), (NULL, 'Zondag', '0', NULL, '1');";
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('loggers') && $oDb->tableExists('customers')) {

    if (!$oDb->tableExists('planning')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `planning`';
        if ($bInstall) {
            // add table
            $sQuery = '
        CREATE TABLE `planning` (
          `planningId` int(11) NOT NULL AUTO_INCREMENT,
          `loggerId` int(11) NOT NULL,
          `customerId` int(11) NOT NULL,
          `startDate` DATE,
          `endDate` DATE,
          `days` int(11) DEFAULT  \'0\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`planningId`),
          KEY `loggerId` (`loggerId`),
          KEY `customerId` (`customerId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    } else {

        if (!$oDb->tableExists('planning_users')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing table `planning_users`';
            if ($bInstall) {
                // add table
                $sQuery = '
                CREATE TABLE `planning_users` (
                `planningId` int(11) NOT NULL,
                `userId` int(11) NOT NULL,
                PRIMARY KEY (`planningId`, userId),
                KEY `planningId` (`planningId`),
                KEY `userId` (`userId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ';
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }
    }


    if ($oDb->tableExists('planning')) {
        // check loggers constraint
        if (!$oDb->constraintExists('planning', 'loggerId', 'loggers', 'loggerId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `planning`.`loggerId` => `loggers`.`loggerId`';
            if ($bInstall) {
                $oDb->addConstraint('planning', 'loggerId', 'loggers', 'loggerId', 'CASCADE', 'CASCADE');
            }
        }
        // check loggers constraint
        if (!$oDb->constraintExists('planning', 'customerId', 'customers', 'customerId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `planning`.`customerId` => `customers`.`customerId`';
            if ($bInstall) {
                $oDb->addConstraint('planning', 'customerId', 'customers', 'customerId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('planning_users')) {

        // check loggers constraint
        if (!$oDb->constraintExists('planning_users', 'planningId', 'planning', 'planningId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `planning_users`.`planningId` => `planning`.`planningId`';
            if ($bInstall) {
                $oDb->addConstraint('planning_users', 'planningId', 'planning', 'planningId', 'CASCADE', 'CASCADE');
            }
        }
        // check loggers constraint
        if (!$oDb->constraintExists('planning_users', 'userId', 'users', 'userId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `planning_users`.`userId` => `users`.`userId`';
            if ($bInstall) {
                $oDb->addConstraint('planning_users', 'userId', 'users', 'userId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->columnExists('loggers', 'deleted')) {
        $aLoggers = LoggerManager::getLoggersByFilter();
        if (empty($aLoggers)) {

            $aLogs[$sModuleName]['errors'][] = 'Missing data in table `loggers`';
            if ($bInstall) {

                $sQuery = "
            INSERT INTO `loggers` (`loggerId`, `name`, `online`, `order`, `availableFrom`, `created`, `modified`) VALUES (NULL, 'A', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'B', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'C', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'D', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'E', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'F', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'G', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'H', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'I', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'J', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'K', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'L', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'M', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'N', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'O', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'P', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'Q', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'R', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'S', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'T', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'U', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'V', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'W', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'X', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'Y', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'Z', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'AA', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'AB', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'AC', '1', '99999', '2022-04-21 12:00:19', NULL, NULL)
            ,(NULL, 'AD', '1', '99999', '2022-04-21 12:00:19', NULL, NULL);


            ";
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }
    }


    if (!$oDb->columnExists('users', 'accountmanager')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `users`.`accountmanager`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `users` ADD `accountmanager` TINYINT(1) NOT NULL DEFAULT '0' AFTER `administrator`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    

    if (!$oDb->columnExists('planning', 'color')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `planning`.`color`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `planning` ADD `color` INT(9) DEFAULT NULL AFTER `customerId`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->columnExists('planning', 'comment')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `planning`.`comment`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `planning` ADD `comment` varchar(255) DEFAULT NULL AFTER `customerId`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if (!$oDb->columnExists('planning', 'parentPlanningId')) {

        $aLogs[$sModuleName]['errors'][] = 'Missing column `planning`.`parentPlanningId`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `planning` ADD `parentPlanningId` INT(11) DEFAULT NULL AFTER `planningId`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }

        // check parentPlanningId constraint
        if (!$oDb->constraintExists('planning', 'parentPlanningId', 'planning', 'planningId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `planning`.`parentPlanningId` => `planning`.`planningId`';
            if ($bInstall) {
                $oDb->addConstraint('planning', 'parentPlanningId', 'planning', 'planningId', 'CASCADE', 'CASCADE');
            }
        }
    }


}


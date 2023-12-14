<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'logs' => [
        'module'     => 'logs',
        'controller' => 'log',
    ],
    
];

$aNeededClassRoutes = [
    'Log'        => [
        'module' => 'logs',
    ],
    'LogManager' => [
        'module' => 'logs',
    ]
];

$aNeededModulesForMenu = [
    [
        'name'          => 'logs',
        'icon'          => 'fa-flag',
        'linkName'      => 'logs_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'logs_full'],
        ]
    ]
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'log_not_deletable', 'text' => 'Log is niet verwijderbaar'],
        ['label' => 'logs_menu', 'text' => 'Logs'],
        ['label' => 'log_deleted', 'text' => 'Log is verwijderd'],
        ['label' => 'log_not_deleted', 'text' => 'Log kan niet worden verwijderd'],
        ['label' => 'log_not_saved', 'text' => 'Log is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'log_saved', 'text' => 'Log is opgeslagen'],
        ['label' => 'log_not_edited', 'text' => 'Log kan niet worden bewerkt'],
        ['label' => 'log_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'log_content', 'text' => 'Content'],
        ['label' => 'log_video_warning', 'text' => "Video's kunnen worden toegevoegd nadat het item eerst is opgeslagen"],
        ['label' => 'log_links_warning', 'text' => 'Links kunnen worden toegevoegd nadat het item eerst is opgeslagen'],
        ['label' => 'log_files_warning', 'text' => 'Bestanden kunnen worden geüpload nadat het item eerst is opgeslagen'],
        ['label' => 'log_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat het item eerst is opgeslagen'],
        ['label' => 'log_content_tooltip', 'text' => 'Vul hier uw content in.'],
        ['label' => 'log_intro_tooltip', 'text'  => 'Vul hier een korte introductie tekst in.'],
        ['label' => 'log_intro', 'text' => 'Intro (korte intro tekst)'],
        ['label' => 'log_enter_title_tooltip', 'text' => 'Vul de titel in'],
        ['label' => 'log_title_tooltip', 'text' => 'De titel van uw item'],
        ['label' => 'log_set_online_tooltip', 'text' => 'Zet het log online OF offline'],
        ['label' => 'log', 'text' => 'Log'],
        ['label' => 'log_not_changed', 'text' => 'Log niet gewijzigd'],
        ['label' => 'log_is_offline', 'text' => 'Log offline gezet'],
        ['label' => 'log_is_online', 'text' => 'Log online gezet'],
        ['label' => 'log_no_log', 'text' => 'Er zijn geen logs om weer te geven'],
        ['label' => 'log_delete', 'text' => 'Verwijder log'],
        ['label' => 'log_edit', 'text' => 'Bewerk log'],
        ['label' => 'log_set_offline', 'text' => 'Log offline zetten'],
        ['label' => 'log_set_online', 'text' => 'Log online zetten'],
        ['label' => 'log_add', 'text' => 'Log toevoegen'],
        ['label' => 'log_add_tooltip', 'text' => 'Nieuw log toevoegen'],
        ['label' => 'log_all', 'text' => 'Alle logs'],
        ['label' => 'log_filter', 'text' => 'Filter logs'],
    ],
    'en' => [
        ['label' => 'log_not_deletable', 'text' => 'Log is not deletable'],
        ['label' => 'logs_menu', 'text' => 'Log'],
        ['label' => 'log_not_deleted', 'text' => 'Log cannot be deleted'],
        ['label' => 'log_not_saved', 'text' => 'Log is not saved, not all fields are (correctly) filled in'],
        ['label' => 'log_saved', 'text' => 'Log is saved'],
        ['label' => 'log_not_edited', 'text' => 'Log cannot be edited'],
        ['label' => 'log_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'log_content', 'text' => 'Content'],
        ['label' => 'log_video_warning', 'text' => 'Videolinks can be added after the item is saved'],
        ['label' => 'log_links_warning', 'text' => 'Links can be added after the item is saved'],
        ['label' => 'log_files_warning', 'text' => 'Files can be uploaded after the item page is saved'],
        ['label' => 'log_images_warning', 'text' => 'Images can be uploaded after the item is saved'],
        ['label' => 'log_content_tooltip', 'text' => 'Enter the text.'],
        ['label' => 'log_intro_tooltip', 'text'  => 'Fill in here a short introduction text.'],
        ['label' => 'log_intro', 'text' => 'Intro (short intro text)'],
        ['label' => 'log_enter_title_tooltip', 'text' => 'Fill in the title of the log'],
        ['label' => 'log_title_tooltip', 'text' => 'The title of the log'],
        ['label' => 'log_set_online_tooltip', 'text' => 'Place the log online or offline'],
        ['label' => 'log', 'text' => 'Log'],
        ['label' => 'log_not_changed', 'text' => 'Log not changed'],
        ['label' => 'log_is_offline', 'text' => 'Log placed offline'],
        ['label' => 'log_is_online', 'text' => 'Log placed online'],
        ['label' => 'log_no_log', 'text' => 'There are no logs to display'],
        ['label' => 'log_delete', 'text' => 'Delete log'],
        ['label' => 'log_edit', 'text' => 'Edit log'],
        ['label' => 'log_set_offline', 'text' => 'Set log offline'],
        ['label' => 'log_set_online', 'text' => 'Set log online'],
        ['label' => 'log_add', 'text' => 'Add log'],
        ['label' => 'log_add_tooltip', 'text' => 'Add a log'],
        ['label' => 'log_all', 'text' => 'All logs'],
        ['label' => 'log_filter', 'text' => 'Filter logs'],
    ]
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        
        
        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_back_to_overview', 'text' => 'Terug naar het overzicht', 'editable' => 1]
    ],
];

if (!$oDb->tableExists('logs')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `logs`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `logs` (
          `logId` int(11) NOT NULL AUTO_INCREMENT,
          `userId` int(11) NULL DEFAULT NULL,
          
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `name` text COLLATE utf8_unicode_ci,
          `link` text COLLATE utf8_unicode_ci,
          `content` text COLLATE utf8_unicode_ci,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`logId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}




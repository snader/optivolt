<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'devices' => [
        'module'     => 'devices',
        'controller' => 'device',
    ],
    
];

$aNeededClassRoutes = [
    'Device'        => [
        'module' => 'devices',
    ],
    'DeviceManager' => [
        'module' => 'devices',
    ]
];

$aNeededModulesForMenu = [
    [
        'name'          => 'devices',
        'icon'          => 'fa-plug',
        'linkName'      => 'devices_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'devices_full'],
        ]
    ]
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'device_not_deletable', 'text' => 'Apparaat is niet verwijderbaar'],
        ['label' => 'devices_menu', 'text' => 'Apparaten'],
        ['label' => 'device_deleted', 'text' => 'Apparaat is verwijderd'],
        ['label' => 'device_not_deleted', 'text' => 'Apparaat kan niet worden verwijderd'],
        ['label' => 'device_not_saved', 'text' => 'Apparaat is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'device_saved', 'text' => 'Apparaat is opgeslagen'],
        ['label' => 'device_not_edited', 'text' => 'Apparaat kan niet worden bewerkt'],
        ['label' => 'device_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'device_content', 'text' => 'Content'],
        ['label' => 'device_video_warning', 'text' => "Video's kunnen worden toegevoegd nadat het item eerst is opgeslagen"],
        ['label' => 'device_links_warning', 'text' => 'Links kunnen worden toegevoegd nadat het item eerst is opgeslagen'],
        ['label' => 'device_files_warning', 'text' => 'Bestanden kunnen worden geüpload nadat het item eerst is opgeslagen'],
        ['label' => 'device_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat het item eerst is opgeslagen'],
        ['label' => 'device_content_tooltip', 'text' => 'Vul hier uw content in.'],
        ['label' => 'device_intro_tooltip', 'text'  => 'Vul hier een korte introductie tekst in.'],
        ['label' => 'device_intro', 'text' => 'Intro (korte intro tekst)'],
        ['label' => 'device_enter_title_tooltip', 'text' => 'Vul de titel in'],
        ['label' => 'device_title_tooltip', 'text' => 'De titel van uw item'],
        ['label' => 'device_set_online_tooltip', 'text' => 'Zet het apparaat online OF offline'],
        ['label' => 'device', 'text' => 'Apparaat'],
        ['label' => 'device_not_changed', 'text' => 'Apparaat niet gewijzigd'],
        ['label' => 'device_is_offline', 'text' => 'Apparaat offline gezet'],
        ['label' => 'device_is_online', 'text' => 'Apparaat online gezet'],
        ['label' => 'device_no_device', 'text' => 'Er zijn geen apparaten om weer te geven'],
        ['label' => 'device_delete', 'text' => 'Verwijder apparaat'],
        ['label' => 'device_edit', 'text' => 'Bewerk apparaat'],
        ['label' => 'device_set_offline', 'text' => 'Apparaat offline zetten'],
        ['label' => 'device_set_online', 'text' => 'Apparaat online zetten'],
        ['label' => 'device_add', 'text' => 'Apparaat toevoegen'],
        ['label' => 'device_add_tooltip', 'text' => 'Nieuw apparaat toevoegen'],
        ['label' => 'device_all', 'text' => 'Alle apparaten'],
        ['label' => 'device_filter', 'text' => 'Filter apparaten'],
    ],
    'en' => [
        ['label' => 'device_not_deletable', 'text' => 'Device is not deletable'],
        ['label' => 'devices_menu', 'text' => 'Device'],
        ['label' => 'device_not_deleted', 'text' => 'Device cannot be deleted'],
        ['label' => 'device_not_saved', 'text' => 'Device is not saved, not all fields are (correctly) filled in'],
        ['label' => 'device_saved', 'text' => 'Device is saved'],
        ['label' => 'device_not_edited', 'text' => 'Device cannot be edited'],
        ['label' => 'device_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'device_content', 'text' => 'Content'],
        ['label' => 'device_video_warning', 'text' => 'Videolinks can be added after the item is saved'],
        ['label' => 'device_links_warning', 'text' => 'Links can be added after the item is saved'],
        ['label' => 'device_files_warning', 'text' => 'Files can be uploaded after the item page is saved'],
        ['label' => 'device_images_warning', 'text' => 'Images can be uploaded after the item is saved'],
        ['label' => 'device_content_tooltip', 'text' => 'Enter the text.'],
        ['label' => 'device_intro_tooltip', 'text'  => 'Fill in here a short introduction text.'],
        ['label' => 'device_intro', 'text' => 'Intro (short intro text)'],
        ['label' => 'device_enter_title_tooltip', 'text' => 'Fill in the title of the device'],
        ['label' => 'device_title_tooltip', 'text' => 'The title of the device'],
        ['label' => 'device_set_online_tooltip', 'text' => 'Place the device online or offline'],
        ['label' => 'device', 'text' => 'Device'],
        ['label' => 'device_not_changed', 'text' => 'Device not changed'],
        ['label' => 'device_is_offline', 'text' => 'Device placed offline'],
        ['label' => 'device_is_online', 'text' => 'Device placed online'],
        ['label' => 'device_no_device', 'text' => 'There are no devices to display'],
        ['label' => 'device_delete', 'text' => 'Delete device'],
        ['label' => 'device_edit', 'text' => 'Edit device'],
        ['label' => 'device_set_offline', 'text' => 'Set device offline'],
        ['label' => 'device_set_online', 'text' => 'Set device online'],
        ['label' => 'device_add', 'text' => 'Add device'],
        ['label' => 'device_add_tooltip', 'text' => 'Add a device'],
        ['label' => 'device_all', 'text' => 'All devices'],
        ['label' => 'device_filter', 'text' => 'Filter devices'],
    ]
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        
        
        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_back_to_overview', 'text' => 'Terug naar het overzicht', 'editable' => 1]
    ],
];

if (!$oDb->tableExists('devices')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `devices`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `devices` (
          `deviceId` int(11) NOT NULL AUTO_INCREMENT,          
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,          
          `serial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,                  
          `online` tinyint(1) NOT NULL DEFAULT \'1\',          
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`deviceId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

<?php
/** @var \DBConnection $oDb */

// check folders existance and writing rights
$aCheckRightFolders = [
    '/private_files'                                         => false,
    '/uploads'                                               => false,
    '/uploads/images'                                        => false,
    '/uploads/files'                                         => false,
    '/logs'                                                  => false,
    '/cronjobs/cronfiles'                                    => false,
    '/cronjobs/cronlocks'                                    => false,
    '/cronjobs/cronlogs'                                     => false,
    '/tmp'                                                   => false,
    SITE_THEMES_FOLDER . '/' . SITE_TEMPLATE . '/' . 'cache' => false,
    Setting::IMAGES_PATH                                     => true,
    Setting::IMAGES_PATH . '/cms_thumb'                      => true,
    Setting::IMAGES_PATH . '/original'                       => true,
    Setting::IMAGES_PATH . '/resize'                         => true,
];

// get settings for module and template and create all images folders
$aImageSettings = TemplateSettings::get('core', 'images');
if (!empty($aImageSettings['imagesPath'])) {
    // set main images folder
    $aCheckRightFolders[$aImageSettings['imagesPath']] = true;
    if (!empty($aImageSettings['sizes'])) {
        foreach ($aImageSettings['sizes'] as $sReference => $aSizeData) {
            // set image file folders
            $aCheckRightFolders[$aImageSettings['imagesPath'] . '/' . $sReference] = true;
        }
    }
}

// check dependencies
$aDependencyModules = [
    'imageManager',
    'fileManager',
    'linkManager',
    'videoLinkManager',
];

$aNeededAdminControllerRoutes = [
    ''                    => [
        'module'     => 'core',
        'controller' => 'home',
    ],
    'login'               => [
        'module'     => 'core',
        'controller' => 'login',
    ],
    'gebruikers'          => [
        'module'     => 'core',
        'controller' => 'user',
    ],
    'modules'             => [
        'module'     => 'core',
        'controller' => 'module',
    ],
    'crop'                => [
        'module'     => 'core',
        'controller' => 'crop',
    ],
    'instellingen'        => [
        'module'     => 'core',
        'controller' => 'settings',
    ],
    'system-translations' => [
        'module'     => 'core',
        'controller' => 'systemTranslation',
    ],
    'access-management'   => [
        'module'     => 'core',
        'controller' => 'accessLog',
    ],
    'locales'             => [
        'module'     => 'core',
        'controller' => 'locale',
    ],
    'languages'           => [
        'module'     => 'core',
        'controller' => 'language',
    ],
    'countries'           => [
        'module'     => 'core',
        'controller' => 'country',
    ],
    'site-translations'   => [
        'module'     => 'core',
        'controller' => 'siteTranslation',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'             => 'site-translations',
        'icon'             => 'fa-language',
        'linkName'         => 'siteTrans_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            [
                'displayName' => 'Volledig',
                'name'        => 'siteTranslations_full',
            ],
        ],
    ],
    [
        'name'             => 'languages',
        'linkName'         => 'languages',
        'icon'             => 'fa-flag',
        'parentModuleName' => 'locales',
        'moduleActions'    => [
            [
                'displayName' => 'Volledig',
                'name'        => 'languages_full',
            ],
        ],
    ],
    [
        'name'             => 'countries',
        'linkName'         => 'countries',
        'icon'             => 'fa-flag-o',
        'parentModuleName' => 'locales',
        'moduleActions'    => [
            [
                'displayName' => 'Volledig',
                'name'        => 'countries_full',
            ],
        ],
    ],
    
];

$aNeededClassRoutes = [
    'AccessLog'                => [
        'module' => 'core',
    ],
    'AccessLogManager'         => [
        'module' => 'core',
    ],
    'CronManager'              => [
        'module' => 'core',
    ],
    'CropSettings'             => [
        'module' => 'core',
    ],
    'Date'                     => [
        'module' => 'core',
    ],
    'DBConnection'             => [
        'module' => 'core',
    ],
    'DBConnections'            => [
        'module' => 'core',
    ],
    'Debug'                    => [
        'module' => 'core',
    ],
    'MailManager'              => [
        'module' => 'core',
    ],
    'Media'                    => [
        'module' => 'core',
    ],
    'Model'                    => [
        'module' => 'core',
    ],
    'Module'                   => [
        'module' => 'core',
    ],
    'ModuleManager'            => [
        'module' => 'core',
    ],
    'PageLayout'               => [
        'module' => 'core',
    ],
    'PageLayoutStylesheet'     => [
        'module' => 'core',
    ],
    'PageLayoutJavascript'     => [
        'module' => 'core',
    ],
    'Setting'                  => [
        'module' => 'core',
    ],
    'SettingManager'           => [
        'module' => 'core',
    ],
    'Settings'                 => [
        'module' => 'core',
    ],
    'SystemLanguage'           => [
        'module' => 'core',
    ],
    'SystemLanguageManager'    => [
        'module' => 'core',
    ],
    'SystemTranslation'        => [
        'module' => 'core',
    ],
    'SystemTranslationManager' => [
        'module' => 'core',
    ],
    'SysTranslation'           => [
        'module' => 'core',
    ],
    'sysTranslations'          => [
        'module' => 'core',
    ],
    'Upload'                   => [
        'module' => 'core',
    ],
    'UploadManager'            => [
        'module' => 'core',
    ],
    'User'                     => [
        'module' => 'core',
    ],
    'UserManager'              => [
        'module' => 'core',
    ],
    'UserAccessGroup'          => [
        'module' => 'core',
    ],
    'UserAccessGroupManager'   => [
        'module' => 'core',
    ],
    'ModuleAction'             => [
        'module' => 'core',
    ],
    'ModuleActionManager'      => [
        'module' => 'core',
    ],
    'ACMS\Locale'              => [
        'module' => 'core',
    ],
    'LocaleManager'            => [
        'module' => 'core',
    ],
    'Locales'                  => [
        'module' => 'core',
    ],
    'Language'                 => [
        'module' => 'core',
    ],
    'LanguageManager'          => [
        'module' => 'core',
    ],
    'Country'                  => [
        'module' => 'core',
    ],
    'CountryManager'           => [
        'module' => 'core',
    ],
    'Captcha'                  => [
        'module' => 'core',
    ],
    'ModuleRedirect'           => [
        'module' => 'core',
    ],
    'ModuleRedirectManager'    => [
        'module' => 'core',
    ],
    'VideoLinkManager'         => [
        'module' => 'core',
    ],
];

$aNeededSiteControllerRoutes = [
    'uploads'        => [
        'module'     => 'core',
        'controller' => 'uploads',
    ],
    'robots'         => [
        'module'     => 'core',
        'controller' => 'robots',
    ],
    'change-locale'  => [
        'module'     => 'core',
        'controller' => 'changeLocale',
    ],
    'webhook'        => [
        'module'     => 'core',
        'controller' => 'webhook',
    ],
    'dominant-color' => [
        'module'     => 'core',
        'controller' => 'dominant-color',
    ],
    'csp-reports' => [
        'module' => 'core',
        'controller' => 'CspReports',
    ],
];

$aNeededTranslations = [
    'en' => [
        [
            'label' => 'locale_set_online',
            'text'  => 'Set locale online or offline',
        ],
        [
            'label' => 'locales_languageId',
            'text'  => 'Language',
        ],
        [
            'label' => 'locales_countryId',
            'text'  => 'Country',
        ],
        [
            'label' => 'global_domain',
            'text'  => 'Domain',
        ],
        [
            'label' => 'locales_set_domain',
            'text'  => 'Enter a domain name',
        ],
        [
            'label' => 'locales_set_subdomain',
            'text'  => 'Enter a subdomain name',
        ],
        [
            'label' => 'locales_set_countryId',
            'text'  => 'Select a country',
        ],
        [
            'label' => 'locales_set_languageId',
            'text'  => 'Select a language',
        ],
        [
            'label' => 'locales_subdomain',
            'text'  => 'Sub domain',
        ],
        [
            'label' => 'locales_language',
            'text'  => 'Language',
        ],
        [
            'label' => 'locales_country',
            'text'  => 'Country',
        ],
        [
            'label' => 'locales_no_subdomain',
            'text'  => 'No subdomain',
        ],
        [
            'label' => 'locales_prefix1',
            'text'  => 'Prefix 1',
        ],
        [
            'label' => 'locales_no_prefix',
            'text'  => 'No prefix',
        ],
        [
            'label' => 'global_prefix2',
            'text'  => 'Prefix 2',
        ],
        [
            'label' => 'locales_URLFormat',
            'text'  => 'URL format',
        ],
        [
            'label' => 'global_readonly',
            'text'  => 'read only',
        ],
        [
            'label' => 'locales_change_order',
            'text'  => 'Change locales order',
        ],
        [
            'label' => 'locales_drag',
            'text'  => 'Drag the locales to change the order',
        ],
        [
            'label' => 'locales_all_locales',
            'text'  => 'All locales',
        ],
        [
            'label' => 'locales_add',
            'text'  => 'Add locale',
        ],
        [
            'label' => 'locales_locale',
            'text'  => 'Locale',
        ],
        [
            'label' => 'locales_set_offline_tooltip',
            'text'  => 'Change locale to offline',
        ],
        [
            'label' => 'locales_set_online_tooltip',
            'text'  => 'Change locale to online',
        ],
        [
            'label' => 'locales_edit',
            'text'  => 'Edit locale',
        ],
        [
            'label' => 'locales_delete',
            'text'  => 'Delete locale',
        ],
        [
            'label' => 'locales_not_deletable',
            'text'  => 'Locale can not be deleted',
        ],
        [
            'label' => 'locales_no_locales',
            'text'  => 'No locales found',
        ],
        [
            'label' => 'locales_online',
            'text'  => 'Locale online',
        ],
        [
            'label' => 'locales_offline',
            'text'  => 'Locale offline',
        ],
        [
            'label' => 'locales_not_changed',
            'text'  => 'Locale not changed',
        ],
        [
            'label' => 'locales_locales',
            'text'  => 'Locales',
        ],
        [
            'label' => 'locales_saved',
            'text'  => 'Locale saved',
        ],
        [
            'label' => 'locales_not_saved',
            'text'  => 'Locale not saved',
        ],
        [
            'label' => 'locales_deleted',
            'text'  => 'Locale deleted',
        ],
        [
            'label' => 'locales_not_deleted',
            'text'  => 'Locale not deleted',
        ],
        [
            'label' => 'locales_url_format_already_exists',
            'text'  => 'URL-format already exists',
        ],
        [
            'label' => 'locales_language_country_combi_already_exists',
            'text'  => 'Language/country combination already exists',
        ],
        [
            'label' => 'global_country_language',
            'text'  => 'Country and language',
        ],
        [
            'label' => 'js_mfu_max_files_reached',
            'text'  => 'Maximum of %s files is reached',
        ],
        [
            'label' => 'js_mfu_failure',
            'text'  => 'Error',
        ],
        [
            'label' => 'js_mfu_waiting',
            'text'  => 'Waiting',
        ],
        [
            'label' => 'js_mfu_ready',
            'text'  => 'Ready',
        ],
        [
            'label' => 'js_mfu_uploading',
            'text'  => 'Uploading',
        ],
        [
            'label' => 'js_mfu_aborted',
            'text'  => 'Aborted',
        ],
        [
            'label' => 'js_mfu_process_and_save',
            'text'  => 'Processing and saving',
        ],
        [
            'label' => 'global_browse',
            'text'  => 'Browse',
        ],
        [
            'label' => 'global_drop_your_files_here',
            'text'  => 'or drop your files here',
        ],
        [
            'label' => 'js_mfu_no_cutout',
            'text'  => 'One of more crops are missing',
        ],
        [
            'label' => 'js_mfu_max_file_size_reached',
            'text'  => 'File larger than %s',
        ],
        [
            'label' => 'js_mfu_invalid_file_extension',
            'text'  => 'Files with extension %s are not allowed (only %s allowed)',
        ],
        [
            'label' => 'js_mfu_no_file_extension',
            'text'  => 'File extension not found',
        ],
        [
            'label' => '2_step_whitelist_ips_title',
            'text'  => 'save ip addresses with a comma between addresses (127.0.0.1,127.0.0.2)',
        ],
        [
            'label' => '2_step_whitelist_ips',
            'text'  => 'Whitelisted ip addresses',
        ],
        [
            'label' => '2_step_forced',
            'text'  => 'Force 2-step authentication',
        ],
        [
            'label' => '2_step_user_force_two_step',
            'text'  => '2-step authentication',
        ],
        [
            'label' => '2_step_authenticate_for',
            'text'  => '2-step authentication for',
        ],
        [
            'label' => '2_step_use_authenticator_app_to_scan_qr',
            'text'  => 'Use your authenticator app to scan the QR code',
        ],
        [
            'label' => '2_step_fill_in_authenticator_code',
            'text'  => 'Fill in the authenticator code',
        ],
        [
            'label' => '2_step_check_code',
            'text'  => 'Check code',
        ],
        [
            'label' => '2_step_user_authenticator_app_on_mobile',
            'text'  => 'Use the authenticator app on your mobile',
        ],
        [
            'label' => 'global_global_setting',
            'text'  => 'Global setting',
        ],
        [
            'label' => '2_step_request_new_secret',
            'text'  => 'Request new 2-step secret',
        ],
        [
            'label' => '2_step_request_new_secret',
            'text'  => 'Request a new 2-step secret',
        ],
        [
            'label' => '2_step_secret_reset_popup_title',
            'text'  => 'Reques a new 2-step secret',
        ],
        [
            'label' => '2_step_secret_reset_popup_content',
            'text'  => 'Are you sure you want to request a new secret? After generating a new secret the user needs to log out and in again to activate the new secret',
        ],
        [
            'label' => '2_step_secret_reset_popup_success',
            'text'  => 'The new secret is set. Let the user log out and in again to activate the new secret',
        ],
        [
            'label' => '2_step_secret_reset_popup_cancel',
            'text'  => 'Code request canceled',
        ],
        [
            'label' => 'sysTrans_error',
            'text'  => 'System translation NOT saved',
        ],
        [
            'label' => 'siteTrans_error',
            'text'  => 'Site translation NOT saved',
        ],
        [
            'label' => 'sysTrans_random_tooltip',
            'text'  => 'Use multiple translations by seperating them by | (pipesign). The frontend will randomly show one. For instance: xxx|yyy|zzz',
        ],
        [
            'label' => 'user_image',
            'text'  => 'User image',
        ],
        [
            'label' => 'user_settings',
            'text'  => 'edit user',
        ],
        [
            'label' => 'image_manager_not_installed_title',
            'text'  => 'Image Manager not installed',
        ],
        [
            'label' => 'image_manager_not_installed',
            'text'  => 'Install the Image Manager to get access to this functionality!',
        ],
        [
            'label' => 'user_is_activated',
            'text'  => 'User is activate',
        ],
        [
            'label' => 'user_is_deactivated',
            'text'  => 'User is blocked',
        ],
        [
            'label' => 'user_not_changed',
            'text'  => 'User status could not be changed',
        ],
        [
            'label' => 'user_deactivation_reason',
            'text'  => 'Block reason',
        ],
        [
            'label' => 'user_enter_user_deactication_reason_tooltip',
            'text'  => 'Reason for blocking',
        ],
        [
            'label' => 'user_is_deactivated_tooltip',
            'text'  => 'Activate or block user',
        ],
        [
            'label' => 'user_set_activation',
            'text'  => 'Activate users account',
        ],
        [
            'label' => 'user_set_deactivation',
            'text'  => 'Deactivate users account',
        ],
        [
            'label' => 'user_locked',
            'text'  => 'Deactivation date',
        ],
        [
            'label' => 'back_to_login',
            'text'  => 'Back to login',
        ],
        [
            'label' => 'for_language',
            'text'  => 'For language:',
        ],
        [
            'label' => 'core_languages',
            'text'  => 'Languages',
        ],
        [
            'label' => 'core_countries',
            'text'  => 'Countries',
        ],
        [
            'label' => 'languages',
            'text'  => 'languages',
        ],
        [
            'label' => 'countries',
            'text'  => 'countries',
        ],
        [
            'label' => 'settings_geo_maps_api_key',
            'text'  => 'Google Maps GEO Key',
        ],
        [
            'label' => 'user_enter_user_deactivation_reason_tooltip',
            'text'  => 'Fill in a reason for deactivating this account',
        ],
        [
            'label' => 'customergroup_add_custom_customers',
            'text'  => 'Add a customer',
        ],
        [
            'label' => 'customers_no_clients_to_add',
            'text'  => 'No customers to add',
        ],
        [
            'label' => 'dynamiccontent_adminonly_tooltip',
            'text'  => 'Choose to use this for admins only',
        ],
        [
            'label' => 'security_enabled',
            'text'  => 'Security enabled',
        ],
        [
            'label' => 'global_sequence_tooltip',
            'text'  => 'Set the sequence',
        ],
        [
            'label' => 'image_resize_is_too_big',
            'text'  => 'The value you are trying to add it too big',
        ],
        [
            'label' => 'core_template_installer',
            'text'  => 'template installer',
        ],
        [
            'label' => 'core_template_does_not_exist',
            'text'  => 'Template %1$s does not exist',
        ],
        [
            'label' => 'core_install_all',
            'text'  => 'Install all',
        ],
        [
            'label' => 'core_total_errors',
            'text'  => 'Total errors',
        ],
        [
            'label' => 'core_total_warnings',
            'text'  => 'Total warnings',
        ],
        [
            'label' => 'core_render_error',
            'text'  => 'Something went wrong while trying to display the webpage.',
        ],
        [
            'label' => 'global_video_saved',
            'text'  => 'Video saved',
        ],
        [
            'label' => 'global_video_not_saved',
            'text'  => 'Video not saved',
        ],
        [
            'label' => 'settings_hellodialog_api_tooltip',
            'text'  => 'Enter your HelloDialog api key here',
        ],
        [
            'label' => 'settings_hellodialog_ids_tooltip',
            'text'  => 'Please enter your HelloDialog group id\'s comma separated here',
        ],
        [
            'label' => 'global_video_link',
            'text'  => 'Video link',
        ],
        [
            'label' => 'settings_cxpay',
            'text'  => 'CXPay settings',
        ],
    ],
    'nl' => [
        [
            'label' => 'user_locked',
            'text'  => 'Datum deactivatie',
        ],
        [
            'label' => 'user_set_activation',
            'text'  => 'Gebruikersaccount activeren',
        ],
        [
            'label' => 'user_set_deactivation',
            'text'  => 'Gebruikersaccount deactiveren',
        ],
        [
            'label' => 'user_is_activated',
            'text'  => 'Gebruiker is geactiveerd',
        ],
        [
            'label' => 'user_is_deactivated',
            'text'  => 'Gebruiker is geblokkeerd',
        ],
        [
            'label' => 'user_not_changed',
            'text'  => 'Gebruiker status kon niet worden gewijzigd',
        ],
        [
            'label' => 'user_deactivation_reason',
            'text'  => 'Blokkeer reden',
        ],
        [
            'label' => 'user_enter_user_deactication_reason_tooltip',
            'text'  => 'Geef een reden voor blokkeren op',
        ],
        [
            'label' => 'user_is_deactivated_tooltip',
            'text'  => 'Activeer of blokkeer gebruiker',
        ],
        [
            'label' => 'locale',
            'text'  => 'Locale',
        ],
        [
            'label' => 'locale_set_online',
            'text'  => 'Zet de locale online of offline',
        ],
        [
            'label' => 'locales_languageId',
            'text'  => 'Taal',
        ],
        [
            'label' => 'locales_countryId',
            'text'  => 'Land',
        ],
        [
            'label' => 'global_domain',
            'text'  => 'Domein',
        ],
        [
            'label' => 'locales_set_domain',
            'text'  => 'Vul een domeinnaam in',
        ],
        [
            'label' => 'locales_set_countryId',
            'text'  => 'Kies een land',
        ],
        [
            'label' => 'locales_set_languageId',
            'text'  => 'Kies een taal',
        ],
        [
            'label' => 'locales_subdomain',
            'text'  => 'Subdomein',
        ],
        [
            'label' => 'locales_set_subdomain',
            'text'  => 'Vul een subdomein in',
        ],
        [
            'label' => 'locales_language',
            'text'  => 'Taal',
        ],
        [
            'label' => 'locales_country',
            'text'  => 'Land',
        ],
        [
            'label' => 'locales_no_subdomain',
            'text'  => 'Geen subdomein',
        ],
        [
            'label' => 'locales_prefix1',
            'text'  => 'Prefix 1',
        ],
        [
            'label' => 'locales_no_prefix',
            'text'  => 'Geen prefix',
        ],
        [
            'label' => 'global_prefix2',
            'text'  => 'Prefix 2',
        ],
        [
            'label' => 'locales_country',
            'text'  => 'Administración de páginas',
        ],
        [
            'label' => 'locales_URLFormat',
            'text'  => 'URL format',
        ],
        [
            'label' => 'global_readonly',
            'text'  => 'Administración de páginas',
        ],
        [
            'label' => 'locales_change_order',
            'text'  => 'Verander de locales volgorde',
        ],
        [
            'label' => 'locales_drag',
            'text'  => 'Sleep de locales om de volgorde aan te passen',
        ],
        [
            'label' => 'locales_all_locales',
            'text'  => 'Alle locales',
        ],
        [
            'label' => 'locales_add',
            'text'  => 'Locale toevoegen',
        ],
        [
            'label' => 'locales_locale',
            'text'  => 'Locale',
        ],
        [
            'label' => 'locales_set_offline_tooltip',
            'text'  => 'Zet locale offline',
        ],
        [
            'label' => 'locales_set_online_tooltip',
            'text'  => 'Zet locale online',
        ],
        [
            'label' => 'locales_edit',
            'text'  => 'Locale bewerken',
        ],
        [
            'label' => 'locales_delete',
            'text'  => 'Locale verwijderen',
        ],
        [
            'label' => 'locales_not_deletable',
            'text'  => 'Locale kan niet worden verwijderd',
        ],
        [
            'label' => 'locales_no_locales',
            'text'  => 'Geen locales gevonden',
        ],
        [
            'label' => 'locales_online',
            'text'  => 'Locale online',
        ],
        [
            'label' => 'locales_offline',
            'text'  => 'Locale offline',
        ],
        [
            'label' => 'locales_not_changed',
            'text'  => 'Locale niet gewijzigd',
        ],
        [
            'label' => 'locales_locales',
            'text'  => 'Locales',
        ],
        [
            'label' => 'locales_saved',
            'text'  => 'Locale opgeslagen',
        ],
        [
            'label' => 'locales_not_saved',
            'text'  => 'Locale niet opgeslagen',
        ],
        [
            'label' => 'locales_deleted',
            'text'  => 'Locale verwijderd',
        ],
        [
            'label' => 'locales_not_deleted',
            'text'  => 'Locale niet verwijderd',
        ],
        [
            'label' => 'locales_url_format_already_exists',
            'text'  => 'URL-format bestaat al',
        ],
        [
            'label' => 'locales_language_country_combi_already_exists',
            'text'  => 'Taal/land combinatie bestaat al',
        ],
        [
            'label' => 'global_country_language',
            'text'  => 'Land en taal',
        ],
        [
            'label' => 'settings_email_tooltip',
            'text'  => 'Vul een e-mailadres in',
        ],
        [
            'label' => 'locales_date_format',
            'text'  => 'Datum format',
        ],
        [
            'label' => 'locales_set_date_format',
            'text'  => 'Selecteer een datum format',
        ],
        [
            'label' => 'js_mfu_max_files_reached',
            'text'  => 'Maximum van %s bestanden is bereikt',
        ],
        [
            'label' => 'js_mfu_failure',
            'text'  => 'Fout',
        ],
        [
            'label' => 'js_mfu_waiting',
            'text'  => 'Wachten',
        ],
        [
            'label' => 'js_mfu_ready',
            'text'  => 'Klaar',
        ],
        [
            'label' => 'js_mfu_uploading',
            'text'  => 'Uploaden',
        ],
        [
            'label' => 'js_mfu_aborted',
            'text'  => 'Afgebroken',
        ],
        [
            'label' => 'js_mfu_process_and_save',
            'text'  => 'Verwerken en opslaan',
        ],
        [
            'label' => 'global_browse',
            'text'  => 'Bladeren',
        ],
        [
            'label' => 'global_drop_your_files_here',
            'text'  => 'of sleep je bestanden naar hier',
        ],
        [
            'label' => 'js_mfu_no_cutout',
            'text'  => 'Een of meer uitsnedes missen',
        ],
        [
            'label' => 'js_mfu_max_file_size_reached',
            'text'  => 'Bestand groter dan %s',
        ],
        [
            'label' => 'js_mfu_invalid_file_extension',
            'text'  => 'Bestanden met extensie %s zijn niet toegestaan (alleen %s is toegestaan)',
        ],
        [
            'label' => 'js_mfu_no_file_extension',
            'text'  => 'Bestand heeft geen extensie',
        ],
        [
            'label' => '2_step_whitelist_ips_title',
            'text'  => 'Sla de IP-addressen komma gescheiden op. (127.0.0.1,127.0.0.2)',
        ],
        [
            'label' => '2_step_whitelist_ips',
            'text'  => 'Veilige IP-addressen',
        ],
        [
            'label' => '2_step_forced',
            'text'  => 'Forceer 2-staps authenticatie',
        ],
        [
            'label' => '2_step_user_force_two_step',
            'text'  => '2-staps authenticatie',
        ],
        [
            'label' => '2_step_authenticate_for',
            'text'  => '2-staps authenticatie voor',
        ],
        [
            'label' => '2_step_use_authenticator_app_to_scan_qr',
            'text'  => 'Gebruik authenticatie app om de QR-code te scannen.',
        ],
        [
            'label' => '2_step_fill_in_authenticator_code',
            'text'  => 'Voer de authenticatiecode in',
        ],
        [
            'label' => '2_step_check_code',
            'text'  => 'Controleer code',
        ],
        [
            'label' => '2_step_user_authenticator_app_on_mobile',
            'text'  => 'Gebruik de authenticatieapp op uw mobiel',
        ],
        [
            'label' => 'global_global_setting',
            'text'  => 'Systeem instelling',
        ],
        [
            'label' => '2_step_request_new_secret',
            'text'  => 'Vraag nieuwe 2-staps code aan',
        ],
        [
            'label' => '2_step_secret_reset_popup_title',
            'text'  => 'Nieuwe 2-staps code aanvragen',
        ],
        [
            'label' => '2_step_secret_reset_popup_content',
            'text'  => 'Weet je zeker dat je een nieuwe code aan wilt vragen? Na het succesvol aanmaken dient de gebruiker uit- en opnieuw in te loggen om deze definitief te maken.',
        ],
        [
            'label' => '2_step_secret_reset_popup_success',
            'text'  => 'Er is een nieuwe 2-staps ingesteld. Laat de gebruiker nu uit- en opnieuw inloggen om deze definitief te maken',
        ],
        [
            'label' => '2_step_secret_reset_popup_cancel',
            'text'  => 'Code aanvraag geannuleerd',
        ],
        [
            'label' => 'dev',
            'text'  => 'DEV',
        ],
        [
            'label' => 'dev_flush',
            'text'  => 'flush',
        ],
        [
            'label' => 'dev_build',
            'text'  => 'Build',
        ],
        [
            'label' => 'user_locked_date',
            'text'  => 'Geblokkerd tot',
        ],
        [
            'label' => 'user_is_blocked',
            'text'  => 'Geblokkeerd',
        ],
        [
            'label' => 'locked_reason',
            'text'  => 'Reden blokkering:',
        ],
        [
            'label' => 'user_enter_user_locked_reason_tooltip',
            'text'  => 'Vul een reden van blokkering in',
        ],
        [
            'label' => 'instagram_client_id',
            'text'  => 'Instagram Client ID',
        ],
        [
            'label' => 'instagram_client_secret',
            'text'  => 'Client secret',
        ],
        [
            'label' => 'instagram_acces_token',
            'text'  => 'Access token',
        ],
        [
            'label' => 'settings_instagram',
            'text'  => 'Instagram',
        ],
        [
            'label' => 'settings_defaultEmailOrders_tooltip',
            'text'  => 'Vul het standaard bestellingenadres in',
        ],
        [
            'label' => 'settings_defaultEmailOrders',
            'text'  => 'Standaard bestellingenadres',
        ],
        [
            'label' => 'global_create_local_first',
            'text'  => 'Maak eerst lokaal aan',
        ],
        [
            'label' => 'siteTrans_not_deleted',
            'text'  => 'niet verwijderd',
        ],
        [
            'label' => 'setting_no_force_step',
            'text'  => 'Niet forceren',
        ],
        [
            'label' => 'sysTrans_error',
            'text'  => 'Uw systeemvertaling is niet opgeslagen',
        ],
        [
            'label' => 'siteTrans_error',
            'text'  => 'Uw sitevertaling is niet opgeslagen',
        ],
        [
            'label' => 'sysTrans_random_tooltip',
            'text'  => 'Gebruik meerdere sitevertalingen door ze te scheiden met | (pipesign). De frontend toont er dan random één. Bijvoorbeeld: xxx|yyy|zzz',
        ],
        [
            'label' => 'sysTrans_error',
            'text'  => 'Uw Systeem vertaling is niet opgeslagen',
        ],
        [
            'label' => 'siteTrans_error',
            'text'  => 'Uw site vertaling is niet opgeslagen',
        ],
        [
            'label' => 'user_image',
            'text'  => 'Gebruikers afbeelding',
        ],
        [
            'label' => 'user_settings',
            'text'  => 'gebruiker bewerken',
        ],
        [
            'label' => 'image_manager_not_installed_title',
            'text'  => 'Image Manager niet geinstalleerd',
        ],
        [
            'label' => 'image_manager_not_installed',
            'text'  => 'Installeer de Image Manager om toegang te krijgen tot deze functionaliteit!',
        ],
        [
            'label' => 'siteTrans_menu',
            'text'  => 'Sitevertalingen',
        ],
        [
            'label' => 'sysTrans_error',
            'text'  => 'Uw Systeem vertaling is niet opgeslagen',
        ],
        [
            'label' => 'siteTrans_error',
            'text'  => 'Uw site vertaling is niet opgeslagen',
        ],
        [
            'label' => 'user_account_blocked',
            'text'  => 'Uw gebruikersaccount is geblokkeerd.',
        ],
        [
            'label' => 'login_attempts',
            'text'  => 'keer verkeerd geprobeerd in te loggen.',
        ],
        [
            'label' => 'user_account_is_blocked_part1',
            'text'  => 'Uw gebruikersaccount is',
        ],
        [
            'label' => 'user_account_is_blocked_part2',
            'text'  => 'minuten geblokkeerd.',
        ],
        [
            'label' => 'back_to_login',
            'text'  => 'Terug naar login',
        ],
        [
            'label' => 'for_language',
            'text'  => 'Voor taal:',
        ],
        [
            'label' => 'global_custom_canonical',
            'text'  => 'Aangepaste canonical',
        ],
        [
            'label' => 'global_custom_canonical_tooltip',
            'text'  => 'Vul hier een volledige link in voor canonical gebruik',
        ],
        [
            'label' => 'core_languages',
            'text'  => 'Talen',
        ],
        [
            'label' => 'core_countries',
            'text'  => 'Landen',
        ],
        [
            'label' => 'languages',
            'text'  => 'Talen',
        ],
        [
            'label' => 'countries',
            'text'  => 'Landen',
        ],
        [
            'label' => 'settings_geo_maps_api_key',
            'text'  => 'Google Maps GEO Key',
        ],
        [
            'label' => 'user_enter_user_deactivation_reason_tooltip',
            'text'  => 'Vul een reden van deactivatie voor dit account',
        ],
        [
            'label' => 'customergroup_add_custom_customers',
            'text'  => 'Voeg een klant toe',
        ],
        [
            'label' => 'customers_no_clients_to_add',
            'text'  => 'Geen klanten om toe te voegen',
        ],
        [
            'label' => 'dynamiccontent_adminonly_tooltip',
            'text'  => 'zet alleen voor adminstrators ja/nee',
        ],
        [
            'label' => 'security_enabled',
            'text'  => 'Beveiliging ingeschakeld',
        ],
        [
            'label' => 'global_sequence_tooltip',
            'text'  => 'Bepaal de volgorde',
        ],
        [
            'label' => 'image_resize_is_too_big',
            'text'  => 'De waarde die u wilt toevoegen aan de afbeelding is te groot',
        ],
        [
            'label' => 'core_template_installer',
            'text'  => 'template installer',
        ],
        [
            'label' => 'core_template_does_not_exist',
            'text'  => 'Template %1$s bestaat niet',
        ],
        [
            'label' => 'core_install_all',
            'text'  => 'Alles installeren',
        ],
        [
            'label' => 'core_total_errors',
            'text'  => 'Totaal aantal errors',
        ],
        [
            'label' => 'core_total_warnings',
            'text'  => 'Totaal aantal warnings',
        ],
        [
            'label' => 'core_render_error',
            'text'  => 'Er is iets mis gegaan bij het tonen van de webpagina. Probeer het later nog eens.',
        ],
        [
            'label' => 'global_no_default_country',
            'text'  => 'Geen standaard land',
        ],
        [
            'label' => 'settings_defaultCountryId',
            'text'  => 'Standaard land',
        ],
        [
            'label' => 'global_general_settings',
            'text'  => 'Algemene instellingen',
        ],
        [
            'label' => 'global_systemName',
            'text'  => 'Systeem naam',
        ],
      
        [
            'label' => 'global_external_syncs_item_not_synced_since',
            'text'  => 'Item niet gesynchroniseerd sinds `%1$s`',
        ],
        [
            'label' => 'global_external_syncs_error',
            'text'  => 'Sync error: `%1$s`',
        ],
        [
            'label' => 'global_never',
            'text'  => 'nooit',
        ],
        [
            'label' => 'core_qa_security_headers',
            'text'  => 'Security headers',
        ],
        [
            'label' => 'core_quality_assurance_security',
            'text'  => 'Security',
        ],
        [
            'label' => 'global_video_saved',
            'text'  => 'Video opgeslagen',
        ],
        [
            'label' => 'global_video_not_saved',
            'text'  => 'Video niet opgeslagen',
        ],
        [
            'label' => 'settings_hellodialog_api_tooltip',
            'text'  => 'Vul hier uw HelloDialog api key in',
        ],
        [
            'label' => 'settings_hellodialog_ids_tooltip',
            'text'  => 'Vul hier uw HelloDialog group id\'s komma gescheiden in',
        ],
        [
            'label' => 'global_video_link',
            'text'  => 'Video link',
        ],
        [
            'label' => 'settings_cxpay',
            'text'  => 'CXPay instellingen',
        ],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        [
            'label'    => 'site_fill_in_your_email',
            'text'     => 'Vul een e-mailadres in',
            'editable' => 1,
        ],
        [
            'label'    => 'site_email',
            'text'     => 'E-mail',
            'editable' => 1,
        ],
        [
            'label'    => 'site_visit_us',
            'text'     => 'Bezoek ons',
            'editable' => 1,
        ],
        [
            'label'    => 'site_send_whatsapp',
            'text'     => 'Stuur een WhatsApp',
            'editable' => 1,
        ],
        [
            'label'    => 'site_chat_with_us',
            'text'     => 'Chat met ons',
            'editable' => 1,
        ],
        [
            'label'    => 'site_call_us',
            'text'     => 'Bel ons',
            'editable' => 1,
        ],
        [
            'label'    => 'site_send_email',
            'text'     => 'Mail ons',
            'editable' => 1,
        ],
        [
            'label'    => 'site_openings_hours',
            'text'     => 'Openingstijden',
            'editable' => 1,
        ],
        [
            'label'    => 'site_follow_us',
            'text'     => 'Volg ons',
            'editable' => 1,
        ],
        [
            'label'    => 'site_social',
            'text'     => 'Social media',
            'editable' => 1,
        ],
        [
            'label'    => 'site_created_by',
            'text'     => 'By',
            'editable' => 1,
        ],
        [
            'label'    => 'site_newsletter_intro',
            'text'     => 'Schrijf u nu in voor de nieuwsbrief',
            'editable' => 1,
        ],
        [
            'label'    => 'site_newsletter',
            'text'     => 'Nieuwsbrief',
            'editable' => 1,
        ],
        [
            'label'    => 'site_latest_news',
            'text'     => 'Laatste nieuws',
            'editable' => 1,
        ],
        [
            'label'    => 'site_environment_warning',
            'text'     => 'Let op, u bekijkt de omgeving:',
            'editable' => 1,
        ],
        [
            'label'    => 'site_close_environment_warning',
            'text'     => 'Omgeving melding verbergen',
            'editable' => 1,
        ],
        [
            'label'    => 'site_back',
            'text'     => 'Terug',
            'editable' => 1,
        ],
        [
            'label'    => 'site_go_to_overview',
            'text'     => 'Naar het overzicht',
            'editable' => 1,
        ],
        [
            'label'    => 'site_design_realization',
            'text'     => 'Webdesign & realisatie',
            'editable' => 1,
        ],
        [
            'label'    => 'site_search',
            'text'     => 'Zoeken',
            'editable' => 1,
        ],
        [
            'label'    => 'site_next',
            'text'     => 'Volgende',
            'editable' => 1,
        ],
        [
            'label'    => 'site_previous',
            'text'     => 'Vorige',
            'editable' => 1,
        ],
        [
            'label'    => 'site_all',
            'text'     => 'alle',
            'editable' => 1,
        ],
        [
            'label'    => 'site_per_page',
            'text'     => 'per pagina',
            'editable' => 1,
        ],
        [
            'label'    => 'site_mail_us',
            'text'     => 'Mail ons',
            'editable' => 1,
        ],
        [
            'label'    => 'site_direct_contact',
            'text'     => 'Direct contact',
            'editable' => 1,
        ],
        [
            'label'    => 'site_contact',
            'text'     => 'Contact',
            'editable' => 1,
        ],
        [
            'label'    => 'site_no_image',
            'text'     => 'Geen afbeelding',
            'editable' => 1,
        ],
        [
            'label'    => 'site_warning',
            'text'     => 'Waarschuwing',
            'editable' => 1,
        ],
        [
            'label'    => 'site_outdated_browser',
            'text'     => 'U probeert deze website te openen. Helaas maakt u gebruik van een verouderde webbrowser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_please_update_browser',
            'text'     => 'Wij adviseren u deze browser te updaten, zodat u weer veilig alle websites kunt openen. Maak hier een keuze om de update van uw gewenste browser te downloaden.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_visit_site_old_browser',
            'text'     => 'Site bezoeken met uw oude browser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_continue_with_old_browser',
            'text'     => 'Ga verder met oude browser',
            'editable' => 1,
        ],
        [
            'label'    => 'site_youtube_videos',
            'text'     => 'YouTubevideo\'s',
            'editable' => 1,
        ],
        [
            'label'    => 'site_images',
            'text'     => 'Afbeeldingen',
            'editable' => 1,
        ],
        [
            'label'    => 'site_files',
            'text'     => 'Bestanden',
            'editable' => 1,
        ],
        [
            'label'    => 'site_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'site_pay_attention',
            'text'     => 'Let op, testomgeving!',
            'editable' => 1,
        ],
        [
            'label'    => 'site_information_missing_or_incorrect',
            'text'     => 'Enkele gegevens ontbreken of zijn onjuist',
            'editable' => 1,
        ],
        [
            'label'    => 'site_form_cannot_be_sent_filled_not_completely',
            'text'     => 'Het formulier kan nog niet worden verzonden omdat het nog niet helemaal (correct) is ingevuld.',
            'editable' => 1,
        ],
        [
            'label'    => 'captcha_error',
            'text'     => 'Onze SPAM beveiliging heeft wat vreemds ontdekt en kan hierdoor het formulier niet versturen. Probeer het opnieuw.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_mo_fr',
            'text'     => 'Maandag t/m Vrijdag',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sat',
            'text'     => 'Zaterdag',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sun',
            'text'     => 'Zondag',
            'editable' => 1,
        ],
        [
            'label'    => 'global_files',
            'text'     => 'Bestanden',
            'editable' => 1,
        ],
        [
            'label'    => 'global_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'social_share',
            'text'     => 'Delen',
            'editable' => 1,
        ],
        [
            'label'    => 'global_archive',
            'text'     => 'Archief',
            'editable' => 1,
        ],
        [
            'label'    => 'site_visit_site_old_browser',
            'text'     => 'Site bezoeken met uw oude browser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_continue_with_old_browser',
            'text'     => 'Ga verder met oude browser',
            'editable' => 1,
        ],
        [
            'label'    => 'site_videos',
            'text'     => 'Video\'s',
            'editable' => 1,
        ],
        [
            'label'    => 'site_images',
            'text'     => 'Afbeeldingen',
            'editable' => 1,
        ],
        [
            'label'    => 'site_files',
            'text'     => 'Bestanden',
            'editable' => 1,
        ],
        [
            'label'    => 'site_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'site_pay_attention',
            'text'     => 'Let op, testomgeving!',
            'editable' => 1,
        ],
        [
            'label'    => 'site_information_missing_or_incorrect',
            'text'     => 'Enkele gegevens ontbreken of zijn onjuist',
            'editable' => 1,
        ],
        [
            'label'    => 'site_form_cannot_be_sent_filled_not_completely',
            'text'     => 'Het formulier kan nog niet worden verzonden omdat het nog niet helemaal (correct) is ingevuld.',
            'editable' => 1,
        ],
        [
            'label'    => 'captcha_error',
            'text'     => 'Onze SPAM beveiliging heeft wat vreemds ontdekt en kan hierdoor het formulier niet versturen. Probeer het opnieuw.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_mo_fr',
            'text'     => 'Maandag t/m Vrijdag',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sat',
            'text'     => 'Zaterdag',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sun',
            'text'     => 'Zondag',
            'editable' => 1,
        ],
        [
            'label'    => 'global_files',
            'text'     => 'Bestanden',
            'editable' => 1,
        ],
        [
            'label'    => 'global_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'social_share',
            'text'     => 'Delen',
            'editable' => 1,
        ],
        [
            'label'    => 'global_archive',
            'text'     => 'Archief',
            'editable' => 1,
        ],
        [
            'label'    => 'contact_spam',
            'text'     => 'Onze SPAM beveiliging heeft wat vreemds ontdekt en kan hierdoor het formulier niet versturen. Probeer het opnieuw.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_navigate',
            'text'     => 'Navigeer',
            'editable' => 1,
        ],
        [
            'label'    => 'site_required_field',
            'text'     => 'Dit is een verplicht veld.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_more',
            'text'     => 'Meer',
            'editable' => 1,
        ],
    ],
    'en' => [
        [
            'label'    => 'site_fill_in_your_email',
            'text'     => 'Fill in a email-address',
            'editable' => 1,
        ],
        [
            'label'    => 'site_email',
            'text'     => 'E-mail',
            'editable' => 1,
        ],
        [
            'label'    => 'site_visit_us',
            'text'     => 'Visit us',
            'editable' => 1,
        ],
        [
            'label'    => 'site_send_whatsapp',
            'text'     => 'Send a WhatsApp',
            'editable' => 1,
        ],
        [
            'label'    => 'site_chat_with_us',
            'text'     => 'Chat with us',
            'editable' => 1,
        ],
        [
            'label'    => 'site_call_us',
            'text'     => 'Call us',
            'editable' => 1,
        ],
        [
            'label'    => 'site_send_email',
            'text'     => 'Mail us',
            'editable' => 1,
        ],
        [
            'label'    => 'site_openings_hours',
            'text'     => 'Openinghours',
            'editable' => 1,
        ],
        [
            'label'    => 'site_follow_us',
            'text'     => 'Follow us',
            'editable' => 1,
        ],
        [
            'label'    => 'site_social',
            'text'     => 'Social media',
            'editable' => 1,
        ],
        [
            'label'    => 'site_created_by',
            'text'     => 'By',
            'editable' => 1,
        ],
        [
            'label'    => 'site_newsletter_intro',
            'text'     => 'Subscribe for the newsletter',
            'editable' => 1,
        ],
        [
            'label'    => 'site_newsletter',
            'text'     => 'Newsletter',
            'editable' => 1,
        ],
        [
            'label'    => 'site_latest_news',
            'text'     => 'Latest news',
            'editable' => 1,
        ],
        [
            'label'    => 'site_environment_warning',
            'text'     => 'Caution, you are currently watching environment:',
            'editable' => 1,
        ],
        [
            'label'    => 'site_close_environment_warning',
            'text'     => 'Hide environment message',
            'editable' => 1,
        ],
        [
            'label'    => 'site_back',
            'text'     => 'Back',
            'editable' => 1,
        ],
        [
            'label'    => 'site_go_to_overview',
            'text'     => 'To overview',
            'editable' => 1,
        ],
        [
            'label'    => 'site_design_realization',
            'text'     => 'Webdesign & realisation',
            'editable' => 1,
        ],
        [
            'label'    => 'site_search',
            'text'     => 'Search',
            'editable' => 1,
        ],
        [
            'label'    => 'site_next',
            'text'     => 'Next',
            'editable' => 1,
        ],
        [
            'label'    => 'site_previous',
            'text'     => 'Previous',
            'editable' => 1,
        ],
        [
            'label'    => 'site_all',
            'text'     => 'all',
            'editable' => 1,
        ],
        [
            'label'    => 'site_per_page',
            'text'     => 'per page',
            'editable' => 1,
        ],
        [
            'label'    => 'site_mail_us',
            'text'     => 'Mail us',
            'editable' => 1,
        ],
        [
            'label'    => 'site_direct_contact',
            'text'     => 'Direct contact',
            'editable' => 1,
        ],
        [
            'label'    => 'site_contact',
            'text'     => 'Contact',
            'editable' => 1,
        ],
        [
            'label'    => 'site_no_image',
            'text'     => 'No image',
            'editable' => 1,
        ],
        [
            'label'    => 'site_warning',
            'text'     => 'Warning',
            'editable' => 1,
        ],
        [
            'label'    => 'site_outdated_browser',
            'text'     => 'You are trying to open this website, unfortunately you use an older webbrowser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_please_update_browser',
            'text'     => 'We advise you to update this browser, so you can safely open websites. Choose an option to update your browser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_visit_site_old_browser',
            'text'     => 'Visit site with your old browser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_continue_with_old_browser',
            'text'     => 'Continue with old browser',
            'editable' => 1,
        ],
        [
            'label'    => 'site_youtube_videos',
            'text'     => 'YouTubevideo\'s',
            'editable' => 1,
        ],
        [
            'label'    => 'site_images',
            'text'     => 'Images',
            'editable' => 1,
        ],
        [
            'label'    => 'site_files',
            'text'     => 'Files',
            'editable' => 1,
        ],
        [
            'label'    => 'site_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'site_pay_attention',
            'text'     => 'Caution, test environment!',
            'editable' => 1,
        ],
        [
            'label'    => 'site_information_missing_or_incorrect',
            'text'     => 'Some of the date is missing or invalid',
            'editable' => 1,
        ],
        [
            'label'    => 'site_form_cannot_be_sent_filled_not_completely',
            'text'     => 'The form can not be send because it is not (correctly) filled in.',
            'editable' => 1,
        ],
        [
            'label'    => 'captcha_error',
            'text'     => 'Our spam detection found something strange, because of this the form can not be send. Please try again.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_mo_fr',
            'text'     => 'Monday until Friday',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sat',
            'text'     => 'Saturday',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sun',
            'text'     => 'Sunday',
            'editable' => 1,
        ],
        [
            'label'    => 'global_files',
            'text'     => 'Files',
            'editable' => 1,
        ],
        [
            'label'    => 'global_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'social_share',
            'text'     => 'Share',
            'editable' => 1,
        ],
        [
            'label'    => 'global_archive',
            'text'     => 'Archive',
            'editable' => 1,
        ],
        [
            'label'    => 'site_visit_site_old_browser',
            'text'     => 'Visit site with your old browser.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_continue_with_old_browser',
            'text'     => 'Continue with old browser',
            'editable' => 1,
        ],
        [
            'label'    => 'site_videos',
            'text'     => 'Video\'s',
            'editable' => 1,
        ],
        [
            'label'    => 'site_images',
            'text'     => 'Images',
            'editable' => 1,
        ],
        [
            'label'    => 'site_files',
            'text'     => 'Files',
            'editable' => 1,
        ],
        [
            'label'    => 'site_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'site_pay_attention',
            'text'     => 'Caution, test environment!',
            'editable' => 1,
        ],
        [
            'label'    => 'site_information_missing_or_incorrect',
            'text'     => 'Some of the date is missing or invalid',
            'editable' => 1,
        ],
        [
            'label'    => 'site_form_cannot_be_sent_filled_not_completely',
            'text'     => 'The form can not be send because it is not (correctly) filled in.',
            'editable' => 1,
        ],
        [
            'label'    => 'captcha_error',
            'text'     => 'Our spam detection found something strange, because of this the form can not be send. Please try again.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_mo_fr',
            'text'     => 'Monday until Friday',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sat',
            'text'     => 'Saturday',
            'editable' => 1,
        ],
        [
            'label'    => 'site_sun',
            'text'     => 'Sunday',
            'editable' => 1,
        ],
        [
            'label'    => 'global_files',
            'text'     => 'Files',
            'editable' => 1,
        ],
        [
            'label'    => 'global_links',
            'text'     => 'Links',
            'editable' => 1,
        ],
        [
            'label'    => 'social_share',
            'text'     => 'Share',
            'editable' => 1,
        ],
        [
            'label'    => 'global_archive',
            'text'     => 'Archive',
            'editable' => 1,
        ],
        [
            'label'    => 'contact_spam',
            'text'     => 'Our spam detection found something strange, because of this the form can not be send. Please try again.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_navigate',
            'text'     => 'Navigate',
            'editable' => 1,
        ],
        [
            'label'    => 'site_required_field',
            'text'     => 'This is a required field.',
            'editable' => 1,
        ],
        [
            'label'    => 'site_more',
            'text'     => 'More',
            'editable' => 1,
        ],
    ],
];

// add locales
if ($bInstall) {
    // add table
    $sQuery = '
        CREATE TABLE IF NOT EXISTS `locales` (
            `localeId` INT(11) NOT NULL AUTO_INCREMENT,
            `languageId` INT(11) NOT NULL,
            `countryId` INT(11) NOT NULL,
            `domain` VARCHAR(250) COLLATE utf8_unicode_ci NOT NULL,
            `subdomain` ENUM(\'country\',\'language\') COLLATE utf8_unicode_ci DEFAULT NULL,
            `prefix1` ENUM(\'country\',\'language\') COLLATE utf8_unicode_ci DEFAULT NULL,
            `prefix2` ENUM(\'country\') COLLATE utf8_unicode_ci DEFAULT NULL,
            `urlFormat` VARCHAR(250) COLLATE utf8_unicode_ci NOT NULL,
            `online` TINYINT(1) NOT NULL DEFAULT \'0\',
            `order` INT(11) NOT NULL DEFAULT \'9999\',
            `created` timestamp NULL DEFAULT NULL,
            `createdBy` VARCHAR(250) COLLATE utf8_unicode_ci NOT NULL,
            `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            `modifiedBy` VARCHAR(250) COLLATE utf8_unicode_ci DEFAULT NULL,
            PRIMARY KEY (`localeId`),
            UNIQUE KEY `locales_urlFormat` (`urlFormat`),
            KEY `locales_languageId_idx` (`languageId`),
            KEY `locales_countryId_idx` (`countryId`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
        ';
    $oDb->query($sQuery, QRY_NORESULT);
}

// check pages constraints
if ($oDb->tableExists('locales')) {
    if ($oDb->tableExists('countries')) {
        // check countries constraint
        if (!$oDb->constraintExists('locales', 'countryId', 'countries', 'countryId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `locales`.`countryId` => `countries`.`countryId`';
            if ($bInstall) {
                $oDb->addConstraint('locales', 'countryId', 'countries', 'countryId', 'RESTRICT', 'RESTRICT');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('locales', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `locales`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('locales', 'languageId', 'languages', 'languageId', 'RESTRICT', 'RESTRICT');
            }
        }
    }
}

// add default locale (NL)
if (class_exists('LocaleManager')) {
    if (count(LocaleManager::getLocalesByFilter()) == 0 && $bInstall) {
        $oFirstLocale             = new ACMS\Locale();
        $oFirstLocale->localeId   = 1;
        $oFirstLocale->languageId = DEFAULT_LANGUAGE_ID;
        $oFirstLocale->countryId  = DEFAULT_COUNTRY_ID;
        $oFirstLocale->domain     = 'loc.a-cms.nl';
        $oFirstLocale->subdomain  = null;
        $oFirstLocale->prefix1    = null;
        $oFirstLocale->prefix2    = null;
        $oFirstLocale->urlFormat  = 'loc.a-cms.nl';
        $oFirstLocale->dateFormat = ACMS\Locale::DATE_FORMAT_NL;
        $oFirstLocale->online     = 1;
        $oFirstLocale->order      = 1;
        $oFirstLocale->created    = date('Y-m-d H:i:s');
        $oFirstLocale->createdBy  = 'LandgoedVoorn';
        $oFirstLocale->modified   = null;
        $oFirstLocale->modifedBy  = null;

        if ($oFirstLocale->isValid()) {
            LocaleManager::saveLocale($oFirstLocale);
        } else {
            _d($oFirstLocale->getInvalidProps());
            die('Can\'t create locale `NL`');
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('defaultCountryId'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `defaultCountryId`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'defaultCountryId';
        $oSetting->value = CountryManager::getCountryByCode('NL')->countryId;
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('googleGeoMapsKey'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `googleGeoMapsKey`';
    if ($bInstall) {
        $oSetting       = new Setting();
        $oSetting->name = 'googleGeoMapsKey';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('reCaptchaWebsiteKey'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `reCaptchaWebsiteKey`';
    if ($bInstall) {
        $oSetting       = new Setting();
        $oSetting->name = 'reCaptchaWebsiteKey';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}
if (!($oSetting = SettingManager::getSettingByName('reCaptchaSecretKey'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `reCaptchaSecretKey`';
    if ($bInstall) {
        $oSetting       = new Setting();
        $oSetting->name = 'reCaptchaSecretKey';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (($oSetting = SettingManager::getSettingByName('reCaptchaWebsiteKey')) && !$oSetting->value) {
    //$aLogs[$sModuleName]['errors'][] = 'Please enter your reCAPTCHA website key in the settings module';
}

if (($oSetting = SettingManager::getSettingByName('reCaptchaSecretKey')) && !$oSetting->value) {
    //$aLogs[$sModuleName]['errors'][] = 'Please enter your reCAPTCHA secret key in the settings module';
}

if (!($oSetting = SettingManager::getSettingByName('2StepWhitelistIps'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `2StepWhitelistIps`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = '2StepWhitelistIps';
        $oSetting->value = '127.0.0.1,::1';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}
if (!($oSetting = SettingManager::getSettingByName('2StepForced'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `2StepForced`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = '2StepForced';
        $oSetting->value = 1;
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

//COOKIE CONSENT SETTINGS
if (!($oSetting = SettingManager::getSettingByName('cc_enabled'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `cc_enabled`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'cc_enabled';
        $oSetting->value = 0;
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('cc_position'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `cc_postition`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'cc_position';
        $oSetting->value = 'bottom-left';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('cc_theme'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `cc_theme`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'cc_theme';
        $oSetting->value = 'block';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('cc_bgcolor'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `cc_bgcolor`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'cc_bgcolor';
        $oSetting->value = '#222121';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('cc_textcolor'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `cc_textcolor`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'cc_textcolor';
        $oSetting->value = '#222121';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('cc_accentcolor'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `cc_accentcolor`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'cc_accentcolor';
        $oSetting->value = '#E5104C';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

// create maintenance setting
if (!($oSetting = SettingManager::getSettingByName('maintenance'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `maintenance`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'maintenance';
        $oSetting->value = 0;
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

// create beanstalkAppWhitelistIps setting
if (!($oSetting = SettingManager::getSettingByName('beanstalkAppWhitelistIpRange'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `beanstalkAppWhitelistIpRange`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'beanstalkAppWhitelistIpRange';
        $oSetting->value = '50.31.156.1,50.31.156.127';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

// create beanstalkAppWhitelistIps setting
if (!($oSetting = SettingManager::getSettingByName('landgoedvoornWhitelistIps'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `landgoedvoornWhitelistIps`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'landgoedvoornWhitelistIps';
        $oSetting->value = '37.0.81.193,127.0.0.1,::1,80.28.138.195';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

// add page
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageAccount = PageManager::getPageByName('access-denied', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `access-denied`';
        if ($bInstall) {
            $oPageAccount             = new Page();
            $oPageAccount->languageId = DEFAULT_LANGUAGE_ID;
            $oPageAccount->name       = 'access-denied';
            $oPageAccount->title      = 'Toegang geblokkeerd';
            $oPageAccount->content    = '
<p>Uw toegang tot onze website is geblokkeerd. Dit kan de volgende redenen hebben.</p>
<ul>
<li>U bent onterecht geblokkeerd;</li>
<li>U heeft 5 maal uw gebruikersnaam en wachtwoord verkeerd ingevuld;</li>
<li>U ben geblokkeerd omdat u dingen heeft gedaan die in strijd zijn met onze algemene voorwaarden.</li>
</ul>
<p>Als u van mening bent dat u ten onrechte bent geblokkeerd, neem dan contact met ons op. Wij zullen u dan zo snel mogelijk weer toegang geven.</p>';
            $oPageAccount->shortTitle = 'Toegang geblokkeerd';
            $oPageAccount->forceUrlPath('/toegang-geblokkeerd');
            $oPageAccount->setControllerPath('/modules/pages/site/controllers/page.cont.php');
            $oPageAccount->setInMenu(0);
            $oPageAccount->setIndexable(0);
            $oPageAccount->setOnlineChangeable(0);
            $oPageAccount->setDeletable(0);
            $oPageAccount->setMayHaveSub(0);
            $oPageAccount->setLockUrlPath(1);
            $oPageAccount->setLockParent(1);
            $oPageAccount->setHideImageManagement(1);
            $oPageAccount->setHideFileManagement(1);
            $oPageAccount->setHideLinkManagement(1);
            $oPageAccount->setHideVideoLinkManagement(1);
            if ($oPageAccount->isValid()) {
                PageManager::savePage($oPageAccount);
            } else {
                _d($oPageAccount->getInvalidProps());
                die('Can\'t create page `access-denied`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(
        [
            'showAll'       => true,
            'NOTlanguageId' => DEFAULT_LANGUAGE_ID,
        ]
    ) as $oLocale) {
        if (!($oPageAccount = PageManager::getPageByName('access-denied', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `access-denied` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oPageAccount             = new Page();
                $oPageAccount->languageId = $oLocale->languageId;
                $oPageAccount->name       = 'access-denied';
                $oPageAccount->title      = 'Access denied';
                $oPageAccount->content    = '
<p>Your access to our website is denied. This may have the following reasons.</p>
<ul>
<li>You are incorrectly blocked;</li>
<li>You have entered the wrong username and password combination for five times;</li>
<li>You are blocked because you have done things that are contrary to our terms and conditions.</li>
</ul>
<p>If you believe that you are denied access in error, please contact us. We will then give you access as soon as possible.</p>';

                $oPageAccount->shortTitle = 'Access denied';
                $oPageAccount->forceUrlPath('/access-denied');
                $oPageAccount->setControllerPath('/modules/pages/site/controllers/page.cont.php');
                $oPageAccount->setInMenu(0);
                $oPageAccount->setIndexable(0);
                $oPageAccount->setOnlineChangeable(0);
                $oPageAccount->setDeletable(0);
                $oPageAccount->setMayHaveSub(0);
                $oPageAccount->setLockUrlPath(1);
                $oPageAccount->setLockParent(1);
                $oPageAccount->setHideImageManagement(1);
                $oPageAccount->setHideFileManagement(1);
                $oPageAccount->setHideLinkManagement(1);
                $oPageAccount->setHideVideoLinkManagement(1);
                if ($oPageAccount->isValid()) {
                    PageManager::savePage($oPageAccount);
                } else {
                    _d($oPageAccount->getInvalidProps());
                    die('Can\'t create page `access-denied`');
                }
            }
        }
    }
}

if (!$oDb->tableExists('module_redirects')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `module_redirects`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `module_redirects` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `languageId` INT(11) NOT NULL,
          `vacancyId` INT(11) NULL,
          `urlPath` VARCHAR(2048) COLLATE utf8_unicode_ci NOT NULL,
          `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `vacancyId_moduleRedirects` (`vacancyId`), 
          KEY `languageId_moduleRedirects` (`languageId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('module_redirects')) {
    // check languages constraint
    if (!$oDb->constraintExists('module_redirects', 'languageId', 'languages', 'languageId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `module_redirects`.`languageId` => `languages`.`languageId`';
        if ($bInstall) {
            $oDb->addConstraint('module_redirects', 'languageId', 'languages', 'languageId', 'RESTRICT', 'RESTRICT');
        }
    }
}

if (!$oDb->columnExists('users', 'imageId')) {
    $aLogs[$sModuleName]['errors'][] = ' Missing column imageId';
    if ($bInstall) {
        $oDb->addColumn('users', 'imageId', 'INT', 11, 'twoStepSecretVerified');
    }
}

if (!$oDb->columnExists('users', 'twoStepCookie')) {
    $aLogs[$sModuleName]['errors'][] = ' Missing column twoStepCookie';
    if ($bInstall) {
        $oDb->addColumn('users', 'twoStepCookie', 'INT', 11, 'twoStepSecretVerified');
    }
}

// check robots.txt existance and content
if (!file_exists(DOCUMENT_ROOT . '/robots.txt')) {
    $aLogs['core']['errors'][] = 'Robots.txt does not exist in root';
} else {
    $sContents = file_get_contents(DOCUMENT_ROOT . '/robots.txt');

    $sContentsNeeded = '# !! DO NOT CHANGE THIS FILE, THIS IS A FALLBACK FILE, THERE IS A ROBOTS CONTROLLER TO HANDLE THIS !!
User-agent: *
Disallow: /
# !! DO NOT CHANGE THIS FILE, THIS IS A FALLBACK FILE, THERE IS A ROBOTS CONTROLLER TO HANDLE THIS !!';

    $sContentsReplaced       = preg_replace('#(\r|\n)#', '', $sContents);
    $sContentsNeededReplaced = preg_replace('#(\r|\n)#', '', $sContentsNeeded);

    if ($sContentsReplaced != $sContentsNeededReplaced) {
        $aLogs['core']['errors'][] = 'Changed robots.txt contents, should not be changed.';
        if ($bInstall) {
            file_put_contents(DOCUMENT_ROOT . '/robots.txt', $sContentsNeeded);
        }
    }
}

if (!$oDb->tableExists('language_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `language_translations`';
    if ($bInstall) {
        $sQuery = '
            CREATE TABLE `language_translations` (
              `languageTranslationId` int(11) NOT NULL AUTO_INCREMENT,
              `languageId` int(11) NOT NULL,
              `localeId` int(11) NOT NULL,
              `name` varchar(250),
              PRIMARY KEY(`languageTranslationId`),
              KEY (`languageId`),
              KEY (`localeId`),
              KEY (`name`),
              UNIQUE KEY `language_locale` (`languageId`, `LocaleId`)
            ) Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
        ';
        $oDb->query($sQuery);
    }
} else {
    if (!$oDb->constraintExists('language_translations', 'languageId', 'languages', 'languageId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing constraint `language_translations`.`languageId` > `languages`.`languageId`';
        if ($bInstall) {
            $oDb->addConstraint('language_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'RESTRICT');
        }
    }

    if (!$oDb->constraintExists('language_translations', 'localeId', 'locales', 'localeId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing constraint `language_translations`.`localeId` > `locales`.`localeId`';
        if ($bInstall) {
            $oDb->addConstraint('language_translations', 'localeId', 'locales', 'localeId', 'RESTRICT', 'RESTRICT');
        }
    }

    $aTranslations = [
        '1'   => [
            'en' => 'Abkhaz',
            'nl' => 'Abchazisch',
        ],
        '2'   => [
            'en' => 'Afar',
            'nl' => 'Afar',
        ],
        '3'   => [
            'en' => 'Afrikaans',
            'nl' => 'Afrikaans',
        ],
        '4'   => [
            'en' => 'Akan',
            'nl' => 'Akan',
        ],
        '5'   => [
            'en' => 'Albanian',
            'nl' => 'Albanees',
        ],
        '6'   => [
            'en' => 'Amharic',
            'nl' => 'Amhaars',
        ],
        '7'   => [
            'en' => 'Arabic',
            'nl' => 'Arabisch',
        ],
        '8'   => [
            'en' => 'Aragonese',
            'nl' => 'Aragonees',
        ],
        '9'   => [
            'en' => 'Armenian',
            'nl' => 'Armeens',
        ],
        '10'  => [
            'en' => 'Assamese',
            'nl' => 'Assamitisch',
        ],
        '11'  => [
            'en' => 'Avaric',
            'nl' => 'Avaars',
        ],
        '12'  => [
            'en' => 'Avestan',
            'nl' => 'Avestisch, Avestaans',
        ],
        '13'  => [
            'en' => 'Aymara',
            'nl' => 'Aymara',
        ],
        '14'  => [
            'en' => 'Azerbaijani',
            'nl' => 'Azerbeidzjaans',
        ],
        '15'  => [
            'en' => 'Bambara',
            'nl' => 'Bambara, Bamanankan',
        ],
        '16'  => [
            'en' => 'Bashkir',
            'nl' => 'Basjkiers',
        ],
        '17'  => [
            'en' => 'Basque',
            'nl' => 'Baskisch',
        ],
        '18'  => [
            'en' => 'Belarusian',
            'nl' => 'Belarussisch, Wit-Russisch',
        ],
        '19'  => [
            'en' => 'Bengali, Bangla',
            'nl' => 'Bengaals, Bangla',
        ],
        '20'  => [
            'en' => 'Bihari',
            'nl' => 'Bihari',
        ],
        '21'  => [
            'en' => 'Bislama',
            'nl' => 'Bislama, Bichelamar',
        ],
        '22'  => [
            'en' => 'Bosnian',
            'nl' => 'Bosnisch',
        ],
        '23'  => [
            'en' => 'Breton',
            'nl' => 'Bretons',
        ],
        '24'  => [
            'en' => 'Bulgarian',
            'nl' => 'Bulgaars',
        ],
        '25'  => [
            'en' => 'Burmese',
            'nl' => 'Birmaans',
        ],
        '26'  => [
            'en' => 'Catalan',
            'nl' => 'Catalaans',
        ],
        '27'  => [
            'en' => 'Chamorro',
            'nl' => 'Chamorro',
        ],
        '28'  => [
            'en' => 'Chechen',
            'nl' => 'Tsjetsjeens',
        ],
        '29'  => [
            'en' => 'Chichewa, Chewa, Nyanja',
            'nl' => 'Nyanja, Chichewa',
        ],
        '30'  => [
            'en' => 'Chinese',
            'nl' => 'Chinees',
        ],
        '31'  => [
            'en' => 'Chuvash',
            'nl' => 'Tsjoevasjisch',
        ],
        '32'  => [
            'en' => 'Cornish',
            'nl' => 'Cornisch',
        ],
        '33'  => [
            'en' => 'Corsican',
            'nl' => 'Corsicaans',
        ],
        '34'  => [
            'en' => 'Cree',
            'nl' => 'Cree',
        ],
        '35'  => [
            'en' => 'Croatian',
            'nl' => 'Kroatisch',
        ],
        '36'  => [
            'en' => 'Czech',
            'nl' => 'Tsjechisch',
        ],
        '37'  => [
            'en' => 'Danish',
            'nl' => 'Deens',
        ],
        '38'  => [
            'en' => 'Divehi, Dhivehi, Maldivian',
            'nl' => 'Divehi, Dhivehi, Maldivisch',
        ],
        '39'  => [
            'en' => 'Dutch',
            'nl' => 'Nederlands',
        ],
        '40'  => [
            'en' => 'Dzongkha',
            'nl' => 'Dzongkha',
        ],
        '41'  => [
            'en' => 'English',
            'nl' => 'Engels',
        ],
        '42'  => [
            'en' => 'Esperanto',
            'nl' => 'Esperanto',
        ],
        '43'  => [
            'en' => 'Estonian',
            'nl' => 'Estisch',
        ],
        '44'  => [
            'en' => 'Ewe',
            'nl' => 'Ewe',
        ],
        '45'  => [
            'en' => 'Faroese',
            'nl' => 'Faeröers',
        ],
        '46'  => [
            'en' => 'Fijian',
            'nl' => 'Fijisch',
        ],
        '47'  => [
            'en' => 'Finnish',
            'nl' => 'Fins',
        ],
        '48'  => [
            'en' => 'French',
            'nl' => 'Frans',
        ],
        '49'  => [
            'en' => 'Fula, Fulah, Pulaar, Pular',
            'nl' => 'Fula, Fulah, Pulaar, Pular',
        ],
        '50'  => [
            'en' => 'Galician',
            'nl' => 'Galicisch',
        ],
        '51'  => [
            'en' => 'Georgian',
            'nl' => 'Georgisch',
        ],
        '52'  => [
            'en' => 'German',
            'nl' => 'Duits',
        ],
        '53'  => [
            'en' => 'Greek (modern)',
            'nl' => 'Grieks (modern)',
        ],
        '54'  => [
            'en' => 'Guaraní',
            'nl' => 'Guaraní',
        ],
        '55'  => [
            'en' => 'Gujarati',
            'nl' => 'Gujarati',
        ],
        '56'  => [
            'en' => 'Haitian, Haitian Creole',
            'nl' => 'Haïtiaans Creools',
        ],
        '57'  => [
            'en' => 'Hausa',
            'nl' => 'Hausa',
        ],
        '58'  => [
            'en' => 'Hebrew (modern)',
            'nl' => 'Hebreeuws (modern)',
        ],
        '59'  => [
            'en' => 'Herero',
            'nl' => 'Herero, Otjiherero',
        ],
        '60'  => [
            'en' => 'Hindi',
            'nl' => 'Hindi',
        ],
        '61'  => [
            'en' => 'Hiri Motu',
            'nl' => 'Hiri Motu',
        ],
        '62'  => [
            'en' => 'Hungarian',
            'nl' => 'Hongaars',
        ],
        '63'  => [
            'en' => 'Interlingua',
            'nl' => 'Interlingua (de IALA)',
        ],
        '64'  => [
            'en' => 'Indonesian',
            'nl' => 'Indonesisch',
        ],
        '65'  => [
            'en' => 'Interlingue',
            'nl' => 'Interlingue',
        ],
        '66'  => [
            'en' => 'Irish',
            'nl' => 'Iers',
        ],
        '67'  => [
            'en' => 'Igbo',
            'nl' => 'Igbo',
        ],
        '68'  => [
            'en' => 'Inupiaq',
            'nl' => 'Inupiak',
        ],
        '69'  => [
            'en' => 'Ido',
            'nl' => 'Ido',
        ],
        '70'  => [
            'en' => 'Icelandic',
            'nl' => 'IJslands',
        ],
        '71'  => [
            'en' => 'Italian',
            'nl' => 'Italiaans',
        ],
        '72'  => [
            'en' => 'Inuktitut',
            'nl' => 'Inuktitut',
        ],
        '73'  => [
            'en' => 'Japanese',
            'nl' => 'Japans',
        ],
        '74'  => [
            'en' => 'Javanese',
            'nl' => 'Javaans',
        ],
        '75'  => [
            'en' => 'Kalaallisut, Greenlandic',
            'nl' => 'Kalaallisut, Groenlands',
        ],
        '76'  => [
            'en' => 'Kannada',
            'nl' => 'Kannada',
        ],
        '77'  => [
            'en' => 'Kanuri',
            'nl' => 'Kanuri',
        ],
        '78'  => [
            'en' => 'Kashmiri',
            'nl' => 'Kasjmiri',
        ],
        '79'  => [
            'en' => 'Kazakh',
            'nl' => 'Kazachs',
        ],
        '80'  => [
            'en' => 'Khmer',
            'nl' => 'Khmer, Cambodjaans',
        ],
        '81'  => [
            'en' => 'Kikuyu, Gikuyu',
            'nl' => 'Kikuyu, Gikuyu',
        ],
        '82'  => [
            'en' => 'Kinyarwanda',
            'nl' => 'Kinyarwanda',
        ],
        '83'  => [
            'en' => 'Kyrgyz',
            'nl' => 'Kirgizisch',
        ],
        '84'  => [
            'en' => 'Komi',
            'nl' => 'Zurjeens',
        ],
        '85'  => [
            'en' => 'Kongo',
            'nl' => 'Kongo',
        ],
        '86'  => [
            'en' => 'Korean',
            'nl' => 'Koreaans',
        ],
        '87'  => [
            'en' => 'Kurdish',
            'nl' => 'Koerdisch',
        ],
        '88'  => [
            'en' => 'Kwanyama, Kuanyama',
            'nl' => 'Kwanyama, Kuanyama',
        ],
        '89'  => [
            'en' => 'Latin',
            'nl' => 'Latijn',
        ],
        '90'  => [
            'en' => 'Luxembourgish, Letzeburgesch',
            'nl' => 'Luxemburgs',
        ],
        '91'  => [
            'en' => 'Ganda',
            'nl' => 'Luganda',
        ],
        '92'  => [
            'en' => 'Limburgish, Limburgan, Limburger',
            'nl' => 'Limburgs',
        ],
        '93'  => [
            'en' => 'Lingala',
            'nl' => 'Lingala',
        ],
        '94'  => [
            'en' => 'Lao',
            'nl' => 'Laotiaans',
        ],
        '95'  => [
            'en' => 'Lithuanian',
            'nl' => 'Litouws',
        ],
        '96'  => [
            'en' => 'Luba-Katanga',
            'nl' => 'Luba-Katanga',
        ],
        '97'  => [
            'en' => 'Latvian',
            'nl' => 'Lets',
        ],
        '98'  => [
            'en' => 'Manx',
            'nl' => 'Manx-Gaelisch',
        ],
        '99'  => [
            'en' => 'Macedonian',
            'nl' => 'Macedonisch',
        ],
        '100' => [
            'en' => 'Malagasy',
            'nl' => 'Malagasitalen',
        ],
        '101' => [
            'en' => 'Malay',
            'nl' => 'Maleis',
        ],
        '102' => [
            'en' => 'Malayalam',
            'nl' => 'Malayalam',
        ],
        '103' => [
            'en' => 'Maltese',
            'nl' => 'Maltees',
        ],
        '104' => [
            'en' => 'Māori',
            'nl' => 'Maori',
        ],
        '105' => [
            'en' => 'Marathi (Marāṭhī)',
            'nl' => 'Marathi',
        ],
        '106' => [
            'en' => 'Marshallese',
            'nl' => 'Marshallees',
        ],
        '107' => [
            'en' => 'Mongolian',
            'nl' => 'Mongools',
        ],
        '108' => [
            'en' => 'Nauruan',
            'nl' => 'Nauruaans',
        ],
        '109' => [
            'en' => 'Navajo, Navaho',
            'nl' => 'Navajo, Navaho',
        ],
        '110' => [
            'en' => 'Northern Ndebele',
            'nl' => 'Noord-Ndebele',
        ],
        '111' => [
            'en' => 'Nepali',
            'nl' => 'Nepalees',
        ],
        '112' => [
            'en' => 'Ndonga',
            'nl' => 'Ndonga',
        ],
        '113' => [
            'en' => 'Norwegian Bokmål',
            'nl' => 'Noors Bokmål',
        ],
        '114' => [
            'en' => 'Norwegian Nynorsk',
            'nl' => 'Noors Nynorsk',
        ],
        '115' => [
            'en' => 'Norwegian',
            'nl' => 'Noors',
        ],
        '116' => [
            'en' => 'Nuosu',
            'nl' => 'Nuosu, Yi',
        ],
        '117' => [
            'en' => 'Southern Ndebele',
            'nl' => 'Zuid-Ndebele',
        ],
        '118' => [
            'en' => 'Occitan',
            'nl' => 'Occitaans',
        ],
        '119' => [
            'en' => 'Ojibwe, Ojibwa',
            'nl' => 'Ojibwe, Ojibwa',
        ],
        '120' => [
            'en' => 'Old Church Slavonic, Church Slavonic, Old Bulgarian',
            'nl' => 'Oudkerkslavisch, Oudbulgaars',
        ],
        '121' => [
            'en' => 'Oromo',
            'nl' => 'Afaan Oromo',
        ],
        '122' => [
            'en' => 'Odia, Oriya',
            'nl' => 'Odia, Oriya',
        ],
        '123' => [
            'en' => 'Ossetian, Ossetic',
            'nl' => 'Ossetisch, Osseets',
        ],
        '124' => [
            'en' => 'Eastern Punjabi, Eastern Panjabi',
            'nl' => 'Oost-Punjabi',
        ],
        '125' => [
            'en' => 'Pāli',
            'nl' => 'Pali',
        ],
        '126' => [
            'en' => 'Persian (Farsi)',
            'nl' => 'Perzisch (Farsi)',
        ],
        '127' => [
            'en' => 'Polish',
            'nl' => 'Pools',
        ],
        '128' => [
            'en' => 'Pashto, Pushto',
            'nl' => 'Pasjtoe',
        ],
        '129' => [
            'en' => 'Portuguese',
            'nl' => 'Portugees',
        ],
        '130' => [
            'en' => 'Quechua',
            'nl' => 'Quechua',
        ],
        '131' => [
            'en' => 'Romansh',
            'nl' => 'Reto-Romaans',
        ],
        '132' => [
            'en' => 'Kirundi',
            'nl' => 'Kirundi',
        ],
        '133' => [
            'en' => 'Romanian',
            'nl' => 'Roemeens',
        ],
        '134' => [
            'en' => 'Russian',
            'nl' => 'Russisch',
        ],
        '135' => [
            'en' => 'Sanskrit (Saṁskṛta)',
            'nl' => 'Sanskriet',
        ],
        '136' => [
            'en' => 'Sardinian',
            'nl' => 'Sardijns',
        ],
        '137' => [
            'en' => 'Sindhi',
            'nl' => 'Sindhi',
        ],
        '138' => [
            'en' => 'Northern Sami',
            'nl' => 'Noord-Samisch',
        ],
        '139' => [
            'en' => 'Samoan',
            'nl' => 'Samoaans',
        ],
        '140' => [
            'en' => 'Sango',
            'nl' => 'Sango',
        ],
        '141' => [
            'en' => 'Serbian',
            'nl' => 'Servisch',
        ],
        '142' => [
            'en' => 'Scottish Gaelic, Gaelic',
            'nl' => 'Schots-Gaelisch',
        ],
        '143' => [
            'en' => 'Shona',
            'nl' => 'Shona',
        ],
        '144' => [
            'en' => 'Sinhalese, Sinhala',
            'nl' => 'Singalees, Sinhala',
        ],
        '145' => [
            'en' => 'Slovak',
            'nl' => 'Slowaaks',
        ],
        '146' => [
            'en' => 'Slovene',
            'nl' => 'Sloveens',
        ],
        '147' => [
            'en' => 'Somali',
            'nl' => 'Somalisch',
        ],
        '148' => [
            'en' => 'Southern Sotho',
            'nl' => 'Zuid-Sotho',
        ],
        '149' => [
            'en' => 'Spanish',
            'nl' => 'Spaans',
        ],
        '150' => [
            'en' => 'Sundanese',
            'nl' => 'Soendanees',
        ],
        '151' => [
            'en' => 'Swahili',
            'nl' => 'Swahili',
        ],
        '152' => [
            'en' => 'Swati',
            'nl' => 'Swazi',
        ],
        '153' => [
            'en' => 'Swedish',
            'nl' => 'Zweeds',
        ],
        '154' => [
            'en' => 'Tamil',
            'nl' => 'Tamil',
        ],
        '155' => [
            'en' => 'Telugu',
            'nl' => 'Telugu',
        ],
        '156' => [
            'en' => 'Tajik',
            'nl' => 'Tadzjieks',
        ],
        '157' => [
            'en' => 'Thai',
            'nl' => 'Thai',
        ],
        '158' => [
            'en' => 'Tigrinya',
            'nl' => 'Tigrinya',
        ],
        '159' => [
            'en' => 'Tibetan Standard, Tibetan, Central',
            'nl' => 'Tibetaans',
        ],
        '160' => [
            'en' => 'Turkmen',
            'nl' => 'Turkmeens',
        ],
        '161' => [
            'en' => 'Tagalog',
            'nl' => 'Tagalog',
        ],
        '162' => [
            'en' => 'Tswana',
            'nl' => 'Tswana',
        ],
        '163' => [
            'en' => 'Tonga (Tonga Islands)',
            'nl' => 'Tongaans',
        ],
        '164' => [
            'en' => 'Turkish',
            'nl' => 'Turks',
        ],
        '165' => [
            'en' => 'Tsonga',
            'nl' => 'Tsonga',
        ],
        '166' => [
            'en' => 'Tatar',
            'nl' => 'Tataars',
        ],
        '167' => [
            'en' => 'Twi',
            'nl' => 'Twi',
        ],
        '168' => [
            'en' => 'Tahitian',
            'nl' => 'Tahitiaans',
        ],
        '169' => [
            'en' => 'Uyghur',
            'nl' => 'Oeigoers',
        ],
        '170' => [
            'en' => 'Ukrainian',
            'nl' => 'Oekraïens',
        ],
        '171' => [
            'en' => 'Urdu',
            'nl' => 'Urdu',
        ],
        '172' => [
            'en' => 'Uzbek',
            'nl' => 'Oezbeeks',
        ],
        '173' => [
            'en' => 'Venda',
            'nl' => 'Venda',
        ],
        '174' => [
            'en' => 'Vietnamese',
            'nl' => 'Vietnamees',
        ],
        '175' => [
            'en' => 'Volapük',
            'nl' => 'Volapük',
        ],
        '176' => [
            'en' => 'Walloon',
            'nl' => 'Waals',
        ],
        '177' => [
            'en' => 'Welsh',
            'nl' => 'Welsh',
        ],
        '178' => [
            'en' => 'Wolof',
            'nl' => 'Wolof',
        ],
        '179' => [
            'en' => 'Western Frisian',
            'nl' => 'Westerlauwers Fries',
        ],
        '180' => [
            'en' => 'Xhosa',
            'nl' => 'Xhosa',
        ],
        '181' => [
            'en' => 'Yiddish',
            'nl' => 'Jiddisch',
        ],
        '182' => [
            'en' => 'Yoruba',
            'nl' => 'Yoruba',
        ],
        '183' => [
            'en' => 'Zhuang, Chuang',
            'nl' => 'Zhuang, Chuang',
        ],
        '184' => [
            'en' => 'Zulu',
            'nl' => 'Zoeloe',
        ],
    ];
    foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) as $oLocale) {
        foreach ($aTranslations as $iLanguageId => $aTranslation) {
            if (!LanguageTranslationManager::getLanguageTranslationByLanguageId($iLanguageId, $oLocale->localeId)) {
                $aLogs[$sModuleName]['errors'][] = 'Missing language translation for language `' . $iLanguageId . '` in locale `' . $oLocale->getLocale() . '`';
                if ($bInstall) {
                    $oTranslation = new LanguageTranslation(
                        [
                            'languageId' => $iLanguageId,
                            'localeId'   => $oLocale->localeId,
                            'name'       => $aTranslation[$oLocale->getLanguage()->code] ?? $aTranslation['en'],
                        ]
                    );
                    LanguageTranslationManager::saveLanguageTranslation($oTranslation);
                }
            }
        }
    }
}

if (!$oDb->tableExists('country_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `country_translations`';
    if ($bInstall) {
        $sQuery = '
            CREATE TABLE `country_translations` (
              `countryTranslationId` int(11) NOT NULL AUTO_INCREMENT,
              `countryId` int(11) NOT NULL,
              `localeId` int(11) NOT NULL,
              `name` varchar(250),
              PRIMARY KEY(`countryTranslationId`),
              KEY (`countryId`),
              KEY (`localeId`),
              KEY (`name`),
              UNIQUE KEY `country_locale` (`countryId`, `LocaleId`)
            ) Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
        ';
        $oDb->query($sQuery);
    }
} else {
    if (!$oDb->constraintExists('country_translations', 'countryId', 'countries', 'countryId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing constraint `country_translations`.`countryId` > `countries`.`countryId`';
        if ($bInstall) {
            $oDb->addConstraint('country_translations', 'countryId', 'countries', 'countryId', 'RESTRICT', 'RESTRICT');
        }
    }

    if (!$oDb->constraintExists('country_translations', 'localeId', 'locales', 'localeId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing constraint `country_translations`.`localeId` > `locales`.`localeId`';
        if ($bInstall) {
            $oDb->addConstraint('country_translations', 'localeId', 'locales', 'localeId', 'RESTRICT', 'RESTRICT');
        }
    }

    $aTranslations = [
        '1'   => [
            'en' => 'Andorra',
            'nl' => 'Andorra',
        ],
        '2'   => [
            'en' => 'United Arab Emirates',
            'nl' => 'Verenigde Arabische Emiraten',
        ],
        '3'   => [
            'en' => 'Afghanistan',
            'nl' => 'Afghanistan',
        ],
        '4'   => [
            'en' => 'Antigua and Barbuda',
            'nl' => 'Antigua en Barbuda',
        ],
        '5'   => [
            'en' => 'Anguilla',
            'nl' => 'Anguilla',
        ],
        '6'   => [
            'en' => 'Albania',
            'nl' => 'Albanië',
        ],
        '7'   => [
            'en' => 'Armenia',
            'nl' => 'Armenië',
        ],
        '8'   => [
            'en' => 'Angola',
            'nl' => 'Angola',
        ],
        '9'   => [
            'en' => 'Antarctica',
            'nl' => 'Antarctica',
        ],
        '10'  => [
            'en' => 'Argentina',
            'nl' => 'Argentinië',
        ],
        '11'  => [
            'en' => 'American Samoa',
            'nl' => 'Amerikaans-Samoa',
        ],
        '12'  => [
            'en' => 'Austria',
            'nl' => 'Oostenrijk',
        ],
        '13'  => [
            'en' => 'Australia',
            'nl' => 'Australië',
        ],
        '14'  => [
            'en' => 'Aruba',
            'nl' => 'Aruba',
        ],
        '15'  => [
            'en' => 'Åland',
            'nl' => 'Åland',
        ],
        '16'  => [
            'en' => 'Azerbaijan',
            'nl' => 'Azerbaijan',
        ],
        '17'  => [
            'en' => 'Bosnia and Herzegovina',
            'nl' => 'Bosnië en Herzegovina',
        ],
        '18'  => [
            'en' => 'Barbados',
            'nl' => 'Barbados',
        ],
        '19'  => [
            'en' => 'Bangladesh',
            'nl' => 'Bangladesh',
        ],
        '20'  => [
            'en' => 'Belgium',
            'nl' => 'België',
        ],
        '21'  => [
            'en' => 'Burkina Faso',
            'nl' => 'Burkina Faso',
        ],
        '22'  => [
            'en' => 'Bulgaria',
            'nl' => 'Bulgarië',
        ],
        '23'  => [
            'en' => 'Bahrain',
            'nl' => 'Bahrein',
        ],
        '24'  => [
            'en' => 'Burundi',
            'nl' => 'Burundi',
        ],
        '25'  => [
            'en' => 'Benin',
            'nl' => 'Benin',
        ],
        '26'  => [
            'en' => 'Saint Barthélemy',
            'nl' => 'Sint-Bartholomeus',
        ],
        '27'  => [
            'en' => 'Bermuda',
            'nl' => 'Bermuda',
        ],
        '28'  => [
            'en' => 'Brunei',
            'nl' => 'Staat Brunei Darussalam',
        ],
        '29'  => [
            'en' => 'Bolivia',
            'nl' => 'Bolivië',
        ],
        '30'  => [
            'en' => 'Bonaire',
            'nl' => 'Bonaire',
        ],
        '31'  => [
            'en' => 'Brazil',
            'nl' => 'Brazilië',
        ],
        '32'  => [
            'en' => 'Bahamas',
            'nl' => 'Gemenebest van de Bahama\'s',
        ],
        '33'  => [
            'en' => 'Bhutan',
            'nl' => 'Bhutan',
        ],
        '34'  => [
            'en' => 'Bouvet Island',
            'nl' => 'Bouveteiland',
        ],
        '35'  => [
            'en' => 'Republic of Botswana',
            'nl' => 'Botswana',
        ],
        '36'  => [
            'en' => 'Republic of Belarus',
            'nl' => 'Belarus',
        ],
        '37'  => [
            'en' => 'Belize',
            'nl' => 'Belize',
        ],
        '38'  => [
            'en' => 'Canada',
            'nl' => 'Canada',
        ],
        '39'  => [
            'en' => 'Cocos [Keeling] Islands',
            'nl' => 'Cocoseilanden',
        ],
        '40'  => [
            'en' => 'Democratic Republic of the Congo',
            'nl' => 'Democratische Republiek Congo',
        ],
        '41'  => [
            'en' => 'Central African Republic',
            'nl' => 'Centraal-Afrikaanse Republiek',
        ],
        '42'  => [
            'en' => 'Congo',
            'nl' => 'Congo',
        ],
        '43'  => [
            'en' => 'Switzerland',
            'nl' => 'Zwitserland',
        ],
        '44'  => [
            'en' => 'Ivory Coast',
            'nl' => 'Ivoorkust',
        ],
        '45'  => [
            'en' => 'Cook Islands',
            'nl' => 'Cookeilanden',
        ],
        '46'  => [
            'en' => 'Chile',
            'nl' => 'Chili',
        ],
        '47'  => [
            'en' => 'Cameroon',
            'nl' => 'Kameroen',
        ],
        '48'  => [
            'en' => 'China',
            'nl' => 'China',
        ],
        '49'  => [
            'en' => 'Colombia',
            'nl' => 'Colombië',
        ],
        '50'  => [
            'en' => 'Costa Rica',
            'nl' => 'Costa Rica',
        ],
        '51'  => [
            'en' => 'Cuba',
            'nl' => 'Cuba',
        ],
        '52'  => [
            'en' => 'Cape Verde',
            'nl' => 'Kaapverdië',
        ],
        '53'  => [
            'en' => 'Curacao',
            'nl' => 'Curaçao',
        ],
        '54'  => [
            'en' => 'Christmas Island',
            'nl' => 'Kersteiland',
        ],
        '55'  => [
            'en' => 'Cyprus',
            'nl' => 'Cyprus',
        ],
        '56'  => [
            'en' => 'Czech Republic',
            'nl' => 'Tsjechische Republiek',
        ],
        '57'  => [
            'en' => 'Germany',
            'nl' => 'Duitsland',
        ],
        '58'  => [
            'en' => 'Djibouti',
            'nl' => 'Djibouti',
        ],
        '59'  => [
            'en' => 'Denmark',
            'nl' => 'Denemarken',
        ],
        '60'  => [
            'en' => 'Dominica',
            'nl' => 'Dominica',
        ],
        '61'  => [
            'en' => 'Dominican Republic',
            'nl' => 'Dominicaanse Republiek',
        ],
        '62'  => [
            'en' => 'Algeria',
            'nl' => 'Algerije',
        ],
        '63'  => [
            'en' => 'Ecuador',
            'nl' => 'Ecuador',
        ],
        '64'  => [
            'en' => 'Estonia',
            'nl' => 'Estland',
        ],
        '65'  => [
            'en' => 'Egypt',
            'nl' => 'Egypte',
        ],
        '66'  => [
            'en' => 'Western Sahara',
            'nl' => 'Westelijke Sahara',
        ],
        '67'  => [
            'en' => 'Eritrea',
            'nl' => 'Eritrea',
        ],
        '68'  => [
            'en' => 'Spain',
            'nl' => 'Spanje',
        ],
        '69'  => [
            'en' => 'Ethiopia',
            'nl' => 'Ethiopië',
        ],
        '70'  => [
            'en' => 'Finland',
            'nl' => 'Finland',
        ],
        '71'  => [
            'en' => 'Fiji',
            'nl' => 'Fiji',
        ],
        '72'  => [
            'en' => 'Falkland Islands',
            'nl' => 'Falklandeilanden',
        ],
        '73'  => [
            'en' => 'Micronesia',
            'nl' => 'Micronesië',
        ],
        '74'  => [
            'en' => 'Faroe Islands',
            'nl' => 'Faeröer',
        ],
        '75'  => [
            'en' => 'France',
            'nl' => 'Frankrijk',
        ],
        '76'  => [
            'en' => 'Gabon',
            'nl' => 'Gabon',
        ],
        '77'  => [
            'en' => 'United Kingdom',
            'nl' => 'Verenigd Koninkrijk',
        ],
        '78'  => [
            'en' => 'Grenada',
            'nl' => 'Grenada',
        ],
        '79'  => [
            'en' => 'Georgia',
            'nl' => 'Georgië',
        ],
        '80'  => [
            'en' => 'French Guiana',
            'nl' => 'Frans-Guyana',
        ],
        '81'  => [
            'en' => 'Guernsey',
            'nl' => 'Guernsey',
        ],
        '82'  => [
            'en' => 'Ghana',
            'nl' => 'Ghana',
        ],
        '83'  => [
            'en' => 'Gibraltar',
            'nl' => 'Gibraltar',
        ],
        '84'  => [
            'en' => 'Greenland',
            'nl' => 'Groenland',
        ],
        '85'  => [
            'en' => 'Gambia',
            'nl' => 'Gambia',
        ],
        '86'  => [
            'en' => 'Guinea',
            'nl' => 'Guinee',
        ],
        '87'  => [
            'en' => 'Guadeloupe',
            'nl' => 'Guadeloupe',
        ],
        '88'  => [
            'en' => 'Equatorial Guinea',
            'nl' => 'Equatoriaal-Guinea',
        ],
        '89'  => [
            'en' => 'Greece',
            'nl' => 'Griekenland',
        ],
        '90'  => [
            'en' => 'South Georgia and the South Sandwich Islands',
            'nl' => 'Zuid-Georgia en de Zuidelijke Sandwicheilanden',
        ],
        '91'  => [
            'en' => 'Guatemala',
            'nl' => 'Guatemala',
        ],
        '92'  => [
            'en' => 'Guam',
            'nl' => 'Guam',
        ],
        '93'  => [
            'en' => 'Guinea-Bissau',
            'nl' => 'Guinee-Bissau',
        ],
        '94'  => [
            'en' => 'Guyana',
            'nl' => 'Guyana',
        ],
        '95'  => [
            'en' => 'Hong Kong',
            'nl' => 'Hongkong',
        ],
        '96'  => [
            'en' => 'Heard Island and McDonald Islands',
            'nl' => 'Heard en McDonaldeilanden',
        ],
        '97'  => [
            'en' => 'Honduras',
            'nl' => 'Honduras',
        ],
        '98'  => [
            'en' => 'Croatia',
            'nl' => 'Kroatië',
        ],
        '99'  => [
            'en' => 'Haiti',
            'nl' => 'Haïti',
        ],
        '100' => [
            'en' => 'Hungary',
            'nl' => 'Hongarije',
        ],
        '101' => [
            'en' => 'Indonesia',
            'nl' => 'Indonesië',
        ],
        '102' => [
            'en' => 'Ireland',
            'nl' => 'Ierland',
        ],
        '103' => [
            'en' => 'Israel',
            'nl' => 'Israël',
        ],
        '104' => [
            'en' => 'Isle of Man',
            'nl' => 'Man (eiland)',
        ],
        '105' => [
            'en' => 'India',
            'nl' => 'Indië',
        ],
        '106' => [
            'en' => 'British Indian Ocean Territory',
            'nl' => 'Brits Indische Oceaanterritorium',
        ],
        '107' => [
            'en' => 'Iraq',
            'nl' => 'Irak',
        ],
        '108' => [
            'en' => 'Iran',
            'nl' => 'Iran',
        ],
        '109' => [
            'en' => 'Iceland',
            'nl' => 'IJsland',
        ],
        '110' => [
            'en' => 'Italy',
            'nl' => 'Italië',
        ],
        '111' => [
            'en' => 'Jersey',
            'nl' => 'Jersey',
        ],
        '112' => [
            'en' => 'Jamaica',
            'nl' => 'Jamaica',
        ],
        '113' => [
            'en' => 'Jordan',
            'nl' => 'Jordanië',
        ],
        '114' => [
            'en' => 'Japan',
            'nl' => 'Japan',
        ],
        '115' => [
            'en' => 'Kenya',
            'nl' => 'Kenia',
        ],
        '116' => [
            'en' => 'Kyrgyzstan',
            'nl' => 'Kirgizië',
        ],
        '117' => [
            'en' => 'Cambodia',
            'nl' => 'Cambodja',
        ],
        '118' => [
            'en' => 'Kiribati',
            'nl' => 'Kiribati',
        ],
        '119' => [
            'en' => 'Comoros',
            'nl' => 'Comoren',
        ],
        '120' => [
            'en' => 'Saint Kitts and Nevis',
            'nl' => 'Saint Kitts en Nevis',
        ],
        '121' => [
            'en' => 'North Korea',
            'nl' => 'Noord-Korea',
        ],
        '122' => [
            'en' => 'South Korea',
            'nl' => 'Zuid-Korea',
        ],
        '123' => [
            'en' => 'Kuwait',
            'nl' => 'Koeweit',
        ],
        '124' => [
            'en' => 'Cayman Islands',
            'nl' => 'Kaaimaneilanden',
        ],
        '125' => [
            'en' => 'Kazakhstan',
            'nl' => 'Kazachstan',
        ],
        '126' => [
            'en' => 'Laos',
            'nl' => 'Laos',
        ],
        '127' => [
            'en' => 'Lebanon',
            'nl' => 'Libanon',
        ],
        '128' => [
            'en' => 'Saint Lucia',
            'nl' => 'Saint Lucia',
        ],
        '129' => [
            'en' => 'Liechtenstein',
            'nl' => 'Liechtenstein',
        ],
        '130' => [
            'en' => 'Sri Lanka',
            'nl' => 'Sri Lanka',
        ],
        '131' => [
            'en' => 'Liberia',
            'nl' => 'Liberia',
        ],
        '132' => [
            'en' => 'Lesotho',
            'nl' => 'Lesotho',
        ],
        '133' => [
            'en' => 'Lithuania',
            'nl' => 'Litouwen',
        ],
        '134' => [
            'en' => 'Luxembourg',
            'nl' => 'Luxemburg',
        ],
        '135' => [
            'en' => 'Latvia',
            'nl' => 'Letland',
        ],
        '136' => [
            'en' => 'Libya',
            'nl' => 'Libië',
        ],
        '137' => [
            'en' => 'Morocco',
            'nl' => 'Marokko',
        ],
        '138' => [
            'en' => 'Monaco',
            'nl' => 'Monaco',
        ],
        '139' => [
            'en' => 'Moldova',
            'nl' => 'Moldova',
        ],
        '140' => [
            'en' => 'Montenegro',
            'nl' => 'Montenegro',
        ],
        '141' => [
            'en' => 'Saint Martin',
            'nl' => 'Saint-Martin',
        ],
        '142' => [
            'en' => 'Madagascar',
            'nl' => 'Madagaskar',
        ],
        '143' => [
            'en' => 'Marshall Islands',
            'nl' => 'Marshalleilanden',
        ],
        '144' => [
            'en' => 'Macedonia',
            'nl' => 'Macedonië',
        ],
        '145' => [
            'en' => 'Mali',
            'nl' => 'Mali',
        ],
        '146' => [
            'en' => 'Myanmar [Burma]',
            'nl' => 'Myanmar [Birma]',
        ],
        '147' => [
            'en' => 'Mongolia',
            'nl' => 'Mongolië',
        ],
        '148' => [
            'en' => 'Macao',
            'nl' => 'Macao',
        ],
        '149' => [
            'en' => 'Northern Mariana Islands',
            'nl' => 'Noordelijke Marianen',
        ],
        '150' => [
            'en' => 'Martinique',
            'nl' => 'Martinique',
        ],
        '151' => [
            'en' => 'Mauritania',
            'nl' => 'Mauritanië',
        ],
        '152' => [
            'en' => 'Montserrat',
            'nl' => 'Montserrat',
        ],
        '153' => [
            'en' => 'Malta',
            'nl' => 'Malta',
        ],
        '154' => [
            'en' => 'Mauritius',
            'nl' => 'Mauritius',
        ],
        '155' => [
            'en' => 'Maldives',
            'nl' => 'Maldiven',
        ],
        '156' => [
            'en' => 'Malawi',
            'nl' => 'Malawi',
        ],
        '157' => [
            'en' => 'Mexico',
            'nl' => 'Mexico',
        ],
        '158' => [
            'en' => 'Malaysia',
            'nl' => 'Maleisië',
        ],
        '159' => [
            'en' => 'Mozambique',
            'nl' => 'Mozambique',
        ],
        '160' => [
            'en' => 'Namibia',
            'nl' => 'Namibië',
        ],
        '161' => [
            'en' => 'New Caledonia',
            'nl' => 'Nieuw-Caledonië',
        ],
        '162' => [
            'en' => 'Niger',
            'nl' => 'Niger',
        ],
        '163' => [
            'en' => 'Norfolk Island',
            'nl' => 'Norfolk (eiland)
',
        ],
        '164' => [
            'en' => 'Nigeria',
            'nl' => 'Nigerië',
        ],
        '165' => [
            'en' => 'Nicaragua',
            'nl' => 'Nicaragua',
        ],
        '166' => [
            'en' => 'Netherlands',
            'nl' => 'Nederland',
        ],
        '167' => [
            'en' => 'Norway',
            'nl' => 'Noorwegen',
        ],
        '168' => [
            'en' => 'Nepal',
            'nl' => 'Nepal',
        ],
        '169' => [
            'en' => 'Nauru',
            'nl' => 'Nauru',
        ],
        '170' => [
            'en' => 'Niue',
            'nl' => 'Niue',
        ],
        '171' => [
            'en' => 'New Zealand',
            'nl' => 'Nieuw-Zeeland',
        ],
        '172' => [
            'en' => 'Oman',
            'nl' => 'Oman',
        ],
        '173' => [
            'en' => 'Panama',
            'nl' => 'Panama',
        ],
        '174' => [
            'en' => 'Peru',
            'nl' => 'Peru',
        ],
        '175' => [
            'en' => 'French Polynesia',
            'nl' => 'Frans-Polynesië',
        ],
        '176' => [
            'en' => 'Papua New Guinea',
            'nl' => 'Papoea-Nieuw-Guinea',
        ],
        '177' => [
            'en' => 'Philippines',
            'nl' => 'Filipijnen',
        ],
        '178' => [
            'en' => 'Pakistan',
            'nl' => 'Pakistan',
        ],
        '179' => [
            'en' => 'Poland',
            'nl' => 'Polen',
        ],
        '180' => [
            'en' => 'Saint Pierre and Miquelon',
            'nl' => 'Saint Pierre en Miquelon',
        ],
        '181' => [
            'en' => 'Pitcairn Islands',
            'nl' => 'Pitcairneilanden',
        ],
        '182' => [
            'en' => 'Puerto Rico',
            'nl' => 'Puerto Rico',
        ],
        '183' => [
            'en' => 'Palestine',
            'nl' => 'Palestina',
        ],
        '184' => [
            'en' => 'Portugal',
            'nl' => 'Portugal',
        ],
        '185' => [
            'en' => 'Palau',
            'nl' => 'Palau',
        ],
        '186' => [
            'en' => 'Paraguay',
            'nl' => 'Paraguay',
        ],
        '187' => [
            'en' => 'Qatar',
            'nl' => 'Qatar',
        ],
        '188' => [
            'en' => 'Réunion',
            'nl' => 'Réunion',
        ],
        '189' => [
            'en' => 'Romania',
            'nl' => 'Roemenië',
        ],
        '190' => [
            'en' => 'Serbia',
            'nl' => 'Servië',
        ],
        '191' => [
            'en' => 'Russia',
            'nl' => 'Rusland',
        ],
        '192' => [
            'en' => 'Rwanda',
            'nl' => 'Rwanda',
        ],
        '193' => [
            'en' => 'Saudi Arabia',
            'nl' => 'Saoedi-Arabië',
        ],
        '194' => [
            'en' => 'Solomon Islands',
            'nl' => 'Salomonseilanden',
        ],
        '195' => [
            'en' => 'Seychelles',
            'nl' => 'Seychellen',
        ],
        '196' => [
            'en' => 'Sudan',
            'nl' => 'Soedan',
        ],
        '197' => [
            'en' => 'Sweden',
            'nl' => 'Zweden',
        ],
        '198' => [
            'en' => 'Singapore',
            'nl' => 'Singapore',
        ],
        '199' => [
            'en' => 'Saint Helena',
            'nl' => 'Sint-Helena',
        ],
        '200' => [
            'en' => 'Slovenia',
            'nl' => 'Slovenië',
        ],
        '201' => [
            'en' => 'Svalbard and Jan Mayen',
            'nl' => 'Spitsbergen en Jan Mayen',
        ],
        '202' => [
            'en' => 'Slovakia',
            'nl' => 'Slowakije',
        ],
        '203' => [
            'en' => 'Sierra Leone',
            'nl' => 'Sierra Leone',
        ],
        '204' => [
            'en' => 'San Marino',
            'nl' => 'San Marino',
        ],
        '205' => [
            'en' => 'Senegal',
            'nl' => 'Senegal',
        ],
        '206' => [
            'en' => 'Somalia',
            'nl' => 'Somalië',
        ],
        '207' => [
            'en' => 'Suriname',
            'nl' => 'Suriname',
        ],
        '208' => [
            'en' => 'South Sudan',
            'nl' => 'Zuid-Soedan',
        ],
        '209' => [
            'en' => 'São Tomé and Príncipe',
            'nl' => 'Sao Tomé en Principe',
        ],
        '210' => [
            'en' => 'El Salvador',
            'nl' => 'El Salvador',
        ],
        '211' => [
            'en' => 'Sint Maarten',
            'nl' => 'Sint Maarten',
        ],
        '212' => [
            'en' => 'Syria',
            'nl' => 'Syrië',
        ],
        '213' => [
            'en' => 'Swaziland',
            'nl' => 'Swaziland',
        ],
        '214' => [
            'en' => 'Turks and Caicos Islands',
            'nl' => 'Turks- en Caicoseilanden',
        ],
        '215' => [
            'en' => 'Chad',
            'nl' => 'Tsjaad',
        ],
        '216' => [
            'en' => 'French Southern Territories',
            'nl' => 'Franse Zuidelijke en Antarctische Gebieden',
        ],
        '217' => [
            'en' => 'Togo',
            'nl' => 'Togo',
        ],
        '218' => [
            'en' => 'Thailand',
            'nl' => 'Thailand',
        ],
        '219' => [
            'en' => 'Tajikistan',
            'nl' => 'Tadzjikistan',
        ],
        '220' => [
            'en' => 'Tokelau',
            'nl' => 'Tokelau',
        ],
        '221' => [
            'en' => 'East Timor',
            'nl' => 'Oost-Timor',
        ],
        '222' => [
            'en' => 'Turkmenistan',
            'nl' => 'Turkmenistan',
        ],
        '223' => [
            'en' => 'Tunisia',
            'nl' => 'Tunesië',
        ],
        '224' => [
            'en' => 'Tonga',
            'nl' => 'Tonga',
        ],
        '225' => [
            'en' => 'Turkey',
            'nl' => 'Turkije',
        ],
        '226' => [
            'en' => 'Trinidad and Tobago',
            'nl' => 'Trinidad en Tobago',
        ],
        '227' => [
            'en' => 'Tuvalu',
            'nl' => 'Tuvalu',
        ],
        '228' => [
            'en' => 'Taiwan',
            'nl' => 'Taiwan',
        ],
        '229' => [
            'en' => 'Tanzania',
            'nl' => 'Tanzania',
        ],
        '230' => [
            'en' => 'Ukraine',
            'nl' => 'Oekraïne',
        ],
        '231' => [
            'en' => 'Uganda',
            'nl' => 'Oeganda',
        ],
        '232' => [
            'en' => 'U.S. Minor Outlying Islands',
            'nl' => 'Kleine afgelegen eilanden van de Verenigde Staten',
        ],
        '233' => [
            'en' => 'United States',
            'nl' => 'Verenigde Staten',
        ],
        '234' => [
            'en' => 'Uruguay',
            'nl' => 'Uruguay',
        ],
        '235' => [
            'en' => 'Uzbekistan',
            'nl' => 'Oezbekistan',
        ],
        '236' => [
            'en' => 'Vatican City',
            'nl' => 'Vaticaanstad',
        ],
        '237' => [
            'en' => 'Saint Vincent and the Grenadines',
            'nl' => 'Saint Vincent en de Grenadines',
        ],
        '238' => [
            'en' => 'Venezuela',
            'nl' => 'Venezuela',
        ],
        '239' => [
            'en' => 'British Virgin Islands',
            'nl' => 'Britse Maagdeneilanden',
        ],
        '240' => [
            'en' => 'U.S. Virgin Islands',
            'nl' => 'Amerikaanse Maagdeneilanden',
        ],
        '241' => [
            'en' => 'Vietnam',
            'nl' => 'Vietnam',
        ],
        '242' => [
            'en' => 'Vanuatu',
            'nl' => 'Vanuatu',
        ],
        '243' => [
            'en' => 'Wallis and Futuna',
            'nl' => 'Wallis en Futuna',
        ],
        '244' => [
            'en' => 'Samoa',
            'nl' => 'Samoa',
        ],
        '245' => [
            'en' => 'Kosovo',
            'nl' => 'Kosovo',
        ],
        '246' => [
            'en' => 'Yemen',
            'nl' => 'Jemen',
        ],
        '247' => [
            'en' => 'Mayotte',
            'nl' => 'Mayotte',
        ],
        '248' => [
            'en' => 'South Africa',
            'nl' => 'Zuid-Afrika',
        ],
        '249' => [
            'en' => 'Zambia',
            'nl' => 'Zambia',
        ],
        '250' => [
            'en' => 'Zimbabwe',
            'nl' => 'Zimbabwe',
        ],
    ];
    foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) as $oLocale) {
        foreach ($aTranslations as $iCountryId => $aTranslation) {
            if (!CountryTranslationManager::getCountryTranslationByCountryId($iCountryId, $oLocale->localeId)) {
                $aLogs[$sModuleName]['errors'][] = 'Missing country translation for country `' . $iCountryId . '` in locale `' . $oLocale->getLocale() . '`';
                if ($bInstall) {
                    $oTranslation = new CountryTranslation(
                        [
                            'countryId' => $iCountryId,
                            'localeId'  => $oLocale->localeId,
                            'name'      => $aTranslation[$oLocale->getLanguage()->code] ?? $aTranslation['en'],
                        ]
                    );
                    CountryTranslationManager::saveCountryTranslation($oTranslation);
                }
            }
        }
    }
}

/* external sync history */
// create table external_sync_providers
if (!$oDb->tableExists('external_sync_providers')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `external_sync_providers`';
    if ($bInstall) {
        // add table
        $sQuery = '
            CREATE TABLE `external_sync_providers` (
              `externalSyncProviderId` INT NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
              `item` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
              `connector` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
              `created` TIMESTAMP NULL DEFAULT NULL,
              `createdby` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
              `modified` TIMESTAMP NULL DEFAULT NULL,
              `modifiedBy` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
              PRIMARY KEY (`externalSyncProviderId`),
              INDEX `item` (`item` ASC))
            ENGINE = InnoDB
                ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// create table external_syncs
if (!$oDb->tableExists('external_syncs')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `external_syncs`';
    if ($bInstall) {
        // add table
        $sQuery = '
            CREATE TABLE `external_syncs` (
              `externalSyncId` INT NOT NULL AUTO_INCREMENT,
              `item` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
              `itemId` INT NOT NULL,
              `externalSyncProviderId` INT NOT NULL,
              `lastSynced` TIMESTAMP NULL,
              `synced` TINYINT(1) NULL DEFAULT 0,
              `lastError` TEXT NULL,
              `externalId` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
              `created` TIMESTAMP NULL DEFAULT NULL,
              `createdby` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
              `modified` TIMESTAMP NULL DEFAULT NULL,
              `modifiedBy` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
              PRIMARY KEY (`externalSyncId`),
              INDEX `external_syncs_connector` (`externalSyncProviderId` ASC),
              INDEX `item` (`item` ASC),
              INDEX `itemId` (`itemId` ASC),
              INDEX `itemItemIdExternalSyncProvider` (`item` ASC, `itemId` ASC, `externalSyncProviderId` ASC),
              CONSTRAINT `external_syncs_externalSyncProviderId`
                FOREIGN KEY (`externalSyncProviderId`)
                REFERENCES `external_sync_providers` (`externalSyncProviderId`)
                ON DELETE CASCADE
                ON UPDATE CASCADE)
            ENGINE = InnoDB
                ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// check external_syncs constraints
if ($oDb->tableExists('external_syncs')) {
    if ($oDb->tableExists('external_sync_providers')) {
        // check external_sync_providers constraint
        if (!$oDb->constraintExists('external_syncs', 'externalSyncProviderId', 'external_sync_providers', 'externalSyncProviderId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `external_syncs`.`externalSyncProviderId` => `external_sync_providers`.`externalSyncProviderId`';
            if ($bInstall) {
                $oDb->addConstraint('external_syncs', 'externalSyncProviderId', 'external_sync_providers', 'externalSyncProviderId', 'CASCADE', 'CASCADE');
            }
        }
    }
}
/* /external sync history */

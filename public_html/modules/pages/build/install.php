<?php

// check folders existance and writing rights
if (moduleExists('pages')) {
    $aCheckRightFolders = [
        Page::FILES_PATH => true,
    ];

    // get settings for module and template and create all images folders
    $aImageSettings = TemplateSettings::get('pages', 'images');
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
}

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'paginas' => [
        'module'     => 'pages',
        'controller' => 'page',
    ],
];

$aNeededClassRoutes = [
    'Page'        => [
        'module' => 'pages',
    ],
    'PageManager' => [
        'module' => 'pages',
    ],
];

$aNeededSiteControllerRoutes = [
    'errors' => [
        'module'     => 'pages',
        'controller' => 'errors',
    ],
    '404'    => [
        'module'     => 'pages',
        'controller' => 'errors',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'          => 'paginas',
        'linkName'      => 'pages_menu',
        'icon'          => 'fa-file-text-o',
        'moduleActions' => [
            [
                'displayName' => 'Volledig',
                'name'        => 'pages_full',
            ],
        ],
    ],
];

$aNeededTranslations = [
    'es' => [
        ['label' => 'global_homepage', 'text' => 'Homepage'],
        ['label' => 'global_intro', 'text' => 'Introducción'],
        ['label' => 'pages_unique_name', 'text' => 'System name'],
        ['label' => 'pages_unique_name_tooltip', 'text' => 'Choose a unique system name'],
        ['label' => 'pages_menu', 'text' => 'Administración de páginas'],
        ['label' => 'pages_video_warning', 'text' => 'Los vídeo de Video pueden añadirse después de que la página se guarde por primera vez'],
        [
            'label' => 'pages_url_tooltip_2',
            'text'  => '<b> [texto url] </b><br/>-Sólo se permiten letras, números y \'-\', cualquier carácter diferente ser´reemplazado por \'-\' <br/>-Por ejemplo: \'cms system\' se convierte en \'cms-system\'',
        ],
        ['label' => 'pages_url_tooltip', 'text' => 'Introduzca el texto que se añadirá a la base de la url <br/> por ejemplo:'],
        ['label' => 'pages_url_parameters_tooltip_2', 'text' => '/<b>[texto url]?[parámetros de url]</b><br/>'],
        ['label' => 'pages_url_parameters_tooltip_1', 'text' => 'Introduzca el texto que se añadirá a la base de la url <br/> por ejemplo:'],
        ['label' => 'pages_url_parameters', 'text' => 'Parámetros de URL'],
        ['label' => 'pages_title_tooltip', 'text' => 'Introduzca el título de la página'],
        ['label' => 'pages_link_min_3_tooltip', 'text' => 'Vul minimaal 3 karakters in bij de menu link'],
        ['label' => 'pages_structure_saved', 'text' => 'Estructura de la página guardada'],
        ['label' => 'pages_saved', 'text' => 'Página guardada'],
        ['label' => 'pages_page', 'text' => 'Página'],
        ['label' => 'pages_online_tooltip', 'text' => 'Activar página'],
        ['label' => 'pages_online_changeable_tooltip', 'text' => '¿La página se puede activar y desactivar?'],
        ['label' => 'pages_online_changeable', 'text' => 'Activar/desactivar'],
        ['label' => 'pages_online', 'text' => 'Activar página'],
        ['label' => 'pages_offline_tooltip', 'text' => 'Desactivar página'],
        ['label' => 'pages_offline', 'text' => 'Desactivar página'],
        ['label' => 'pages_not_saved', 'text' => 'Página no se ha guardado, no todos los campos están (correctamente) completados'],
        ['label' => 'pages_not_offline', 'text' => 'Esta página no se puede desactivar'],
        ['label' => 'pages_not_in_menu', 'text' => 'La página no se muestra en el menú'],
        ['label' => 'pages_not_editable_2', 'text' => 'La página está protegida y no se puede editar'],
        ['label' => 'pages_not_editable', 'text' => 'La página está protegida y no se puede editar'],
        ['label' => 'pages_not_deleted', 'text' => 'No se puede eliminar página'],
        ['label' => 'pages_not_deletable', 'text' => 'Primero debe eliminar todas las sub páginas y comprobar que tiene los derechos para realizar esta acción'],
        ['label' => 'pages_not_changed', 'text' => 'La página no ha cambiado'],
        ['label' => 'pages_no_video_tooltip', 'text' => '¿Debe tener la página administración de enlaces de Video?'],
        ['label' => 'pages_no_video', 'text' => 'Ocultar administración de enlaces de Video'],
        ['label' => 'pages_no_pages', 'text' => 'No existen páginas para mostrar'],
        ['label' => 'pages_no_links_tooltip', 'text' => '¿Debe tener la página administración de links?'],
        ['label' => 'pages_no_links', 'text' => 'Ocultar la administración de links'],
        ['label' => 'pages_no_images_tooltip', 'text' => '¿Debe tener la página administración de imágenes?'],
        ['label' => 'pages_no_images', 'text' => 'Ocultar la administración de imagenes'],
        ['label' => 'pages_no_files_tooltip', 'text' => '¿Debe tener la página administrador de archivos?'],
        ['label' => 'pages_no_files', 'text' => 'Ocultar la administración de archivos'],
        ['label' => 'pages_no_form', 'text' => 'Ocultar la administración de archivos'],
        ['label' => 'pages_manager', 'text' => 'Administración de páginas'],
        ['label' => 'pages_lock_path_tooltip', 'text' => '¿Bloquear url?'],
        ['label' => 'pages_lock_path', 'text' => 'Bloquear url'],
        ['label' => 'pages_lock_parent_tooltip', 'text' => '¿Es la página prinicpal?'],
        ['label' => 'pages_lock_parent', 'text' => 'Página prinicpal'],
        ['label' => 'pages_links_warning', 'text' => 'Los enlaces pueden añadirse después de que la página se guarde por primera vez'],
        ['label' => 'pages_link_tooltip', 'text' => 'Completar este campo para utilizarlo en lugar del título. Este campo se mostrará en el menú. Si lo deja en blanco, se utilizará el título.'],
        ['label' => 'pages_info', 'text' => 'Información de la página'],
        ['label' => 'pages_in_menu_tooltip', 'text' => '¿Se muestra la página en el menú?'],
        ['label' => 'pages_in_menu', 'text' => 'En el menú'],
        ['label' => 'pages_images_warning', 'text' => 'Las imágenes se pueden cargar después de que la página se guarda por primera vez'],
        ['label' => 'pages_has_subpages_tooltip', 'text' => '¿Puede esta página tener sub páginas?'],
        ['label' => 'pages_has_subpages', 'text' => 'Puede tener sub páginas'],
        ['label' => 'pages_files_warning', 'text' => 'Los archivos se pueden cargar después de que la página se guarde por primera vez'],
        ['label' => 'pages_edition', 'text' => 'Editar Página'],
        ['label' => 'pages_editable_tooltip', 'text' => '¿Puede editarse la página?'],
        ['label' => 'pages_edit', 'text' => 'Editar Página'],
        ['label' => 'pages_drag_title', 'text' => 'Arrastre los títulos para modificar el orden'],
        ['label' => 'pages_deleted', 'text' => 'Página eliminada'],
        ['label' => 'pages_deletable_tooltip', 'text' => '¿Desea eliminar la página?'],
        ['label' => 'pages_controller_path', 'text' => 'Ruta del controlador'],
        ['label' => 'pages_change_structure', 'text' => 'Modificar el orden'],
        ['label' => 'pages_all', 'text' => 'Todas las páginas'],
        ['label' => 'pages_admin_settings', 'text' => 'Configuración por el administrador'],
        ['label' => 'pages_add_sub_tooltip', 'text' => 'Añadir una sub página a esta página'],
        ['label' => 'pages_add_sub', 'text' => 'Añadir sub página'],
        ['label' => 'pages_indexable', 'text' => 'Indexable'],
        ['label' => 'pages_indexable_tooltip', 'text' => 'Indexable'],
        ['label' => 'global_not_indexable', 'text' => 'No indexable'],
        ['label' => 'pages_link_form', 'text' => 'Link form'],
        ['label' => 'autocomplete_usps_title', 'text' => 'Unique selling points'],
    ],
    'en' => [
        ['label' => 'global_homepage', 'text' => 'Homepage'],
        ['label' => 'global_intro', 'text' => 'Introduction'],
        ['label' => 'pages_unique_name', 'text' => 'System name'],
        ['label' => 'pages_unique_name_tooltip', 'text' => 'Choose a unique system name'],
        ['label' => 'pages_menu', 'text' => 'Page management'],
        ['label' => 'pages_video_warning', 'text' => 'Video\'s can be added after the page is saved first'],
        ['label' => 'pages_url_tooltip_2', 'text' => '<b> [url text] </b> < br/>-only letters, numbers and \'-\' is < br/> allowed-all characters other than indicated may be replaced by a \'-\' < br/>-vb: \' cms \' is \' cms-system \''],
        ['label' => 'pages_url_tooltip', 'text' => 'Choose the text needs to be rendered on the basis of which the url < br/> for example:'],
        ['label' => 'pages_url_parameters_tooltip_2', 'text' => '/<b> [url text]?[url parameters] </b> < br/>'],
        ['label' => 'pages_url_parameters_tooltip_1', 'text' => 'Choose the text needs to be rendered on the basis of which the url < br/> for example:'],
        ['label' => 'pages_url_parameters', 'text' => 'Url parameters'],
        ['label' => 'pages_title_tooltip', 'text' => 'Enter the page title in (min. 3 characters)'],
        ['label' => 'pages_link_min_3_tooltip', 'text' => 'Enter at least 3 characters at the menu link'],
        ['label' => 'pages_structure_saved', 'text' => 'Page structure is stored'],
        ['label' => 'pages_saved', 'text' => 'Page has been saved'],
        ['label' => 'pages_page', 'text' => 'Page'],
        ['label' => 'pages_online_tooltip', 'text' => 'Page online'],
        ['label' => 'pages_online_changeable_tooltip', 'text' => 'Can the page be set online/offline?'],
        ['label' => 'pages_online_changeable', 'text' => 'online changeable'],
        ['label' => 'pages_online', 'text' => 'Page put online'],
        ['label' => 'pages_offline_tooltip', 'text' => 'Page offline'],
        ['label' => 'pages_offline', 'text' => 'Page offline'],
        ['label' => 'pages_not_saved', 'text' => 'Page has not been saved, not all fields are filled in (correctly)'],
        ['label' => 'pages_not_offline', 'text' => 'This page cannot be put offline'],
        ['label' => 'pages_not_in_menu', 'text' => 'not shown in the menu'],
        ['label' => 'pages_not_editable_2', 'text' => 'Page cannot be edited'],
        ['label' => 'pages_not_editable', 'text' => 'Page is protected for edit'],
        ['label' => 'pages_not_deleted', 'text' => 'Page cannot be deleted'],
        ['label' => 'pages_not_deletable', 'text' => 'First remove all sub € ˜ s page and check out the rights'],
        ['label' => 'pages_not_changed', 'text' => 'Page not changed'],
        ['label' => 'pages_no_video_tooltip', 'text' => 'Page has no Video link management?'],
        ['label' => 'pages_no_video', 'text' => 'hide Video link management'],
        ['label' => 'pages_no_pages', 'text' => 'There are no pages to display'],
        ['label' => 'pages_no_links_tooltip', 'text' => 'Page has no link management?'],
        ['label' => 'pages_no_links', 'text' => 'hide link management'],
        ['label' => 'pages_no_images_tooltip', 'text' => 'Page has no images management?'],
        ['label' => 'pages_no_images', 'text' => 'Hide image management'],
        ['label' => 'pages_no_files_tooltip', 'text' => 'Page has no file manager?'],
        ['label' => 'pages_no_files', 'text' => 'hide file management'],
        ['label' => 'pages_no_form', 'text' => 'hide form management'],
        ['label' => 'pages_manager', 'text' => 'Pages management'],
        ['label' => 'pages_lock_path_tooltip', 'text' => 'Page has fixed url?'],
        ['label' => 'pages_lock_path', 'text' => 'lock url path'],
        ['label' => 'pages_lock_parent_tooltip', 'text' => 'Page, main page?'],
        ['label' => 'pages_lock_parent', 'text' => 'parent lock'],
        ['label' => 'pages_links_warning', 'text' => 'Links can be added after the page is saved first'],
        ['label' => 'pages_link_tooltip', 'text' => 'To fill in this field instead of the title, the completed text to use in the menu. At leave blank, the title is used.'],
        ['label' => 'pages_info', 'text' => 'Page data'],
        ['label' => 'pages_in_menu_tooltip', 'text' => 'Page show in menu?'],
        ['label' => 'pages_in_menu', 'text' => 'in menu'],
        ['label' => 'pages_images_warning', 'text' => 'Images can be uploaded after the page is saved first'],
        ['label' => 'pages_has_subpages_tooltip', 'text' => 'Page can have subpages?'],
        ['label' => 'pages_has_subpages', 'text' => 'may have sub'],
        ['label' => 'pages_files_warning', 'text' => 'Files can be uploaded after the page is saved first'],
        ['label' => 'pages_edition', 'text' => 'edit page'],
        ['label' => 'pages_editable_tooltip', 'text' => 'Can edit the page?'],
        ['label' => 'pages_edit', 'text' => 'Edit page'],
        ['label' => 'pages_drag_title', 'text' => 'Drag the page titles to the structure'],
        ['label' => 'pages_deleted', 'text' => 'Page has been removed'],
        ['label' => 'pages_deletable_tooltip', 'text' => 'Delete the page?'],
        ['label' => 'pages_controller_path', 'text' => 'controller path'],
        ['label' => 'pages_change_structure', 'text' => 'Change page structure'],
        ['label' => 'pages_all', 'text' => 'All pages'],
        ['label' => 'pages_admin_settings', 'text' => 'Admin settings'],
        ['label' => 'pages_add_sub_tooltip', 'text' => 'Sub page under Add this page'],
        ['label' => 'pages_add_sub', 'text' => 'Add sub'],
        ['label' => 'pages_add_main', 'text' => 'Add main page'],
        ['label' => 'pages_indexable', 'text' => 'Indexable'],
        ['label' => 'pages_indexable_tooltip', 'text' => 'Set if page is indexable'],
        ['label' => 'global_not_indexable', 'text' => 'Not indexable'],
        ['label' => 'pages_copy_structure', 'text' => 'Copy pages structure'],
        ['label' => 'pages_languageId', 'text' => 'Language'],
        ['label' => 'pages_languageId_title', 'text' => 'Choose a language'],
        ['label' => 'pages_copy_to', 'text' => 'Copy to'],
        ['label' => 'pages_structure_copied', 'text' => 'Structure copied'],
        ['label' => 'pages_copy_structure', 'text' => 'Copy page structure'],
        ['label' => 'pages_link_form', 'text' => 'Link form'],
        ['label' => 'pages_include_parent_in_url_path', 'text' => 'Include URL of parent page?'],
        ['label' => 'pages_include_parent_in_url_path_tooltip', 'text' => 'Decide if this page needs the URL from the parent page or not'],
        ['label' => 'autocomplete_usps_title', 'text' => 'Unique selling points'],
    ],
    'nl' => [
        ['label' => 'global_homepage', 'text' => 'Homepage'],
        ['label' => 'pages_no_brandbox_tooltip', 'text' => 'verberg brandbox management'],
        ['label' => 'pages_no_brandbox', 'text' => 'verberg brandbox management'],
        ['label' => 'pages_in_footer_tooltip', 'text' => 'Toon de pagina in de footer ja/nee'],
        ['label' => 'pages_in_footer', 'text' => 'In footer'],
        ['label' => 'settings_layout', 'text' => 'Layout instellingen'],
        ['label' => 'global_theme_color', 'text' => 'Theme color'],
        ['label' => 'global_intro', 'text' => 'Introductie'],
        ['label' => 'pages_unique_name', 'text' => 'System name'],
        ['label' => 'pages_unique_name_tooltip', 'text' => 'Choose a unique system name'],
        ['label' => 'pages_menu', 'text' => 'Pagina beheer'],
        ['label' => 'pages_video_warning', 'text' => 'Video\'s kunnen worden toegevoegd nadat de pagina eerst is opgeslagen'],
        [
            'label' => 'pages_url_tooltip_2',
            'text'  => '<b>[url tekst]</b><br />- alleen letters, cijfers en een `-` is toegestaan<br />- alle tekens anders dan aangegeven worden vervangen door een `-`<br />- vb: `cms systeem` wordt `cms-systeem`',
        ],
        ['label' => 'pages_url_tooltip', 'text' => 'Kies zelf de tekst op basis waarvan de url moet worden gegenereerd<br /> Bijvoorbeeld:'],
        ['label' => 'pages_url_parameters_tooltip_2', 'text' => '/<b>[url tekst]?[url parameters]</b><br />'],
        ['label' => 'pages_url_parameters_tooltip_1', 'text' => 'Kies zelf de tekst op basis waarvan de url moet worden gegenereerd<br />Bijvoorbeeld:'],
        ['label' => 'pages_url_parameters', 'text' => 'Url parameters'],
        ['label' => 'pages_title_tooltip', 'text' => 'Vul de pagina titel in van minimaal 3 karakters'],
        ['label' => 'pages_link_min_3_tooltip', 'text' => 'Vul minimaal 3 karakters in bij de menu link'],
        ['label' => 'pages_structure_saved', 'text' => 'Paginastructuur is opgeslagen'],
        ['label' => 'pages_saved', 'text' => 'Pagina is opgeslagen'],
        ['label' => 'pages_page', 'text' => 'Pagina'],
        ['label' => 'pages_online_tooltip', 'text' => 'Pagina online zetten'],
        ['label' => 'pages_online_changeable_tooltip', 'text' => 'Kan de pagina online/offline gezet worden?'],
        ['label' => 'pages_online_changeable', 'text' => 'online changeable'],
        ['label' => 'pages_online', 'text' => 'Pagina online gezet'],
        ['label' => 'pages_offline_tooltip', 'text' => 'Pagina offline zetten'],
        ['label' => 'pages_offline', 'text' => 'Pagina offline gezet'],
        ['label' => 'pages_not_saved', 'text' => 'Pagina is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'pages_not_offline', 'text' => 'Deze pagina kan niet offline gezet worden'],
        ['label' => 'pages_not_in_menu', 'text' => 'niet getoond in het menu'],
        ['label' => 'pages_not_editable_2', 'text' => 'Pagina kan niet worden bewerkt'],
        ['label' => 'pages_not_editable', 'text' => 'Pagina is beveiligd voor bewerken'],
        ['label' => 'pages_not_deleted', 'text' => 'Pagina kan niet worden verwijderd'],
        ['label' => 'pages_not_deletable', 'text' => 'Verwijder eerst alle subpagina\'s en check de rechten'],
        ['label' => 'pages_not_changed', 'text' => 'Pagina niet gewizijd'],
        ['label' => 'pages_no_video_tooltip', 'text' => 'Pagina heeft geen Videolink beheer?'],
        ['label' => 'pages_no_video', 'text' => 'verberg Videomanagement'],
        ['label' => 'pages_no_pages', 'text' => 'Er zijn geen pagina\'s weer te geven'],
        ['label' => 'pages_no_links_tooltip', 'text' => 'Pagina heeft geen linkbeheer?'],
        ['label' => 'pages_no_links', 'text' => 'verberg link management'],
        ['label' => 'pages_no_images_tooltip', 'text' => 'Pagina heeft geen afbeeldingenbeheer?'],
        ['label' => 'pages_no_images', 'text' => 'Verberg image management'],
        ['label' => 'pages_no_files_tooltip', 'text' => 'Pagina heeft geen bestandenbeheer?'],
        ['label' => 'pages_no_files', 'text' => 'verberg file management'],
        ['label' => 'pages_no_form', 'text' => 'verberg form management'],
        ['label' => 'pages_manager', 'text' => 'Pages beheer'],
        ['label' => 'pages_lock_path_tooltip', 'text' => 'Pagina heeft vaste url?'],
        ['label' => 'pages_lock_path', 'text' => 'lock url path'],
        ['label' => 'pages_lock_parent_tooltip', 'text' => 'Pagina heeft hoofdpagina?'],
        ['label' => 'pages_lock_parent', 'text' => 'lock parent'],
        ['label' => 'pages_links_warning', 'text' => 'Links kunnen worden toegevoegd nadat de pagina is opgeslagen'],
        ['label' => 'pages_link_tooltip', 'text' => 'Vul dit veld in om in plaats van de titel, de ingevulde tekst te gebruiken in het menu. Bij leeg laten wordt de titel gebruikt.'],
        ['label' => 'pages_info', 'text' => 'Pagina gegevens'],
        ['label' => 'pages_in_menu_tooltip', 'text' => 'Pagina tonen in menu?'],
        ['label' => 'pages_in_menu', 'text' => 'in menu'],
        ['label' => 'pages_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat de pagina is opgeslagen'],
        ['label' => 'pages_has_subpages_tooltip', 'text' => 'Pagina kan subpagina\'s hebben?'],
        ['label' => 'pages_has_subpages', 'text' => 'may have sub'],
        ['label' => 'pages_files_warning', 'text' => 'Bestanden kunnen worden geüpload nadat de pagina is opgeslagen'],
        ['label' => 'pages_edition', 'text' => 'pagina bewerken'],
        ['label' => 'pages_editable_tooltip', 'text' => 'Kan de pagina bewerken?'],
        ['label' => 'pages_edit', 'text' => 'Pagina bewerken'],
        ['label' => 'pages_drag_title', 'text' => 'Sleep de paginatitels om de structuur te veranderen'],
        ['label' => 'pages_deleted', 'text' => 'Pagina is verwijderd'],
        ['label' => 'pages_deletable_tooltip', 'text' => 'Kan de pagina verwijderen?'],
        ['label' => 'pages_controller_path', 'text' => 'controller path'],
        ['label' => 'pages_change_structure', 'text' => 'Paginastructuur wijzigen'],
        ['label' => 'pages_all', 'text' => 'Alle pagina\'s'],
        ['label' => 'pages_admin_settings', 'text' => 'Admin instellingen'],
        ['label' => 'pages_add_sub_tooltip', 'text' => 'Subpagina onder deze pagina toevoegen'],
        ['label' => 'pages_add_sub', 'text' => 'toevoegen'],
        ['label' => 'pages_add_main', 'text' => 'Hoofdpagina toevoegen'],
        ['label' => 'pages_indexable', 'text' => 'Indexeerbaar'],
        ['label' => 'pages_indexable_tooltip', 'text' => 'Kies of de pagina indexeerbaar is of niet'],
        ['label' => 'global_not_indexable', 'text' => 'Niet indexeerbaar'],
        ['label' => 'pages_copy_structure', 'text' => 'Kopieer paginastructuur'],
        ['label' => 'pages_languageId', 'text' => 'Taal'],
        ['label' => 'pages_languageId_title', 'text' => 'Kies een taal'],
        ['label' => 'pages_copy_to', 'text' => 'Kopieer naar'],
        ['label' => 'pages_structure_copied', 'text' => 'Structuur gekopieerd'],
        ['label' => 'pages_copy_structure', 'text' => 'Kopieer pagina structuur'],
        ['label' => 'pages_link_form', 'text' => 'Link formulier'],
        ['label' => 'settings_headScripts', 'text' => 'Head scripts'],
        ['label' => 'settings_bodyScripts', 'text' => 'Body scripts'],
        ['label' => 'page_has_no_video_management', 'text' => 'Pagina heeft geen Videobeheer'],
        ['label' => 'page_has_no_file_management', 'text' => 'Pagina heeft geen bestandsbeheer'],
        ['label' => 'page_has_sub_tooltip', 'text' => 'Heeft sub'],
        ['label' => 'page_has_subpages_tooltip', 'text' => 'Heeft subpagina\'s'],
        ['label' => 'pages_include_parent_in_url_path', 'text' => 'Voeg URL van hoofdpagina toe?'],
        ['label' => 'pages_include_parent_in_url_path_tooltip', 'text' => 'Bepaal of het URL van de gekoppelde hoofdpagina toegevoegd moet worden in het URL van deze pagina'],
        ['label' => 'autocomplete_usps_title', 'text' => 'Unique selling points'],
    ],
];

// add page with default language Id
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageAccount = PageManager::getPageByName('home', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `home`';
        if ($bInstall) {
            $oPageAccount             = new Page();
            $oPageAccount->languageId = DEFAULT_LANGUAGE_ID;
            $oPageAccount->name       = 'home';
            $oPageAccount->title      = 'Home';
            $oPageAccount->content    = '<p>Dit is de homepage</p>';
            $oPageAccount->shortTitle = 'Home';
            $oPageAccount->order      = 1;
            $oPageAccount->forceUrlPath('/');
            $oPageAccount->setControllerPath('/modules/pages/site/controllers/home.cont.php');
            $oPageAccount->setOnlineChangeable(0);
            $oPageAccount->setDeletable(0);
            $oPageAccount->setMayHaveSub(0);
            $oPageAccount->setLockUrlPath(1);
            $oPageAccount->setLockParent(1);
            $oPageAccount->setHideImageManagement(1);
            $oPageAccount->setHideFileManagement(1);
            $oPageAccount->setHideLinkManagement(1);
            $oPageAccount->setHideVideoLinkManagement(1);
            $oPageAccount->setHideBrandboxManagement(0);
            if ($oPageAccount->isValid()) {
                PageManager::savePage($oPageAccount);
            } else {
                _d($oPageAccount->getInvalidProps());
                die('Can\'t create page `home`');
            }
        }
    }

// add page
    if (!($oPageAccount = PageManager::getPageByName('404', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `404`';
        if ($bInstall) {
            $oPageAccount             = new Page();
            $oPageAccount->languageId = DEFAULT_LANGUAGE_ID;
            $oPageAccount->name       = '404';
            $oPageAccount->title      = '404 - Pagina of bestand niet gevonden';
            $oPageAccount->content    = '<p>De opgevraagde content bestaat niet (meer)!</p><p>Wij verzoeken u naar onze <a href="/">homepage</a> te gaan</p>';
            $oPageAccount->shortTitle = '404 - Pagina of bestand niet gevonden';
            $oPageAccount->forceUrlPath('/404');
            $oPageAccount->setControllerPath('/modules/pages/site/controllers/errors.cont.php');
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
                die('Can\'t create page `404`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oPageAccount = PageManager::getPageByName('home', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `home` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oPageAccount             = new Page();
                $oPageAccount->languageId = $oLocale->languageId;
                $oPageAccount->name       = 'home';
                $oPageAccount->title      = 'Home';
                $oPageAccount->content    = '<p>This is the homepage</p>';
                $oPageAccount->shortTitle = 'Home';
                $oPageAccount->order      = 1;
                $oPageAccount->forceUrlPath('/');
                $oPageAccount->setControllerPath('/modules/pages/site/controllers/home.cont.php');
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
                    die('Can\'t create page `home`');
                }
            }
        }

        if (!($oPageAccount = PageManager::getPageByName('404', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `404` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oPageAccount             = new Page();
                $oPageAccount->languageId = $oLocale->languageId;
                $oPageAccount->name       = '404';
                $oPageAccount->title      = '404 - Page or file can not be found';
                $oPageAccount->content    = '<p>The requested content does not exist (anymore)!</p><p>Please go to our <a href="/' . $oLocale->getLanguage()->code . '">homepage</a>.</p>';
                $oPageAccount->shortTitle = '404 - Page or file can not be found';
                $oPageAccount->forceUrlPath('/404');
                $oPageAccount->setControllerPath('/modules/pages/site/controllers/errors.cont.php');
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
                    die('Can\'t create page `404`');
                }
            }
        }

    }

    if (!($oPageMaintenance = PageManager::getPageByName('maintenance', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `maintenance`';
        if ($bInstall) {
            $oPageMaintenance             = new Page();
            $oPageMaintenance->languageId = DEFAULT_LANGUAGE_ID;
            $oPageMaintenance->name       = 'maintenance';
            $oPageMaintenance->title      = 'Onderhoud aan de applicatie';
            $oPageMaintenance->content    = '<p>Op dit moment vindt er onderhoud plaats aan de website. U kunt daarom wellicht tijdelijk de website niet goed gebruiken. Ververs de pagina regelmatig om te zien wanneer de werkzaamheden gereed zijn.</p>';
            $oPageMaintenance->shortTitle = 'Onderhoud';
            $oPageMaintenance->forceUrlPath('/onderhoud-applicatie');
            $oPageMaintenance->setControllerPath('/modules/pages/site/controllers/page.cont.php');
            $oPageMaintenance->setInMenu(0);
            $oPageMaintenance->setIndexable(0);
            $oPageMaintenance->setOnlineChangeable(0);
            $oPageMaintenance->setDeletable(0);
            $oPageMaintenance->setMayHaveSub(0);
            $oPageMaintenance->setLockUrlPath(1);
            $oPageMaintenance->setLockParent(1);
            $oPageMaintenance->setHideImageManagement(1);
            $oPageMaintenance->setHideFileManagement(1);
            $oPageMaintenance->setHideLinkManagement(1);
            $oPageMaintenance->setHideVideoLinkManagement(1);
            if ($oPageMaintenance->isValid()) {
                PageManager::savePage($oPageMaintenance);
            } else {
                _d($oPageMaintenance->getInvalidProps());
                die('Can\'t create page `maintenance`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oPageMaintenance = PageManager::getPageByName('maintenance', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `maintenance` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oPageMaintenance             = new Page();
                $oPageMaintenance->languageId = $oLocale->languageId;
                $oPageMaintenance->name       = 'maintenance';
                $oPageMaintenance->title      = 'Application maintenance';
                $oPageMaintenance->content    = '<p>At the moment, maintenance is applied on the website. You may therefore be unable to use the website temporarily. Refresh the page, if this message is not shown anymore, maintenance is finished.</p>';
                $oPageMaintenance->shortTitle = 'Application maintenance';
                $oPageMaintenance->forceUrlPath('/maintenance');
                $oPageMaintenance->setControllerPath('/modules/pages/site/controllers/page.cont.php');
                $oPageMaintenance->setInMenu(0);
                $oPageMaintenance->setIndexable(0);
                $oPageMaintenance->setOnlineChangeable(0);
                $oPageMaintenance->setDeletable(0);
                $oPageMaintenance->setMayHaveSub(0);
                $oPageMaintenance->setLockUrlPath(1);
                $oPageMaintenance->setLockParent(1);
                $oPageMaintenance->setHideImageManagement(1);
                $oPageMaintenance->setHideFileManagement(1);
                $oPageMaintenance->setHideLinkManagement(1);
                $oPageMaintenance->setHideVideoLinkManagement(1);
                if ($oPageMaintenance->isValid()) {
                    PageManager::savePage($oPageMaintenance);
                } else {
                    _d($oPageMaintenance->getInvalidProps());
                    die('Can\'t create page `maintenance`');
                }
            }
        }
    }

}

// check settings
if (moduleExists('pages')) {
    if (!($oSetting1 = SettingManager::getSettingByName('clientName'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `clientName`';
        if ($bInstall) {
            $oSetting1        = new Setting();
            $oSetting1->name  = 'clientName';
            $oSetting1->value = 'Bedrijfsnaam';
            if ($oSetting1->isValid()) {
                SettingManager::saveSetting($oSetting1);
            } else {
                _d($oSetting1->getInvalidProps());
                die('Can\'t create setting `clientName`');
            }
        }
    }

    if (!($oSetting2 = SettingManager::getSettingByName('clientStreet'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `clientStreet`';
        if ($bInstall) {
            $oSetting2        = new Setting();
            $oSetting2->name  = 'clientStreet';
            $oSetting2->value = 'Straat + huisnummer';
            if ($oSetting2->isValid()) {
                SettingManager::saveSetting($oSetting2);
            } else {
                _d($oSetting2->getInvalidProps());
                die('Can\'t create setting `clientStreet`');
            }
        }
    }

    if (!($oSetting3 = SettingManager::getSettingByName('clientPostalCode'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `clientPostalCode`';
        if ($bInstall) {
            $oSetting3        = new Setting();
            $oSetting3->name  = 'clientPostalCode';
            $oSetting3->value = '1234PC';
            if ($oSetting3->isValid()) {
                SettingManager::saveSetting($oSetting3);
            } else {
                _d($oSetting3->getInvalidProps());
                die('Can\'t create setting `clientPostalCode`');
            }
        }
    }

    if (!($oSetting4 = SettingManager::getSettingByName('clientCity'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `clientCity`';
        if ($bInstall) {
            $oSetting4        = new Setting();
            $oSetting4->name  = 'clientCity';
            $oSetting4->value = 'Plaats';
            if ($oSetting4->isValid()) {
                SettingManager::saveSetting($oSetting4);
            } else {
                _d($oSetting4->getInvalidProps());
                die('Can\'t create setting `clientCity`');
            }
        }
    }

    if (!($oSetting5 = SettingManager::getSettingByName('contactLatitude'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `contactLatitude`';
        if ($bInstall) {
            $oSetting5        = new Setting();
            $oSetting5->name  = 'contactLatitude';
            $oSetting5->value = '52.20671';
            if ($oSetting5->isValid()) {
                SettingManager::saveSetting($oSetting5);
            } else {
                _d($oSetting5->getInvalidProps());
                die('Can\'t create setting `contactLatitude`');
            }
        }
    }

    if (!($oSetting6 = SettingManager::getSettingByName('contactLongitude'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `contactLongitude`';
        if ($bInstall) {
            $oSetting6        = new Setting();
            $oSetting6->name  = 'contactLongitude';
            $oSetting6->value = '4.87210';
            if ($oSetting6->isValid()) {
                SettingManager::saveSetting($oSetting6);
            } else {
                _d($oSetting6->getInvalidProps());
                die('Can\'t create setting `contactLongitude`');
            }
        }
    }

    if (!($oSetting7 = SettingManager::getSettingByName('pagesMaxLevels'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `pagesMaxLevels`';
        if ($bInstall) {
            $oSetting7        = new Setting();
            $oSetting7->name  = 'pagesMaxLevels';
            $oSetting7->value = '3';
            if ($oSetting7->isValid()) {
                SettingManager::saveSetting($oSetting7);
            } else {
                _d($oSetting7->getInvalidProps());
                die('Can\'t create setting `pagesMaxLevels`');
            }
        }
    }

    if (!($oSetting8 = SettingManager::getSettingByName('headScripts'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `headScripts`';
        if ($bInstall) {
            $oSetting8        = new Setting();
            $oSetting8->name  = 'headScripts';
            $oSetting8->value = null;
            if ($oSetting8->isValid()) {
                SettingManager::saveSetting($oSetting8);
            } else {
                _d($oSetting8->getInvalidProps());
                die('Can\'t create setting `headScripts`');
            }
        }
    }

    if (!($oSetting9 = SettingManager::getSettingByName('bodyScripts'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `bodyScripts`';
        if ($bInstall) {
            $oSetting9        = new Setting();
            $oSetting9->name  = 'bodyScripts';
            $oSetting9->value = null;
            if ($oSetting9->isValid()) {
                SettingManager::saveSetting($oSetting9);
            } else {
                _d($oSetting9->getInvalidProps());
                die('Can\'t create setting `bodyScripts`');
            }
        }
    }

    if (!($oSetting10 = SettingManager::getSettingByName('themeColor'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `themeColor`';
        if ($bInstall) {
            $oSetting10        = new Setting();
            $oSetting10->name  = 'themeColor';
            $oSetting10->value = '#222121';
            if ($oSetting10->isValid()) {
                SettingManager::saveSetting($oSetting10);
            } else {
                _d($oSetting10->getInvalidProps());
                die('Can\'t create setting `themeColor`');
            }
        }
    }
}

// Database checks

if (!$oDb->tableExists('pages')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `pages`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE IF NOT EXISTS `pages` (
          `pageId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT "-1",
          `windowTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `intro` text COLLATE utf8_unicode_ci,
          `content` text COLLATE utf8_unicode_ci,
          `shortTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `urlPath` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
          `urlPart` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `urlParameters` varchar(2048) COLLATE utf8_unicode_ci DEFAULT NULL,
          `controllerPath` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `parentPageId` int(11) DEFAULT NULL,
          `online` tinyint(1) NOT NULL DEFAULT "0",
          `order` int(11) DEFAULT NULL,
          `onlineChangeable` tinyint(1) NOT NULL DEFAULT "1",
          `editable` tinyint(1) NOT NULL DEFAULT "1",
          `deletable` tinyint(1) NOT NULL DEFAULT "1",
          `inMenu` tinyint(1) NOT NULL DEFAULT "0",
          `inFooter` tinyint(1) NOT NULL DEFAULT "0",
          `indexable` tinyint(1) NOT NULL DEFAULT "1",
          `includeParentInUrlPath` tinyint(1) NOT NULL DEFAULT "1",
          `level` int(11) NOT NULL DEFAULT "1",
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          `mayHaveSub` tinyint(1) NOT NULL DEFAULT "1",
          `lockUrlPath` tinyint(1) NOT NULL DEFAULT "0",
          `lockParent` tinyint(1) NOT NULL DEFAULT "1",
          `hideImageManagement` tinyint(1) NOT NULL DEFAULT "0",
          `hideFileManagement` tinyint(1) NOT NULL DEFAULT "0",
          `hideLinkManagement` tinyint(1) NOT NULL DEFAULT "0",
          `hideVideoLinkManagement` tinyint(1) NOT NULL DEFAULT "0",
          `hideBrandboxManagement` tinyint(1) NOT NULL DEFAULT "1",
          `showOnHome` int(1) NOT NULL DEFAULT "0",
          `showNews` int(1) NOT NULL DEFAULT "0",
          PRIMARY KEY (`pageId`),
          KEY `pageId_pageId` (`parentPageId`),
          KEY `languageId` (`languageId`),
          KEY `urlPath` (`urlPath`(255)),
          UNIQUE KEY `pageId_languageId` (`pageId`,`languageId`),
          UNIQUE KEY `name_languageId` (`name`, `languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('pages_brandbox_items')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `pages_brandbox_items`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `pages_brandbox_items` (
          `pageId` INT(11) NOT NULL,
          `brandboxItemId` INT(11) NOT NULL,
          PRIMARY KEY (`pageId`, `brandboxItemId`),
          KEY `fk_pages` (`brandboxItemId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('pages_files')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `pages_files`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE IF NOT EXISTS `pages_files` (
          `mediaId` INT(11) NOT NULL,
          `pageId` INT(11) NOT NULL,
          PRIMARY KEY (`mediaId`,`pageId`),
          KEY `fk_files_files_pages` (`mediaId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('pages_images')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `pages_images`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `pages_images` (
          `pageId` INT(11) NOT NULL,
          `imageId` INT(11) NOT NULL,
          PRIMARY KEY (`imageId`,`pageId`),
          KEY `fk_images_pageImages` (`imageId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('pages_links')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `pages_links`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `pages_links` (
          `mediaId` INT(11) NOT NULL,
          `pageId` INT(11) NOT NULL,
          PRIMARY KEY (`mediaId`,`pageId`),
          KEY `fk_pages_pagesMedia` (`pageId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('pages_video_links')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `pages_video_links`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE IF NOT EXISTS `pages_video_links` (
          `mediaId` INT(11) NOT NULL,
          `pageId` INT(11) NOT NULL,
          PRIMARY KEY (`mediaId`,`pageId`),
          KEY `fk_pages_pagesVideoLinks` (`pageId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('page_redirects')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `page_redirects`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `page_redirects` (
          `id` INT(11) NOT NULL AUTO_INCREMENT,
          `pageId` INT(11) NOT NULL,
          `urlPath` VARCHAR(2048) COLLATE utf8_unicode_ci NOT NULL,
          `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `pageId_pageRedirects` (`pageId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// add column for custom canonical
if ($oDb->tableExists('pages')) {
    if (!$oDb->columnExists('pages', 'customCanonical')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `customCanonical` in `pages`';
        if ($bInstall) {
            $oDb->addColumn('pages', 'customCanonical', 'varchar', '2048', 'level', null, true);
        }
    }
}

// check pages constraints
if ($oDb->tableExists('pages')) {
    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('pages', 'parentPageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages`.`parentPageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages', 'parentPageId', 'pages', 'pageId', 'RESTRICT', 'RESTRICT');
            }
        }
        // check pages_brandbox_items constraint
        if (!$oDb->constraintExists('pages_brandbox_items', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_brandbox_items`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_brandbox_items', 'pageId', 'pages', 'pageId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('pages', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check pages_files constraints
if ($oDb->tableExists('pages_files')) {
    if ($oDb->tableExists('files')) {
        // check pages_files constraint
        if (!$oDb->constraintExists('pages_files', 'mediaId', 'files', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_files`.`mediaId` => `files`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_files', 'mediaId', 'files', 'mediaId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('pages_files', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_files`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_files', 'pageId', 'pages', 'pageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check pages_images constraints
if ($oDb->tableExists('pages_images')) {
    if ($oDb->tableExists('images')) {
        // check pages_images constraint
        if (!$oDb->constraintExists('pages_images', 'imageId', 'images', 'imageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_images`.`imageId` => `images`.`imageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_images', 'imageId', 'images', 'imageId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('pages_images', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_images`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_images', 'pageId', 'pages', 'pageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check pages_links constraints
if ($oDb->tableExists('pages_links')) {
    if ($oDb->tableExists('media')) {
        // check pages_links constraint
        if (!$oDb->constraintExists('pages_links', 'mediaId', 'media', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_links`.`mediaId` => `media`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_links', 'mediaId', 'media', 'mediaId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('pages_links', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_links`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_links', 'pageId', 'pages', 'pageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check pages_video_links constraints
if ($oDb->tableExists('pages_video_links')) {
    if ($oDb->tableExists('media')) {
        // check pages_links constraint
        if (!$oDb->constraintExists('pages_video_links', 'mediaId', 'media', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_video_links`.`mediaId` => `media`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_video_links', 'mediaId', 'media', 'mediaId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('pages_video_links', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_video_links`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('pages_video_links', 'pageId', 'pages', 'pageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check page_redirects constraints
if ($oDb->tableExists('page_redirects')) {
    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('page_redirects', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `page_redirects`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('page_redirects', 'pageId', 'pages', 'pageId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check pages_brandbox_items constraint
if ($oDb->tableExists('brandbox_items')) {
    if (!$oDb->constraintExists('pages_brandbox_items', 'brandboxItemId', 'brandbox_items', 'brandboxItemId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `pages_brandbox_items`.`brandboxItemId` => `brandbox_items`.`brandboxItemId`';
        if ($bInstall) {
            $oDb->addConstraint('pages_brandbox_items', 'brandboxItemId', 'brandbox_items', 'brandboxItemId', 'RESTRICT', 'CASCADE');
        }
    }
}

if (moduleExists('newsItems')) {
    $aErrors = InstallHelper::pivot('pages', 'pageId', 'news_items', 'newsItemId', $bInstall);
    if (count($aErrors)) {
        if (!isset($aLogs[$sModuleName], $aLogs[$sModuleName]['errors'])) {
            $aLogs[$sModuleName]['errors'] = [];
        }

        $aLogs[$sModuleName]['errors'] = array_merge($aLogs[$sModuleName]['errors'], $aErrors);
    }
    unset($aErrors);

}

if (moduleExists('usps')) {
    $aErrors = InstallHelper::pivot('pages', 'pageId', 'usps', 'uspId', $bInstall, null, InstallHelper::CASCADE, InstallHelper::CASCADE);
    if (count($aErrors)) {
        if (!isset($aLogs[$sModuleName], $aLogs[$sModuleName]['errors'])) {
            $aLogs[$sModuleName]['errors'] = [];
        }

        $aLogs[$sModuleName]['errors'] = array_merge($aLogs[$sModuleName]['errors'], $aErrors);
    }
    unset($aErrors);
}

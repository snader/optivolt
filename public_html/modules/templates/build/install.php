<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'templates-beheer' => [
        'module'     => 'templates',
        'controller' => 'template',
    ],
    'templategroepen'  => [
        'module'     => 'templates',
        'controller' => 'templateGroup',
    ],
];

$aNeededClassRoutes = [
    'Template'             => [
        'module' => 'templates',
    ],
    'TemplateManager'      => [
        'module' => 'templates',
    ],
    'TemplateGroup'        => [
        'module' => 'templates',
    ],
    'TemplateGroupManager' => [
        'module' => 'templates',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'             => 'templates-beheer',
        'icon'             => 'fa-file-text-o',
        'linkName'         => 'templates_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'templates_full'],
        ],
    ],
    [
        'name'             => 'templategroepen',
        'icon'             => 'fa-object-group',
        'linkName'         => 'templateGroups_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'templateGroups_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'templates_group_unique_name', 'text' => 'Systeemnaam'],
        ['label' => 'templates_group_unique_name_tooltip', 'text' => 'Kies een unieke systeemnaam'],
        ['label' => 'templates_choose_template_group', 'text' => 'Kies een templategroep'],
        ['label' => 'templates_choose_template_type', 'text' => 'Kies het sjabloontype'],
        ['label' => 'templates_copy', 'text' => 'Kopieer template'],
        ['label' => 'templates_definition_name', 'text' => 'Omschrijving/naam'],
        ['label' => 'templates_deletable_tooltip', 'text' => 'De template verwijderen'],
        ['label' => 'templates_editable_tooltip', 'text' => 'De template bewerken'],
        ['label' => 'templates_enter_template_description', 'text' => 'Vul een omschrijving/naam in van de template'],
        ['label' => 'templates_found_templates', 'text' => 'Gevonden templates:'],
        ['label' => 'templates_message', 'text' => 'Bericht'],
        ['label' => 'templates_message_type', 'text' => 'Templatetype'],
        ['label' => 'templates_no_templates', 'text' => 'Er zijn geen templates om weer te geven met dit filter'],
        ['label' => 'templates_remove', 'text' => 'Verwijder template'],
        ['label' => 'templates_send_test', 'text' => 'Verstuur een test'],
        ['label' => 'templates_technical_name', 'text' => 'Technische naam'],
        ['label' => 'templates_template', 'text' => 'Template'],
        ['label' => 'templates_template_group', 'text' => 'Templategroep'],
        ['label' => 'templates_test', 'text' => 'Test template versturen'],
        ['label' => 'templates_tooltip_filter_name', 'text' => 'Zoek op de omschrijving/naam van de template'],
        ['label' => 'templates_topic', 'text' => 'Onderwerp'],
        ['label' => 'templates_unique_name', 'text' => 'Unieke naam'],
        ['label' => 'templates_unique_template_tooltip', 'text' => 'Vul een unieke naam in voor de template'],
        ['label' => 'templates_variables', 'text' => 'Template variabelen'],
        ['label' => 'templates_variables_tooltip', 'text' => 'Gebruik deze variabelen in uw tekst/onderwerp en ze zullen worden vervangen\r\n door de echte waarde bij het versturen van de template'],
        ['label' => 'templates_add_new_template', 'text' => 'Template toevoegen'],
        ['label' => 'templates_management', 'text' => 'Template beheer'],
        ['label' => 'templates_status_not_editable', 'text' => 'Template kan niet worden bewerkt'],
        ['label' => 'templates_field_not_completed', 'text' => 'Template is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'templates_status_deleted', 'text' => 'Template is verwijderd'],
        ['label' => 'templates_status_not_deleted', 'text' => 'Template is niet verwijderd'],
        ['label' => 'templates_status_saved', 'text' => 'Template is opgeslagen'],
        ['label' => 'templates_edit', 'text' => 'Bewerk template'],
        ['label' => 'templates_cannot_retrieve_vars', 'text' => 'Kon template variabelen niet ophalen'],
        ['label' => 'templates_menu', 'text' => 'Templates'],
        ['label' => 'templateGroups_remove', 'text' => 'Templategroep verwijderen'],
        ['label' => 'templateGroups_edit', 'text' => 'Templategroep bewerken'],
        ['label' => 'templateGroups_menu', 'text' => 'Template groepen'],
        ['label' => 'templateGroups_found_templateGroups', 'text' => 'Gevonden templategroepen'],
        ['label' => 'templateGroups_no_templateGroups', 'text' => 'Geen templategroepen gevonden'],
        ['label' => 'templateGroups_add_templateGroup', 'text' => 'Templategroep toevoegen'],
        ['label' => 'templateGroups_templateGroupName', 'text' => 'Naam'],
        ['label' => 'templateGroups_templateVariables', 'text' => 'Variabelen'],
        ['label' => 'templateGroups_templateGroupName_tooltip', 'text' => 'Vul de naam van de groep in'],
        ['label' => 'templateGroups_templateGroup', 'text' => 'Templategroep'],
        ['label' => 'templateGroups_management', 'text' => 'Templategroepen beheer'],
        ['label' => 'templateGroups_status_saved', 'text' => 'Templategroep opgeslagen'],
        ['label' => 'templateGroups_status_deleted', 'text' => 'Templategroep verwijderd'],
        ['label' => 'templateGroups_status_not_deleted', 'text' => 'Templategroep niet verwijderd'],
        ['label' => 'templateGroups_status_not_saved', 'text' => 'Templategroep niet opgeslagen'],
        ['label' => 'template_unique_name_tooltip', 'text' => 'Geef een unieke naam voor deze template op'],
    ],
    'es' => [
        ['label' => 'templates_menu', 'text' => 'Templates'],
        ['label' => 'templates_choose_template_type', 'text' => 'Elija el tipo de plantilla'],
        ['label' => 'templates_copy', 'text' => 'Copiar plantilla'],
        ['label' => 'templates_definition_name', 'text' => 'Descripción/nombre'],
        ['label' => 'templates_deletable_tooltip', 'text' => '¿Se puede eliminar la plantilla?'],
        ['label' => 'templates_editable_tooltip', 'text' => '¿Se puede editar la plantilla?'],
        ['label' => 'templates_enter_template_description', 'text' => 'Por favor, introduzca una descripción/nombre de la plantilla'],
        ['label' => 'templates_found_templates', 'text' => 'Templates encontrados:'],
        ['label' => 'templates_message_type', 'text' => 'Tipo de plantilla'],
        ['label' => 'templates_no_templates', 'text' => 'Hay no hay plantillas para mostrar con estos filtros'],
        ['label' => 'templates_remove', 'text' => 'Eliminar plantilla'],
        ['label' => 'templates_send_test', 'text' => 'Enviar una prueba'],
        ['label' => 'templates_technical_name', 'text' => 'Nombre técnico'],
        ['label' => 'templates_template', 'text' => 'Plantilla'],
        ['label' => 'templates_template_group', 'text' => 'Grupo de plantillas'],
        ['label' => 'templates_test', 'text' => 'Enviar email de prueba'],
        ['label' => 'templates_tooltip_filter_name', 'text' => 'Nombre/descripción a buscar'],
        ['label' => 'templates_topic', 'text' => 'Tema'],
        ['label' => 'templates_unique_name', 'text' => 'Nombre único'],
        ['label' => 'templates_unique_template_tooltip', 'text' => 'Introduzca un nombre único para la plantilla'],
        ['label' => 'templates_variables', 'text' => 'Variables de plantilla'],
        ['label' => 'templates_variables_tooltip', 'text' => 'Utilice estas variables en el texto de la plantilla y serán reemplazados por su valor real durante el envió del email'],
        ['label' => 'templates_add_new_template', 'text' => 'Añadir plantilla'],
        ['label' => 'templates_management', 'text' => 'Gestión de plantilla'],
        ['label' => 'templates_status_not_editable', 'text' => 'No se puede editar la plantilla'],
        ['label' => 'templates_field_not_completed', 'text' => 'La plantilla no se ha guardado, no todos los campos están (correctamente) completados'],
        ['label' => 'templates_status_deleted', 'text' => 'Plantilla eliminada'],
        ['label' => 'templates_status_not_deleted', 'text' => 'Plantilla no eliminada'],
        ['label' => 'templates_status_saved', 'text' => 'Plantilla guardada'],
        ['label' => 'templates_edit', 'text' => 'Editar plantilla'],
        ['label' => 'templates_cannot_retrieve_vars', 'text' => 'No se pudieron recuperar las variables de plantilla'],
        ['label' => 'templates_message', 'text' => 'Mensaje'],
        ['label' => 'template_unique_name_tooltip', 'text' => 'Enter a unique name for this template'],
    ],
    'en' => [
        ['label' => 'templates_choose_template_type', 'text' => 'Choose the type of template'],
        ['label' => 'templates_copy', 'text' => 'Copy template'],
        ['label' => 'templates_definition_name', 'text' => 'Description/name'],
        ['label' => 'templates_deletable_tooltip', 'text' => 'Can remove the template?'],
        ['label' => 'templates_editable_tooltip', 'text' => 'Can the edit template?'],
        ['label' => 'templates_enter_template_description', 'text' => 'Please enter a description/name of the template'],
        ['label' => 'templates_found_templates', 'text' => 'Templates found:'],
        ['label' => 'templates_message', 'text' => 'Message'],
        ['label' => 'templates_message_type', 'text' => 'Template type'],
        ['label' => 'templates_no_templates', 'text' => 'There are no templates to display with this filter'],
        ['label' => 'templates_remove', 'text' => 'Remove template'],
        ['label' => 'templates_send_test', 'text' => 'Send a test'],
        ['label' => 'templates_technical_name', 'text' => 'Technical name'],
        ['label' => 'templates_template', 'text' => 'Template'],
        ['label' => 'templates_template_group', 'text' => 'Template group'],
        ['label' => 'templates_test', 'text' => 'Send test template'],
        ['label' => 'templates_tooltip_filter_name', 'text' => 'Search the description/name of the template'],
        ['label' => 'templates_topic', 'text' => 'Topic'],
        ['label' => 'templates_unique_name', 'text' => 'Unique name'],
        ['label' => 'templates_unique_template_tooltip', 'text' => 'Enter a unique name for the template'],
        ['label' => 'templates_variables', 'text' => 'Template variables'],
        ['label' => 'templates_variables_tooltip', 'text' => 'Use these variables in your text/subject and they will be replaced by < br/> the real value in sending the template'],
        ['label' => 'templates_add_new_template', 'text' => 'Add template'],
        ['label' => 'templates_management', 'text' => 'Template management'],
        ['label' => 'templates_status_not_editable', 'text' => 'Template cannot be edited'],
        ['label' => 'templates_field_not_completed', 'text' => 'Template is not saved, not all fields are filled in (right)'],
        ['label' => 'templates_status_deleted', 'text' => 'Template has been removed'],
        ['label' => 'templates_status_not_deleted', 'text' => 'Template is not deleted'],
        ['label' => 'templates_status_saved', 'text' => 'Template is stored'],
        ['label' => 'templates_edit', 'text' => 'Edit template'],
        ['label' => 'templates_cannot_retrieve_vars', 'text' => 'Could not retrieve template variables'],
        ['label' => 'templates_menu', 'text' => 'Templates'],
        ['label' => 'template_unique_name_tooltip', 'text' => 'Enter a unique name for this template'],
    ],
];

// Database checks

if (!$oDb->tableExists('template_groups')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `template_groups`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `template_groups` (
          `templateGroupId` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          `templateGroupName` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
          `templateVariables` text COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`templateGroupId`),
          UNIQUE KEY `u_template_groups_name` (`name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('template_groups')) {
    if (!$oDb->tableExists('templates')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `templates`';
        if ($bInstall) {

            // add table
            $sQuery = '
        CREATE TABLE `templates` (
          `templateId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `templateGroupId` int(11) NOT NULL,
          `type` enum(\'email\',\'sms\',\'text\') COLLATE utf8_unicode_ci NOT NULL DEFAULT \'email\',
          `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `template` text COLLATE utf8_unicode_ci,
          `deletable` tinyint(1) NOT NULL DEFAULT \'0\',
          `editable` tinyint(1) NOT NULL DEFAULT \'0\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`templateId`),
          UNIQUE KEY `name_languageId` (`name`, `languageId`),
          KEY `templates_templateGroupId` (`templateGroupId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    if ($oDb->tableExists('templates')) {

        // check template group constraint
        if (!$oDb->constraintExists('templates', 'templateGroupId', 'template_groups', 'templateGroupId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `templates`.`templateGroupId` => `template_groups`.`templateGroupId`';
            if ($bInstall) {
                $oDb->addConstraint('templates', 'templateGroupId', 'template_groups', 'templateGroupId', 'RESTRICT', 'CASCADE');
            }
        }

        if ($oDb->tableExists('languages')) {
            // check languages constraint
            if (!$oDb->constraintExists('templates', 'languageId', 'languages', 'languageId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `templates`.`languageId` => `languages`.`languageId`';
                if ($bInstall) {
                    $oDb->addConstraint('templates', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
                }
            }
        }
    }
}

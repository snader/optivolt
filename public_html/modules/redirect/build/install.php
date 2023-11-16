<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'redirect'        => [
        'module'     => 'redirect',
        'controller' => 'redirect',
    ],
    'redirect-import' => [
        'module'     => 'redirect',
        'controller' => 'redirectImport',
    ],
];

$aNeededSiteControllerRoutes = [];

$aNeededClassRoutes = [
    'Redirect'        => [
        'module' => 'redirect',
    ],
    'RedirectManager' => [
        'module' => 'redirect',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'             => 'redirect',
        'icon'             => 'fa-arrows-h',
        'linkName'         => 'redirect_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'redirect_full'],
        ],
    ],
    [
        'name'             => 'redirect-import',
        'icon'             => 'fa-file',
        'linkName'         => 'redirectImport_menu',
        'parentModuleName' => 'redirect',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'redirectImport_full'],
        ],
    ],
];

$aNeededTranslations = [
    'en' => [
        ['label' => 'redirect_url_test_example', 'text' => 'Fill in the URL to test with, for example'],
        ['label' => 'redirect_url_not_allowed', 'text' => 'This URL is not allowed, please contact us'],
        ['label' => 'redirect_offline_online', 'text' => 'Set the redirect online or offline'],
        ['label' => 'redirect_new_url_example', 'text' => 'Fill in the old URL, for example'],
        ['label' => 'redirect_fill_in_old_url', 'text' => 'Fill in the old URL'],
        ['label' => 'redirect_fill_in_expresion_example', 'text' => 'Fill in the expression, for example'],
        ['label' => 'redirect_fill_in_expresion', 'text' => 'Fill in the expression'],
        ['label' => 'redirect_fill_in_destination_url', 'text' => 'Fill in the destination URL'],
        ['label' => 'redirectImport_menu', 'text' => 'Import redirects'],
        ['label' => 'redirect_menu', 'text' => 'Redirect'],
        ['label' => 'Redirect', 'text' => 'Redirect'],
        ['label' => 'redirectsSpecific', 'text' => 'Specific redirect'],
        ['label' => 'redirectsPattern', 'text' => 'Pattern'],
        ['label' => 'redirectsNewUrl', 'text' => 'New URL'],
        ['label' => 'redirectsAdd', 'text' => 'New redirect found'],
        ['label' => 'redirectsImportBatch', 'text' => 'Import batch'],
        ['label' => 'redirectsOnlineTooltip', 'text' => 'Change redirect online or offline'],
        ['label' => 'redirectsEditTooltip', 'text' => 'Edit redirect'],
        ['label' => 'redirectsDeleteTooltip', 'text' => 'Delete redirects'],
        ['label' => 'redirectsNoRedirects', 'text' => 'There are no set redirects'],
        ['label' => 'redirectsRegularExpressions', 'text' => 'Regular expressions'],
        ['label' => 'redirectsOnlineMessage', 'text' => 'Redirect online'],
        ['label' => 'redirectsOfflineMessage', 'text' => 'Redirect offline'],
        ['label' => 'redirectsNothingChangedMessage', 'text' => 'Redirect has not been changed'],
        ['label' => 'redirectsSaved', 'text' => 'Redirect saved'],
        ['label' => 'redirectsMatchFound', 'text' => 'Match found'],
        ['label' => 'redirectsMatchNotFound', 'text' => 'Match not found'],
        ['label' => 'redirectDeleted', 'text' => 'Redirect is deleted'],
        ['label' => 'redirectNotDeleted', 'text' => 'Redirect could not be deleted'],
        ['label' => 'redirectBackToOverview', 'text' => 'Back to the overview'],
        ['label' => 'redirectsNoSave', 'text' => 'Without saving'],
        ['label' => 'redirectSpecific', 'text' => 'Specific redirect'],
        ['label' => 'redirectsOldUrl', 'text' => 'Old URL'],
        ['label' => 'redirectRegularExpression', 'text' => 'Regular Expression'],
        ['label' => 'redirectPattern', 'text' => 'Pattern'],
        ['label' => 'redirectTestOutput', 'text' => 'Test redirect output'],
        ['label' => 'redirectURL', 'text' => 'URL'],
        ['label' => 'redirectOutput', 'text' => 'Output'],
        ['label' => 'redirectTestExpression', 'text' => 'Test expression'],
        ['label' => 'redirectImport', 'text' => 'Import redirects'],
        ['label' => 'redirectResult', 'text' => 'Result'],
        ['label' => 'redirectSaved', 'text' => 'Save'],
        ['label' => 'redirectTotal', 'text' => 'Total'],
        ['label' => 'redirectRows', 'text' => 'Rows'],
        ['label' => 'redirectErrors', 'text' => 'Errors'],
        ['label' => 'redirectWarnings', 'text' => 'Warnings'],
        ['label' => 'redirectFile', 'text' => 'File (.xls or .xlsx)'],
        ['label' => 'redirectSupportedColumns', 'text' => 'Supported columns'],
        ['label' => 'redirectMandatoryColumns', 'text' => 'Required columns'],
        ['label' => 'redirectMandatoryColumnExplenation', 'text' => 'Are bold and underlined'],
        ['label' => 'redirectMandatoryFilledColumns', 'text' => 'Required filled fields'],
        ['label' => 'redirectMandatoryFilledColumnsExplenation', 'text' => 'These fields needs to be filled in'],
        ['label' => 'redirectImportExplenation', 'text' => 'Only the specified column will be imported'],
        ['label' => 'redirectImportTitle', 'text' => 'Import redirects'],
        ['label' => 'redirectFileCouldNotBeRead', 'text' => 'File could not be read'],
        ['label' => 'redirectMandatoryColumn', 'text' => 'Required column'],
        ['label' => 'redirectColomnNotSupported', 'text' => 'Column is not supported and wont be saved'],
        ['label' => 'redirectFound', 'text' => 'Redirect found'],
        ['label' => 'redirectNew', 'text' => 'New redirect'],
        ['label' => 'redirectCannotSave', 'text' => 'Redirect could not be saved'],
        ['label' => 'redirectFileCouldNotBeUploaded', 'text' => 'Error while uploading file'],
        ['label' => 'redirectEmptyValue', 'text' => 'Empty field found'],
        ['label' => 'redirectNotCorrectForColumn', 'text' => 'No valid value for column'],
        ['label' => 'redirectColomnOverwrite', 'text' => 'Records will be overwritten'],
        ['label' => 'followingError', 'text' => 'Error'],
        ['label' => 'redirestTestmode', 'text' => 'Test mode'],
        ['label' => 'redirectTooLong', 'text' => 'Maximum of 255 characters exceeded in: '],
    ],
    'nl' => [
        ['label' => 'redirect_url_test_example', 'text' => 'Vul de URL om te testen in, bijvoorbeeld'],
        ['label' => 'redirect_new_url_example', 'text' => 'Vul de nieuwe URL in, bijvoorbeeld'],
        ['label' => 'redirect_fill_in_expresion', 'text' => 'Vul de expression in'],
        ['label' => 'redirect_fill_in_expresion_example', 'text' => 'Vul de expression in, bijvoorbeeld'],
        ['label' => 'redirect_fill_in_destination_url', 'text' => 'Vul de bestemming URL in'],
        ['label' => 'redirect_url_not_allowed', 'text' => 'Deze URL is niet toegestaan, neem contact met ons op'],
        ['label' => 'redirect_fill_in_old_url', 'text' => 'Vul de oude URL in'],
        ['label' => 'redirect_offline_online', 'text' => 'Zet de redirect online of offline'],
        ['label' => 'redirectImport_menu', 'text' => 'Redirects importeren'],
        ['label' => 'redirect_menu', 'text' => 'Redirect'],
        ['label' => 'Redirect', 'text' => 'Redirect'],
        ['label' => 'redirectsSpecific', 'text' => 'Specifieke redirects'],
        ['label' => 'redirectsPattern', 'text' => 'Patroon'],
        ['label' => 'redirectsNewUrl', 'text' => 'Nieuwe URL'],
        ['label' => 'redirectsAdd', 'text' => 'Nieuwe redirect toevoegen'],
        ['label' => 'redirectsImportBatch', 'text' => 'Importeer batch'],
        ['label' => 'redirectsOnlineTooltip', 'text' => 'Zet de redirect online of offline'],
        ['label' => 'redirectsEditTooltip', 'text' => 'Redirect bewerken'],
        ['label' => 'redirectsDeleteTooltip', 'text' => 'Redirect verwijderen'],
        ['label' => 'redirectsNoRedirects', 'text' => 'Er zijn geen redirects ingesteld'],
        ['label' => 'redirectsRegularExpressions', 'text' => 'Regular expressions'],
        ['label' => 'redirectsOnlineMessage', 'text' => 'Redirect online gezet'],
        ['label' => 'redirectsOfflineMessage', 'text' => 'Redirect offline gezet'],
        ['label' => 'redirectsNothingChangedMessage', 'text' => 'Redirect is niet gewijzigd'],
        ['label' => 'redirectsSaved', 'text' => 'Redirect is opgeslagen'],
        ['label' => 'redirectsMatchFound', 'text' => 'Match gevonden'],
        ['label' => 'redirectsMatchNotFound', 'text' => 'Match niet gevonden'],
        ['label' => 'redirectDeleted', 'text' => 'Redirect is verwijderd'],
        ['label' => 'redirectNotDeleted', 'text' => 'Redirect kan niet worden verwijderd'],
        ['label' => 'redirectBackToOverview', 'text' => 'Terug naar het overzicht'],
        ['label' => 'redirectsNoSave', 'text' => 'Zonder opslaan'],
        ['label' => 'redirectSpecific', 'text' => 'Specifieke redirect'],
        ['label' => 'redirectsOldUrl', 'text' => 'Oude URL'],
        ['label' => 'redirectRegularExpression', 'text' => 'Regular expression'],
        ['label' => 'redirectPattern', 'text' => 'Patroon'],
        ['label' => 'redirectTestOutput', 'text' => 'Test redirect output'],
        ['label' => 'redirectURL', 'text' => 'URL'],
        ['label' => 'redirectOutput', 'text' => 'Output'],
        ['label' => 'redirectTestExpression', 'text' => 'Test expression'],
        ['label' => 'redirectImport', 'text' => 'Importeer redirects'],
        ['label' => 'redirectResult', 'text' => 'Resultaat'],
        ['label' => 'redirectSaved', 'text' => 'Opgeslagen'],
        ['label' => 'redirectTotal', 'text' => 'Totaal'],
        ['label' => 'redirectRows', 'text' => 'Rijen'],
        ['label' => 'redirectErrors', 'text' => 'Fouten'],
        ['label' => 'redirectWarnings', 'text' => 'Waarschuwingen'],
        ['label' => 'redirectFile', 'text' => 'Bestand (.xls of .xlsx)'],
        ['label' => 'redirectSupportedColumns', 'text' => 'Ondersteunde kolommen'],
        ['label' => 'redirectMandatoryColumns', 'text' => 'Verplichte kolommen'],
        ['label' => 'redirectMandatoryColumnExplenation', 'text' => 'Zijn dik gedrukt en onderlijnd'],
        ['label' => 'redirectMandatoryFilledColumns', 'text' => 'Verplicht gevulde velden'],
        ['label' => 'redirectMandatoryFilledColumnsExplenation', 'text' => 'Deze velden moeten ingevuld zijn'],
        ['label' => 'redirectImportExplenation', 'text' => 'Alleen in de import meegegeven kolommen worden geupdatet'],
        ['label' => 'redirectImportTitle', 'text' => 'Redirect importeren'],
        ['label' => 'redirectFileCouldNotBeRead', 'text' => 'Bestand kan niet worden gelezen'],
        ['label' => 'redirectMandatoryColumn', 'text' => 'Verplichte kolom'],
        ['label' => 'redirectColomnNotSupported', 'text' => 'Kolom is niet ondersteund en wordt niet opgeslagen'],
        ['label' => 'redirectFound', 'text' => 'Redirect gevonden'],
        ['label' => 'redirectNew', 'text' => 'Nieuwe redirect'],
        ['label' => 'redirectCannotSave', 'text' => 'Redirect kan niet worden opgeslagen'],
        ['label' => 'redirectFileCouldNotBeUploaded', 'text' => 'Fout bij uploaden bestand'],
        ['label' => 'redirectEmptyValue', 'text' => 'Er is een leeg veld gevonden'],
        ['label' => 'redirectNotCorrectForColumn', 'text' => 'Geen geldige waarde voor kolom'],
        ['label' => 'redirectColomnOverwrite', 'text' => 'Gegevens zullen overschreven worden'],
        ['label' => 'followingError', 'text' => 'Foutmelding'],
        ['label' => 'redirestTestmode', 'text' => 'Test mode'],
        ['label' => 'redirectTooLong', 'text' => 'Maximum van 255 karakters overschreden bij: '],
    ],
];

// Database checks

if (!$oDb->tableExists('redirects')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `redirects`';
    if ($bInstall) {
        // add table
        $sQuery = '
            CREATE TABLE `redirects` (
            `redirectId` int(11) NOT NULL AUTO_INCREMENT,
            `type` int(11) NOT NULL,
            `pattern` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `newUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `online` int(1) NOT NULL DEFAULT "1",
            `created` timestamp NULL DEFAULT NULL,
            `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`redirectId`),
            UNIQUE KEY `type` (`type`, `pattern`),
            KEY `pattern`(`pattern`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

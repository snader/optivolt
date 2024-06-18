<?php
// Database checks

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'klanten'        => [
        'module'     => 'customers',
        'controller' => 'customer',
    ],
    'klantengroepen' => [
        'module'     => 'customers',
        'controller' => 'customerGroups',
    ],
    'klanten-import' => [
        'module'     => 'customers',
        'controller' => 'customerImport',
    ],
    'systemen-import' => [
        'module'     => 'customers',
        'controller' => 'systemsImport',
    ],
    'systemen-datumimport' => [
        'module'     => 'customers',
        'controller' => 'systemsInstallDateImport',
    ],
    'locaties'        => [
        'module'     => 'customers',
        'controller' => 'locations',
    ]
    ,'evaluaties'        => [
        'module'     => 'customers',
        'controller' => 'evaluation',
    ]
];

$aNeededClassRoutes = [

    'Customer'             => [
        'module' => 'customers',
    ],
    'CustomerManager'      => [
        'module' => 'customers',
    ],
    'CustomerGroup'        => [
        'module' => 'customers',
    ],
    'CustomerGroupManager' => [
        'module' => 'customers',
    ],
    'Locations'        => [
        'module' => 'customers',
    ],
    'Evaluation'             => [
        'module' => 'customers',
    ],
    'EvaluationManager'      => [
        'module' => 'customers',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'          => 'klanten',
        'icon'          => 'fa-user',
        'linkName'      => 'customer_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'customers_full'],
        ],
    ],
    [
        'name'          => 'locaties',
        'icon'          => 'fa-thumbtack',
        'linkName'      => 'locations_menu',
        'parentModuleName' => 'klanten',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'locations_full'],
        ],
    ],
    [
        'name'          => 'evaluaties',
        'icon'          => 'fa-star',
        'linkName'      => 'evaluations_menu',
        'parentModuleName' => 'klanten',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'evaluations_full'],
        ],
    ],
    [
        'name'             => 'klantengroepen',
        'icon'             => 'fa-users',
        'linkName'         => 'customer_group_menu',
        'parentModuleName' => 'klanten',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'customerGroups_full'],
        ],
    ],
    [
        'name'             => 'klanten-import',
        'icon'             => 'fa-file',
        'linkName'         => 'customer_import_menu',
        'parentModuleName' => 'klanten',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'customerImport_full'],
        ],
    ],
    [
        'name'             => 'systemen-import',
        'icon'             => 'fa-file',
        'linkName'         => 'system_import_menu',
        'parentModuleName' => 'klanten',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'systemImport_full'],
        ],
    ],
    [
        'name'             => 'systemen-datumimport',
        'icon'             => 'fa-file',
        'linkName'         => 'system_importdate_menu',
        'parentModuleName' => 'klanten',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'systemImportdate_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'system_import_menu', 'text' => 'Systemdatum'],
        ['label' => 'system_importdate_menu', 'text' => 'Systeem datumimport'],
        ['label' => 'customer_group_unique_name', 'text' => 'Systeemnaam'],
        ['label' => 'customer_group_unique_name_tooltip', 'text' => 'Vul een unieke systeemnaam in'],
        ['label' => 'customergroup_back_overview', 'text' => 'Terug naar het overzicht'],
        ['label' => 'amount_of_clients', 'text' => 'Aantal klanten'],
        ['label' => 'amount_of_clients_active', 'text' => 'Aantal actieve klanten'],
        ['label' => 'customer_group_menu', 'text' => 'Klantgroepen'],
        ['label' => 'customer_clients_not_on_this_list', 'text' => 'Klanten die niet in de groep'],
        ['label' => 'customer_add_to_group_button', 'text' => 'Voeg de geselecteerde klanten toe aan deze klantengroep'],
        ['label' => 'customer_clients_not_on_this_list_part2', 'text' => 'zijn toegevoegd'],
        ['label' => 'all_customer_groups', 'text' => 'Alle klantengroepen'],
        ['label' => 'customer_import_menu', 'text' => 'Klanten import'],
        ['label' => 'customer_group_not_delete', 'text' => 'Deze klantengroep kan niet verwijderd worden'],
        ['label' => 'customer_message_sent', 'text' => 'Het bericht is verzonden'],
        ['label' => 'customer_client_lowercase', 'text' => 'klant'],
        ['label' => 'customer_client_plural', 'text' => 'en'],
        ['label' => 'customer_not_added', 'text' => 'Er waren geen klanten toegevoegd'],
        ['label' => 'customer_added', 'text' => 'Klanten zijn toegevoegd'],
        ['label' => 'customer_add_to_group', 'text' => 'Voeg klanten toe aan deze klantengroep'],
        ['label' => 'customer_clients_not_on_this_list_part1', 'text' => 'Alle klanten die niet aan de klantengroep'],
        ['label' => 'customer_menu', 'text' => 'Klanten'],
        ['label' => 'customer_not_deleted', 'text' => 'Klant kan niet worden verwijderd'],
        ['label' => 'customer_deleted', 'text' => 'Klant is verwijderd'],
        ['label' => 'customer_not_saved', 'text' => 'Klant is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'customer_saved', 'text' => 'Klant is opgeslagen'],
        ['label' => 'customer_group_deleted', 'text' => 'Klantengroep is verwijderd'],
        ['label' => 'customer_group_not_deleted', 'text' => 'Klantengroep kan niet worden verwijderd'],
        ['label' => 'customer_group_saved', 'text' => 'Klantengroep is opgeslagen'],
        ['label' => 'customer_group_not_saved', 'text' => 'Klantengroep is niet opgeslagen, niet alle velden zijn (juist) ingevuld\''],
        ['label' => 'customer_groups', 'text' => 'Klantengroepen'],
        ['label' => 'customer_group_online_customers', 'text' => 'Online klanten voor deze groep'],
        ['label' => 'customer_group_title_tooltip', 'text' => 'Vul de naam van de klantengroep in'],
        ['label' => 'customer_group', 'text' => 'Klantengroep'],
        ['label' => 'customer_back_overview', 'text' => 'Terug naar het klanten overzicht'],
        ['label' => 'customer_no_group', 'text' => 'Er zijn geen klantgroepen om weer te geven'],
        ['label' => 'customer_delete_warning', 'text' => 'Alle klanten gekoppeld aan deze groep worden ontkoppeld!'],
        ['label' => 'customer_group_delete', 'text' => 'Verwijder klantengroep'],
        ['label' => 'customer_group_edit', 'text' => 'Bewerk klantengroep'],
        ['label' => 'customer_group_add', 'text' => 'Klantengroep toevoegen'],
        ['label' => 'customer_group_found', 'text' => 'Gevonden klantgroepen'],
        ['label' => 'customer_status_not_changed', 'text' => 'Klant niet gewijzigd'],
        ['label' => 'customer_online', 'text' => 'Klant online gezet'],
        ['label' => 'customer_offline', 'text' => 'Klant offline gezet'],
        ['label' => 'customer_no_customers', 'text' => 'Er zijn geen klanten om weer te geven met dit filter'],
        ['label' => 'customer_delete', 'text' => 'Verwijder klant'],
        ['label' => 'customer_edit', 'text' => 'Bewerk klant'],
        ['label' => 'customer_add', 'text' => 'Klant toevoegen'],
        ['label' => 'customer_found', 'text' => 'Gevonden klanten'],
        ['label' => 'customer_filter_customer', 'text' => 'Filter klanten'],
        ['label' => 'customer_filter_name_tooltip', 'text' => 'Zoek op de naam van de klantn'],
        ['label' => 'customer_signed_group', 'text' => 'Aangemeld voor klant groep(en):'],
        ['label' => 'customer_company_website', 'text' => 'Website'],
        ['label' => 'customer_company_phone', 'text' => 'Telefoonnummer zakelijk'],
        ['label' => 'customer_company_email', 'text' => 'E-mail zakelijk'],
        ['label' => 'customer_company_city', 'text' => 'Plaats zakelijk'],
        ['label' => 'customer_company_postal_code', 'text' => 'Postcode zakelijk'],
        ['label' => 'customer_company_address', 'text' => 'Adres zakelijk'],
        ['label' => 'customer_company_name', 'text' => 'Bedrijfsnaam'],
        ['label' => 'customer_set_online', 'text' => 'Online/offline'],
        ['label' => 'customer_client', 'text' => 'Klant'],
        ['label' => 'customer_to_many_failed_login_attempts', 'text' => 'Teveel foute logins'],
        ['label' => 'customer_unlock', 'text' => 'Blokkering opheffen'],
        ['label' => 'customer_locked', 'text' => 'Geblokkeerd'],
        ['label' => 'customer_lockedReason', 'text' => 'Rede (de)blokkering'],
        ['label' => 'customer_unlockedReason', 'text' => 'Rede deblokkeren'],
        ['label' => 'customer_unlock', 'text' => 'Blokkering opheffen'],
        ['label' => 'customer_unlock_reason', 'text' => 'Rede deblokkeren'],
        ['label' => 'evaluation_deleted', 'text' => 'Evaluatie verwijderd'],
        ['label' => 'evaluation_not_deleted', 'text' => 'Evaluatie niet verwijderd'],
        ['label' => 'system_evaluation', 'text' => 'Evaluatie'],
        
        ['label' => 'evaluation_not_deletable', 'text' => 'Evaluatie is niet verwijderbaar'],        
        ['label' => 'evaluation_deleted', 'text' => 'Evaluatie is verwijderd'],
        ['label' => 'evaluation_not_deleted', 'text' => 'Evaluatie kan niet worden verwijderd'],
        ['label' => 'evaluation_not_saved', 'text' => 'Evaluatie is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'evaluation_saved', 'text' => 'Evaluatie is opgeslagen'],
        ['label' => 'evaluation_not_edited', 'text' => 'Evaluatie kan niet worden bewerkt'],
        ['label' => 'evaluation_content', 'text' => 'Content'],

        ['label' => 'evaluation_set_online_tooltip', 'text' => 'Zet Evaluatie online OF offline'],
        ['label' => 'evaluation_not_changed', 'text' => 'Evaluatie niet gewijzigd'],
        ['label' => 'evaluation_is_offline', 'text' => 'Evaluatie offline gezet'],
        ['label' => 'evaluation_is_online', 'text' => 'Evaluatie online gezet'],
        ['label' => 'evaluation_no_system', 'text' => 'Er zijn geen evaluaties om weer te geven'],
        ['label' => 'evaluation_delete', 'text' => 'Verwijder Evaluatie'],
        ['label' => 'evaluation_edit', 'text' => 'Bewerk Evaluatie'],
        ['label' => 'evaluation_set_offline', 'text' => 'Evaluatie offline zetten'],
        ['label' => 'evaluation_set_online', 'text' => 'Evaluatie online zetten'],
        ['label' => 'evaluation_add', 'text' => 'Evaluatie toevoegen'],
        ['label' => 'evaluation_add_tooltip', 'text' => 'Nieuw Evaluatie toevoegen'],
        ['label' => 'evaluation_all', 'text' => 'Alle systemen'],
        ['label' => 'evaluation_filter', 'text' => 'Filter systemen'],
        
    ],
    'en' => [
        ['label' => 'customer_group_unique_name', 'text' => 'System name'],
        ['label' => 'customer_group_unique_name_tooltip', 'text' => 'Fill in a unique system name'],
        ['label' => 'customergroup_back_overview', 'text' => 'Back to overview'],
        ['label' => 'amount_of_clients', 'text' => 'Amount of clients'],
        ['label' => 'amount_of_clients_active', 'text' => 'Amount of active clients'],
        ['label' => 'amount_of_clients_bounced', 'text' => 'Amount of addresses bounced'],
        ['label' => 'customer_add_to_group_button', 'text' => 'Add selected customers to this customer group'],
        ['label' => 'customer_clients_not_on_this_list_part2', 'text' => '.'],
        ['label' => 'all_customer_groups', 'text' => 'All customer groups'],
        ['label' => 'customer_group_not_delete', 'text' => 'You can not delete this group'],
        ['label' => 'customer_message_sent', 'text' => 'The message is sent'],
        ['label' => 'customer_client_lowercase', 'text' => 'client'],
        ['label' => 'customer_client_plural', 'text' => 's'],
        ['label' => 'customer_receive_verification_tooltip', 'text' => 'Determine if client may receive verification mails from the communication module'],
        ['label' => 'customer_receive_verification', 'text' => 'Receives verification from communication module'],
        ['label' => 'customer_not_added', 'text' => 'The clients weren\'t added'],
        ['label' => 'customer_added', 'text' => 'Customers added'],
        ['label' => 'customer_add_to_group', 'text' => 'Add customers to this customer group'],
        ['label' => 'customer_clients_not_on_this_list', 'text' => 'Clients who are not in the group'],
        ['label' => 'customer_clients_not_on_this_list_part1', 'text' => 'Customers which are not yet added to the customer group'],
        ['label' => 'customer_menu', 'text' => 'Customers'],
        ['label' => 'customer_not_deleted', 'text' => 'Customer cannot be deleted'],
        ['label' => 'customer_deleted', 'text' => 'Customer has been deleted'],
        ['label' => 'customer_not_saved', 'text' => 'Customer has not been saved, not all fields are (correctly) filled in'],
        ['label' => 'customer_saved', 'text' => 'Customer has been stored'],
        ['label' => 'customer_group_deleted', 'text' => 'Customer group has been deleted'],
        ['label' => 'customer_group_not_deleted', 'text' => 'Customer group cannot be deleted'],
        ['label' => 'customer_group_saved', 'text' => 'Customer group has been saved'],
        ['label' => 'customer_group_not_saved', 'text' => 'Customer group is not saved, not all fields are (correctly) filled out'],
        ['label' => 'customer_groups', 'text' => 'Customer groups'],
        ['label' => 'customer_customer_sms', 'text' => 'May receive SMS'],
        ['label' => 'customer_customer_email', 'text' => 'May receive e-mail'],
        ['label' => 'customer_group_online_customers', 'text' => 'Online customers linked to this group'],
        ['label' => 'customer_group_title_tooltip', 'text' => 'Please, fill in the name of the group'],
        ['label' => 'customer_group', 'text' => 'Customer group'],
        ['label' => 'customer_back_overview', 'text' => 'Back to the customer list'],
        ['label' => 'customer_no_group', 'text' => 'There are no customer groups to display'],
        ['label' => 'customer_delete_warning', 'text' => 'All customers linked to this group will be unlinked!'],
        ['label' => 'customer_group_delete', 'text' => 'Delete customer group'],
        ['label' => 'customer_group_edit', 'text' => 'Edit customer group'],
        ['label' => 'customer_group_add', 'text' => 'Add customer group'],
        ['label' => 'customer_group_found', 'text' => 'Customer groups found'],
        ['label' => 'customer_status_not_changed', 'text' => 'Customer status has not changed'],
        ['label' => 'customer_online', 'text' => 'Customer placed online'],
        ['label' => 'customer_offline', 'text' => 'Customer placed offline'],
        ['label' => 'customer_no_customers', 'text' => 'There are no customers to display with this filter'],
        ['label' => 'customer_delete', 'text' => 'Delete customer'],
        ['label' => 'customer_edit', 'text' => 'Edit customer'],
        ['label' => 'customer_add', 'text' => 'Add customer'],
        ['label' => 'customer_found', 'text' => 'Found customers'],
        ['label' => 'customer_filter_customer', 'text' => 'Filter customers'],
        ['label' => 'customer_filter_name_tooltip', 'text' => 'Search on a part of the name of the customer'],
        ['label' => 'customer_client_logoff', 'text' => '(Customer has to log out manually)'],
        ['label' => 'customer_contact_email_tooltip', 'text' => 'Set if the customer may receive messages via email'],
        ['label' => 'customer_contact_sms_tooltip', 'text' => 'Set if the customer may receive messages via SMS'],
        ['label' => 'customer_contact_email', 'text' => 'Contact via email'],
        ['label' => 'customer_contact_sms', 'text' => 'Contact via SMS'],
        ['label' => 'customer_signed_group', 'text' => 'Signed up for customer group(s):'],
        ['label' => 'customer_company_website', 'text' => 'Company website'],
        ['label' => 'customer_company_phone', 'text' => 'Company phonenumber'],
        ['label' => 'customer_company_email', 'text' => 'Company email'],
        ['label' => 'customer_company_city', 'text' => 'Company city'],
        ['label' => 'customer_company_postal_code', 'text' => 'Company postal code'],
        ['label' => 'customer_company_address', 'text' => 'Company address'],
        ['label' => 'customer_company_name', 'text' => 'Company name'],
        ['label' => 'customer_set_online', 'text' => 'Place the customer online or offline'],
        ['label' => 'customer_client', 'text' => 'Customer'],
        ['label' => 'customer_to_many_failed_login_attempts', 'text' => 'Too many failed login attempts'],
        ['label' => 'customer_unlock', 'text' => 'Unlock'],
        ['label' => 'customer_locked', 'text' => 'Locked'],
        ['label' => 'customer_lockedReason', 'text' => 'Reason (un)locked'],
        ['label' => 'customer_unlockedReason', 'text' => 'Reasong unlock'],
        ['label' => 'customer_unlock', 'text' => 'Unlock'],
        ['label' => 'customer_unlock_reason', 'text' => 'Reason unlock'],
    ]
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'customer_login', 'text' => 'Inloggen', 'editable' => 0],
        ['label' => 'site_email_contact', 'text' => 'E-mailadres contactpersoon', 'editable' => 0],
        
        ['label' => 'site_debnr', 'text' => 'Debiteurennummer', 'editable' => 0],
        ['label' => 'site_companyname', 'text' => 'Bedrijfsnaam', 'editable' => 0],
        ['label' => 'site_companyaddress', 'text' => 'Adres', 'editable' => 0],
        ['label' => 'site_companypostalcode', 'text' => 'Postcode', 'editable' => 0],
        ['label' => 'site_companycity', 'text' => 'Plaats', 'editable' => 0],
        ['label' => 'site_contactpersonname', 'text' => 'Contactpersoon', 'editable' => 0],
        ['label' => 'site_contactpersonemail', 'text' => 'Contactpersoon e-mailadres', 'editable' => 0],
        ['label' => 'site_contactpersonphone', 'text' => 'Contactpersoon telefoon', 'editable' => 0],
        ['label' => 'customer_account_confirm', 'text' => 'Account bevestigen', 'editable' => 0],
        ['label' => 'controller_account', 'text' => 'account', 'editable' => 0],
        ['label' => 'controller_forgot_password', 'text' => 'wachtwoord-vergeten', 'editable' => 0],
        ['label' => 'controller_saved', 'text' => 'opgeslagen', 'editable' => 0],
        ['label' => 'controller_edit', 'text' => 'bewerken', 'editable' => 0],
        ['label' => 'controller_created', 'text' => 'aangemaakt', 'editable' => 0],
        ['label' => 'controller_confirm', 'text' => 'bevestigen', 'editable' => 0],
        ['label' => 'site_data_saved', 'text' => 'Gegevens zijn opgeslagen', 'editable' => 1],
        ['label' => 'site_want_to_become_a_customer', 'text' => 'Wilt u klant worden?', 'editable' => 1],
        ['label' => 'site_attempts_left', 'text' => 'poging(en) over', 'editable' => 1],
        ['label' => 'site_minutes_blocked', 'text' => 'minuten geblokkeerd', 'editable' => 1],
        ['label' => 'site_unsuccesful_attempts_your_account', 'text' => 'mislukte pogingen wordt uw gebruikersaccount', 'editable' => 1],
        ['label' => 'site_email_password_incorrect_after', 'text' => 'E-mailadres en/of wachtwoord zijn onjuist. Na', 'editable' => 1],
        ['label' => 'site_combination_email_code_incorrect', 'text' => 'Combinatie e-mailadres en code is niet juist', 'editable' => 1],
        ['label' => 'site_enter_email_code_new_password', 'text' => 'Vul uw e-mailadres, code uit de e-mail en uw nieuwe wachtwoord in', 'editable' => 1],
        ['label' => 'site_code_email_incorrect', 'text' => 'Code en/of e-mailadres niet juist', 'editable' => 1],
        ['label' => 'site_save', 'text' => 'Opslaan', 'editable' => 1],
        ['label' => 'site_confirmation_code_empty_check_spam', 'text' => 'U heeft uw code niet ingevuld. Code nog niet ontvangen? Check voor de zekerheid uw SPAM folder.', 'editable' => 1],
        ['label' => 'site_new_password', 'text' => 'Nieuw wachtwoord', 'editable' => 1],
        ['label' => 'site_change_password', 'text' => 'Wachtwoord wijzigen', 'editable' => 1],
        ['label' => 'site_confirmation_code', 'text' => 'Bevestigingscode', 'editable' => 1],
        ['label' => 'site_send_me_a_reset_link', 'text' => 'Stuur mij een reset link', 'editable' => 1],
        ['label' => 'site_register', 'text' => 'Aanmelden', 'editable' => 1],
        ['label' => 'site_mobile_phone', 'text' => 'Mobiele nummer', 'editable' => 1],
        ['label' => 'site_enter_your_house_number', 'text' => 'U heeft uw huisnummer niet ingevuld', 'editable' => 1],
        ['label' => 'site_you_did_not_confirm_your_password', 'text' => 'U heeft uw wachtwoord niet bevestigd', 'editable' => 1],
        ['label' => 'site_passwords_do_not_match', 'text' => 'Wachtwoorden komen niet overeen', 'editable' => 1],
        ['label' => 'site_enter_safe_password_8_digits', 'text' => 'U heeft geen veilig wachtwoord ingevuld (minimaal 8 tekens)', 'editable' => 1],
        ['label' => 'site_password_again', 'text' => 'Nogmaals wachtwoord', 'editable' => 1],
        ['label' => 'site_desired_password', 'text' => 'Gewenst wachtwoord', 'editable' => 1],
        ['label' => 'site_email_already_in_use', 'text' => 'Het ingevulde e-mailadres is al in gebruik', 'editable' => 1],
        ['label' => 'site_account_temporarily_blocked', 'text' => 'Account tijdelijk geblokkeerd', 'editable' => 1],
        ['label' => 'site_forgot_your_password', 'text' => 'Bent u uw wachtwoord vergeten?', 'editable' => 1],
        ['label' => 'site_forgot_your_password_short', 'text' => 'Wachtwoord vergeten?', 'editable' => 1],
        ['label' => 'site_enter_your_city', 'text' => 'U heeft uw woonplaats niet ingevuld', 'editable' => 1],
        ['label' => 'site_enter_your_postal_code', 'text' => 'U heeft uw postcode niet ingevuld', 'editable' => 1],
        ['label' => 'site_enter_valid_house_number', 'text' => 'Vul een geldig huisnummer in met alleen nummers', 'editable' => 1],
        ['label' => 'site_enter_your_street_name', 'text' => 'U heeft uw straatnaam niet ingevuld', 'editable' => 1],
        ['label' => 'site_enter_your_last_name', 'text' => 'U heeft uw achternaam niet ingevuld', 'editable' => 1],
        ['label' => 'site_enter_your_first_name', 'text' => 'U heeft uw voornaam niet ingevuld', 'editable' => 1],
        ['label' => 'site_enter_valid_email', 'text' => 'Vul een geldig e-mailadres in', 'editable' => 1],
        ['label' => 'site_same_as_invoice_details', 'text' => 'Zelfde als factuurgegevens', 'editable' => 1],
        ['label' => 'site_delivery_details', 'text' => 'Aflevergegevens', 'editable' => 1],
        ['label' => 'site_select_gender', 'text' => 'U heeft geen geslacht gekozen', 'editable' => 1],
        ['label' => 'site_female', 'text' => 'Vrouw', 'editable' => 1],
        ['label' => 'site_male', 'text' => 'Man', 'editable' => 1],
        ['label' => 'site_place_order', 'text' => 'Bestelling plaatsen', 'editable' => 1],
        ['label' => 'site_city', 'text' => 'Plaats', 'editable' => 1],
        ['label' => 'site_postal_code', 'text' => 'Postcode', 'editable' => 1],
        ['label' => 'site_addition', 'text' => 'Toevoeging', 'editable' => 1],
        ['label' => 'site_house_number', 'text' => 'Huisnummer', 'editable' => 1],
        ['label' => 'site_street', 'text' => 'Straat', 'editable' => 1],
        ['label' => 'site_last_name', 'text' => 'Achternaam', 'editable' => 1],
        ['label' => 'site_insertion', 'text' => 'Tussenvoegsel', 'editable' => 1],
        ['label' => 'site_first_name', 'text' => 'Voornaam', 'editable' => 1],
        ['label' => 'site_invoice_details', 'text' => 'Factuurgegevens', 'editable' => 1],
        ['label' => 'site_fill_in_your_password', 'text' => 'U heeft geen wachtwoord ingevuld', 'editable' => 1],
        ['label' => 'site_login', 'text' => 'Inloggen', 'editable' => 1],
        ['label' => 'site_password', 'text' => 'Wachtwoord', 'editable' => 1],
        ['label' => 'site_already_customer', 'text' => 'Bent u al klant?', 'editable' => 1],
        ['label' => 'site_enter_your_information', 'text' => 'Gegevens invullen', 'editable' => 1],
        ['label' => 'site_gender', 'text' => 'Geslacht', 'editable' => 1],
        ['label' => 'site_enter_your_insertion', 'text' => 'Voorvoegsel', 'editable' => 1],
        ['label' => 'site_enter_your_housenumber_addition', 'text' => 'Toevoeging', 'editable' => 1],
        ['label' => 'email_order_productname', 'text' => 'Productnaam', 'editable' => 1],
        ['label' => 'email_order_amount', 'text' => 'Aantal', 'editable' => 1],
        ['label' => 'email_order_price', 'text' => 'Prijs', 'editable' => 1],
        ['label' => 'email_order_deliverymethod', 'text' => 'Aflevermethode', 'editable' => 1],
        ['label' => 'email_order_paymentmethod', 'text' => 'Aflevermethode', 'editable' => 1],
        ['label' => 'email_order_total', 'text' => 'Totaal', 'editable' => 1],
        ['label' => 'email_order_vat', 'text' => 'BTW', 'editable' => 1],
        ['label' => 'email_order_total_ex_vat', 'text' => 'Totaal (excl. BTW)', 'editable' => 1],
    ],
];

// add page
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageAccount = PageManager::getPageByName('account'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `account`';
        if ($bInstall) {
            $oPageAccount             = new Page();
            $oPageAccount->languageId = DEFAULT_LANGUAGE_ID;
            $oPageAccount->name       = 'account';
            $oPageAccount->title      = 'Account';
            $oPageAccount->content    = '<p>U kunt hier inloggen of een account aanmaken. Als u een account aanmaakt hoeft u niet bij iedere bestelling uw gegevens in te vullen.</p>';
            $oPageAccount->shortTitle = 'Account';
            $oPageAccount->forceUrlPath('/account');
            $oPageAccount->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
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
                die('Can\'t create page `account`');
            }
        }
    }

    // add acount edit page
    if (!empty($oPageAccount)) {
        if (!($oPageAccountEdit = PageManager::getPageByName('account_edit', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_edit`';
            if ($bInstall) {
                $oPageAccountEdit               = new Page();
                $oPageAccountEdit->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountEdit->parentPageId = $oPageAccount->pageId;
                $oPageAccountEdit->name         = 'account_edit';
                $oPageAccountEdit->title        = 'Mijn gegevens';
                $oPageAccountEdit->content      = '<p>Hier ziet u de bij ons geregistreerde gegevens. Wijzigingen kunt u doorgeven via onze <a target="_blank" href="https://optivolt.nl/contact">contactpagina</a> op de website.</p>';
                $oPageAccountEdit->shortTitle   = 'Mijn gegevens';
                $oPageAccountEdit->forceUrlPath($oPageAccount->getUrlPath() . '/bewerken');
                $oPageAccountEdit->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountEdit->setIndexable(0);
                $oPageAccountEdit->setOnlineChangeable(0);
                $oPageAccountEdit->setDeletable(0);
                $oPageAccountEdit->setMayHaveSub(0);
                $oPageAccountEdit->setLockUrlPath(1);
                $oPageAccountEdit->setLockParent(1);
                $oPageAccountEdit->setHideImageManagement(1);
                $oPageAccountEdit->setHideFileManagement(1);
                $oPageAccountEdit->setHideLinkManagement(1);
                $oPageAccountEdit->setHideVideoLinkManagement(1);
                if ($oPageAccountEdit->isValid()) {
                    PageManager::savePage($oPageAccountEdit);
                } else {
                    _d($oPageAccountEdit->getInvalidProps());
                    die('Can\'t create page `account_edit`');
                }
            }
        }
    }


    if (!empty($oPageAccountEvaluation)) {
        if (!($oPageAccountEdit = PageManager::getPageByName('evaluation', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `evaluation`';
            if ($bInstall) {
                $oPageAccountEvaluation               = new Page();
                $oPageAccountEvaluation->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountEvaluation->parentPageId = $oPageAccount->pageId;
                $oPageAccountEvaluation->name         = 'evaluation';
                $oPageAccountEvaluation->title        = 'Evaluatie';
                $oPageAccountEvaluation->content      = '<p>Evaluatie / Evalution</p>';
                $oPageAccountEvaluation->shortTitle   = 'Evaluatie / Evalution';
                $oPageAccountEvaluation->forceUrlPath('/evaluation');
                $oPageAccountEvaluation->setControllerPath('/modules/customers/site/controllers/evaluation.cont.php');
                $oPageAccountEvaluation->setIndexable(0);
                $oPageAccountEvaluation->setOnlineChangeable(0);
                $oPageAccountEvaluation->setDeletable(0);
                $oPageAccountEvaluation->setMayHaveSub(0);
                $oPageAccountEvaluation->setLockUrlPath(1);
                $oPageAccountEvaluation->setLockParent(1);
                $oPageAccountEvaluation->setHideImageManagement(1);
                $oPageAccountEvaluation->setHideFileManagement(1);
                $oPageAccountEvaluation->setHideLinkManagement(1);
                $oPageAccountEvaluation->setHideVideoLinkManagement(1);
                if ($oPageAccountEvaluation->isValid()) {
                    PageManager::savePage($oPageAccountEvaluation);
                } else {
                    _d($oPageAccountEvaluation->getInvalidProps());
                    die('Can\'t create page `evaluation`');
                }
            }
        }
    }

    // add acount confirm page
    if (!empty($oPageAccount)) {
        if (!($oPageAccountConfirm = PageManager::getPageByName('account_confirm', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_confirm`';
            if ($bInstall) {
                $oPageAccountConfirm               = new Page();
                $oPageAccountConfirm->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountConfirm->parentPageId = $oPageAccount->pageId;
                $oPageAccountConfirm->name         = 'account_confirm';
                $oPageAccountConfirm->title        = 'Account bevestigen';
                $oPageAccountConfirm->content      = '<p>U ontvangt nu een bevestigingcode per e-mail. U kunt in de e-mail op de link klikken of de code hieronder invullen. Als u deze e-mail niet binnen enkele minuten in uw mailbox kunt vinden kijk dan ook bij uw SPAM. Mocht u de e-mail helemaal niet ontvangen, neem dan contact met ons op.</p>';
                $oPageAccountConfirm->shortTitle   = 'Account bevestigen';
                $oPageAccountConfirm->forceUrlPath($oPageAccount->getUrlPath() . '/bevestigen');
                $oPageAccountConfirm->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountConfirm->setInMenu(0);
                $oPageAccountConfirm->setIndexable(0);
                $oPageAccountConfirm->setOnlineChangeable(0);
                $oPageAccountConfirm->setDeletable(0);
                $oPageAccountConfirm->setMayHaveSub(0);
                $oPageAccountConfirm->setLockUrlPath(1);
                $oPageAccountConfirm->setLockParent(1);
                $oPageAccountConfirm->setHideImageManagement(1);
                $oPageAccountConfirm->setHideFileManagement(1);
                $oPageAccountConfirm->setHideLinkManagement(1);
                $oPageAccountConfirm->setHideVideoLinkManagement(1);
                if ($oPageAccountConfirm->isValid()) {
                    PageManager::savePage($oPageAccountConfirm);
                } else {
                    _d($oPageAccountConfirm->getInvalidProps());
                    die('Can\'t create page `account_confirm`');
                }
            }
        }
    }

    // add acount created page
    if (!empty($oPageAccount)) {
        if (!($oPageAccountCreated = PageManager::getPageByName('account_created', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_created`';
            if ($bInstall) {
                $oPageAccountCreated               = new Page();
                $oPageAccountCreated->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountCreated->parentPageId = $oPageAccount->pageId;
                $oPageAccountCreated->name         = 'account_created';
                $oPageAccountCreated->title        = 'Account aangemaakt';
                $oPageAccountCreated->content      = '<p>Uw account is aangemaakt. U bent direct ingelogd en kunt er direct gebruik van maken.</p>';
                $oPageAccountCreated->shortTitle   = 'Account aangemaakt';
                $oPageAccountCreated->forceUrlPath($oPageAccount->getUrlPath() . '/aangemaakt');
                $oPageAccountCreated->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountCreated->setInMenu(0);
                $oPageAccountCreated->setIndexable(0);
                $oPageAccountCreated->setOnlineChangeable(0);
                $oPageAccountCreated->setDeletable(0);
                $oPageAccountCreated->setMayHaveSub(0);
                $oPageAccountCreated->setLockUrlPath(1);
                $oPageAccountCreated->setLockParent(1);
                $oPageAccountCreated->setHideImageManagement(1);
                $oPageAccountCreated->setHideFileManagement(1);
                $oPageAccountCreated->setHideLinkManagement(1);
                $oPageAccountCreated->setHideVideoLinkManagement(1);
                if ($oPageAccountCreated->isValid()) {
                    PageManager::savePage($oPageAccountCreated);
                } else {
                    _d($oPageAccountCreated->getInvalidProps());
                    die('Can\'t create page `account_created`');
                }
            }
        }
    }

    // add acount forgot password page
    if (!empty($oPageAccount)) {
        if (!($oPageAccountForgotPass = PageManager::getPageByName('account_forgot_password', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_forgot_password`';
            if ($bInstall) {
                $oPageAccountForgotPass               = new Page();
                $oPageAccountForgotPass->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountForgotPass->parentPageId = $oPageAccount->pageId;
                $oPageAccountForgotPass->name         = 'account_forgot_password';
                $oPageAccountForgotPass->title        = 'Wachtwoord vergeten';
                $oPageAccountForgotPass->content      = '<p>Vul uw e-mailadres in. U ontvangt een e-mail met een link waarmee u uw wachtwoord kunt resetten.</p>';
                $oPageAccountForgotPass->shortTitle   = 'Wachtwoord vergeten';
                $oPageAccountForgotPass->forceUrlPath($oPageAccount->getUrlPath() . '/wachtwoord-vergeten');
                $oPageAccountForgotPass->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountForgotPass->setIndexable(0);
                $oPageAccountForgotPass->setOnlineChangeable(0);
                $oPageAccountForgotPass->setDeletable(0);
                $oPageAccountForgotPass->setMayHaveSub(0);
                $oPageAccountForgotPass->setLockUrlPath(1);
                $oPageAccountForgotPass->setLockParent(1);
                $oPageAccountForgotPass->setHideImageManagement(1);
                $oPageAccountForgotPass->setHideFileManagement(1);
                $oPageAccountForgotPass->setHideLinkManagement(1);
                $oPageAccountForgotPass->setHideVideoLinkManagement(1);
                if ($oPageAccountForgotPass->isValid()) {
                    PageManager::savePage($oPageAccountForgotPass);
                } else {
                    _d($oPageAccountForgotPass->getInvalidProps());
                    die('Can\'t create page `account_forgot_password`');
                }
            }
        }
    }

    // add acount forgot password edit page
    if (!empty($oPageAccountForgotPass)) {
        if (!($oPageAccountForgotPassEdit = PageManager::getPageByName('account_forgot_password_edit', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_forgot_password_edit`';
            if ($bInstall) {
                $oPageAccountForgotPassEdit               = new Page();
                $oPageAccountForgotPassEdit->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountForgotPassEdit->parentPageId = $oPageAccountForgotPass->pageId;
                $oPageAccountForgotPassEdit->name         = 'account_forgot_password_edit';
                $oPageAccountForgotPassEdit->title        = 'Wachtwoord bewerken';
                $oPageAccountForgotPassEdit->content      = '<p>Wij sturen u een e-mail met een link waarmee u uw wachtwoord kunt resetten. Als u deze niet binnen enkele minuten ontvangt controleer dan uw SPAM folder of neem <a href="/contact">contact</a>&nbsp;met ons op.</p>';
                $oPageAccountForgotPassEdit->shortTitle   = 'Wachtwoord bewerken';
                $oPageAccountForgotPassEdit->forceUrlPath($oPageAccountForgotPass->getUrlPath() . '/bewerken');
                $oPageAccountForgotPassEdit->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountForgotPassEdit->setInMenu(0);
                $oPageAccountForgotPassEdit->setIndexable(0);
                $oPageAccountForgotPassEdit->setOnlineChangeable(0);
                $oPageAccountForgotPassEdit->setDeletable(0);
                $oPageAccountForgotPassEdit->setMayHaveSub(0);
                $oPageAccountForgotPassEdit->setLockUrlPath(1);
                $oPageAccountForgotPassEdit->setLockParent(1);
                $oPageAccountForgotPassEdit->setHideImageManagement(1);
                $oPageAccountForgotPassEdit->setHideFileManagement(1);
                $oPageAccountForgotPassEdit->setHideLinkManagement(1);
                $oPageAccountForgotPassEdit->setHideVideoLinkManagement(1);
                if ($oPageAccountForgotPassEdit->isValid()) {
                    PageManager::savePage($oPageAccountForgotPassEdit);
                } else {
                    _d($oPageAccountForgotPassEdit->getInvalidProps());
                    die('Can\'t create page `account_forgot_password_edit`');
                }
            }
        }
    }

    // add acount forgot password saved page
    if (!empty($oPageAccountForgotPass)) {
        if (!($oPageAccountForgotPassSaved = PageManager::getPageByName('account_forgot_password_saved', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_forgot_password_saved`';
            if ($bInstall) {
                $oPageAccountForgotPassSaved               = new Page();
                $oPageAccountForgotPassSaved->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountForgotPassSaved->parentPageId = $oPageAccountForgotPass->pageId;
                $oPageAccountForgotPassSaved->name         = 'account_forgot_password_saved';
                $oPageAccountForgotPassSaved->title        = 'Wachtwoord opgeslagen';
                $oPageAccountForgotPassSaved->content      = '<p>Uw nieuwe wachtwoord is opgeslagen. U bent direct ingelogd.</p>';
                $oPageAccountForgotPassSaved->shortTitle   = 'Wachtwoord opgeslagen';
                $oPageAccountForgotPassSaved->forceUrlPath($oPageAccountForgotPass->getUrlPath() . '/opgeslagen');
                $oPageAccountForgotPassSaved->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountForgotPassSaved->setInMenu(0);
                $oPageAccountForgotPassSaved->setIndexable(0);
                $oPageAccountForgotPassSaved->setOnlineChangeable(0);
                $oPageAccountForgotPassSaved->setDeletable(0);
                $oPageAccountForgotPassSaved->setMayHaveSub(0);
                $oPageAccountForgotPassSaved->setLockUrlPath(1);
                $oPageAccountForgotPassSaved->setLockParent(1);
                $oPageAccountForgotPassSaved->setHideImageManagement(1);
                $oPageAccountForgotPassSaved->setHideFileManagement(1);
                $oPageAccountForgotPassSaved->setHideLinkManagement(1);
                $oPageAccountForgotPassSaved->setHideVideoLinkManagement(1);
                if ($oPageAccountForgotPassSaved->isValid()) {
                    PageManager::savePage($oPageAccountForgotPassSaved);
                } else {
                    _d($oPageAccountForgotPassSaved->getInvalidProps());
                    die('Can\'t create page `account_forgot_password_saved`');
                }
            }
        }
    }

    // add acount logout page
    if (!empty($oPageAccount)) {
        if (!($oPageAccountLogout = PageManager::getPageByName('account_logout', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_logout`';
            if ($bInstall) {
                $oPageAccountLogout               = new Page();
                $oPageAccountLogout->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageAccountLogout->parentPageId = $oPageAccount->pageId;
                $oPageAccountLogout->name         = 'account_logout';
                $oPageAccountLogout->title        = 'Uitloggen';
                $oPageAccountLogout->content      = '';
                $oPageAccountLogout->shortTitle   = 'Uitloggen';
                $oPageAccountLogout->forceUrlPath($oPageAccount->getUrlPath() . '/logout');
                $oPageAccountLogout->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oPageAccountLogout->setIndexable(0);
                $oPageAccountLogout->setOnlineChangeable(0);
                $oPageAccountLogout->setDeletable(0);
                $oPageAccountLogout->setMayHaveSub(0);
                $oPageAccountLogout->setLockUrlPath(1);
                $oPageAccountLogout->setLockParent(1);
                $oPageAccountLogout->setHideImageManagement(1);
                $oPageAccountLogout->setHideFileManagement(1);
                $oPageAccountLogout->setHideLinkManagement(1);
                $oPageAccountLogout->setHideVideoLinkManagement(1);
                if ($oPageAccountLogout->isValid()) {
                    PageManager::savePage($oPageAccountLogout);
                } else {
                    _d($oPageAccountLogout->getInvalidProps());
                    die('Can\'t create page `account_logout`');
                }
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oNewPageAccount = PageManager::getPageByName('account', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account page
                $oNewPageAccount             = new Page();
                $oNewPageAccount->languageId = $oLocale->languageId;
                $oNewPageAccount->name       = 'account';
                $oNewPageAccount->title      = 'Account';
                $oNewPageAccount->content    = '<p>Here you can login or create an account. If you create an account you do not need to fill in your data with every order.</p>';
                $oNewPageAccount->shortTitle = 'Account';
                $oNewPageAccount->forceUrlPath('/account');
                $oNewPageAccount->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccount->setOnlineChangeable(0);
                $oNewPageAccount->setDeletable(0);
                $oNewPageAccount->setMayHaveSub(0);
                $oNewPageAccount->setLockUrlPath(1);
                $oNewPageAccount->setLockParent(1);
                $oNewPageAccount->setHideImageManagement(1);
                $oNewPageAccount->setHideFileManagement(1);
                $oNewPageAccount->setHideLinkManagement(1);
                $oNewPageAccount->setHideVideoLinkManagement(1);
                if ($oNewPageAccount->isValid()) {
                    PageManager::savePage($oNewPageAccount);
                } else {
                    _d($oNewPageAccount->getInvalidProps());
                    die('Can\'t create page `account`');
                }
            }
        }

        if (!($oNewPageAccountEdit = PageManager::getPageByName('account_edit', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_edit` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account edit page
                $oNewPageAccountEdit               = new Page();
                $oNewPageAccountEdit->languageId   = $oLocale->languageId;
                $oNewPageAccountEdit->parentPageId = $oNewPageAccount->pageId;
                $oNewPageAccountEdit->name         = 'account_edit';
                $oNewPageAccountEdit->title        = 'My data';
                $oNewPageAccountEdit->content      = '<p>Here you can edit your data.</p>';
                $oNewPageAccountEdit->shortTitle   = 'My data';
                $oNewPageAccountEdit->forceUrlPath('/account/edit');
                $oNewPageAccountEdit->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountEdit->setIndexable(0);
                $oNewPageAccountEdit->setOnlineChangeable(0);
                $oNewPageAccountEdit->setDeletable(0);
                $oNewPageAccountEdit->setMayHaveSub(0);
                $oNewPageAccountEdit->setLockUrlPath(1);
                $oNewPageAccountEdit->setLockParent(1);
                $oNewPageAccountEdit->setHideImageManagement(1);
                $oNewPageAccountEdit->setHideFileManagement(1);
                $oNewPageAccountEdit->setHideLinkManagement(1);
                $oNewPageAccountEdit->setHideVideoLinkManagement(1);
                if ($oNewPageAccountEdit->isValid()) {
                    PageManager::savePage($oNewPageAccountEdit);
                } else {
                    _d($oNewPageAccountEdit->getInvalidProps());
                    die('Can\'t create page `account_edit`');
                }
            }
        }

        if (!($oNewPageAccountConfirm = PageManager::getPageByName('account_confirm', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_confirm` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account confirm page
                $oNewPageAccountConfirm               = new Page();
                $oNewPageAccountConfirm->languageId   = $oLocale->languageId;
                $oNewPageAccountConfirm->parentPageId = $oNewPageAccount->pageId;
                $oNewPageAccountConfirm->name         = 'account_confirm';
                $oNewPageAccountConfirm->title        = 'Confirm account';
                $oNewPageAccountConfirm->content      = '<p>You will receive a confirmation code by e-mail. You can click or enter the code below in the e-mail link. If you can find this e-mail out of your mailbox within a few minutes, please check also your SPAM. If you did not receive the email, please <a href="/contact">contact</a> us.</p>';
                $oNewPageAccountConfirm->shortTitle   = 'Confirm account';
                $oNewPageAccountConfirm->forceUrlPath('/account/confirm');
                $oNewPageAccountConfirm->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountConfirm->setInMenu(0);
                $oNewPageAccountConfirm->setIndexable(0);
                $oNewPageAccountConfirm->setOnlineChangeable(0);
                $oNewPageAccountConfirm->setDeletable(0);
                $oNewPageAccountConfirm->setMayHaveSub(0);
                $oNewPageAccountConfirm->setLockUrlPath(1);
                $oNewPageAccountConfirm->setLockParent(1);
                $oNewPageAccountConfirm->setHideImageManagement(1);
                $oNewPageAccountConfirm->setHideFileManagement(1);
                $oNewPageAccountConfirm->setHideLinkManagement(1);
                $oNewPageAccountConfirm->setHideVideoLinkManagement(1);
                if ($oNewPageAccountConfirm->isValid()) {
                    PageManager::savePage($oNewPageAccountConfirm);
                } else {
                    _d($oNewPageAccountConfirm->getInvalidProps());
                    die('Can\'t create page `account_confirm`');
                }
            }
        }

        if (!($oNewPageAccountCreated = PageManager::getPageByName('account_created', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_created` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account created page
                $oNewPageAccountCreated               = new Page();
                $oNewPageAccountCreated->languageId   = $oLocale->languageId;
                $oNewPageAccountCreated->parentPageId = $oNewPageAccount->pageId;
                $oNewPageAccountCreated->name         = 'account_created';
                $oNewPageAccountCreated->title        = 'Account created';
                $oNewPageAccountCreated->content      = '<p>Your account has been created. You are instantly logged in.</p>';
                $oNewPageAccountCreated->shortTitle   = 'Account created';
                $oNewPageAccountCreated->forceUrlPath('/account/created');
                $oNewPageAccountCreated->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountCreated->setInMenu(0);
                $oNewPageAccountCreated->setIndexable(0);
                $oNewPageAccountCreated->setOnlineChangeable(0);
                $oNewPageAccountCreated->setDeletable(0);
                $oNewPageAccountCreated->setMayHaveSub(0);
                $oNewPageAccountCreated->setLockUrlPath(1);
                $oNewPageAccountCreated->setLockParent(1);
                $oNewPageAccountCreated->setHideImageManagement(1);
                $oNewPageAccountCreated->setHideFileManagement(1);
                $oNewPageAccountCreated->setHideLinkManagement(1);
                $oNewPageAccountCreated->setHideVideoLinkManagement(1);
                if ($oNewPageAccountCreated->isValid()) {
                    PageManager::savePage($oNewPageAccountCreated);
                } else {
                    _d($oNewPageAccountCreated->getInvalidProps());
                    die('Can\'t create page `account_created`');
                }
            }
        }

        if (!($oNewPageAccountForgotPass = PageManager::getPageByName('account_forgot_password', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_forgot_password` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account forgot password page
                $oNewPageAccountForgotPass               = new Page();
                $oNewPageAccountForgotPass->languageId   = $oLocale->languageId;
                $oNewPageAccountForgotPass->parentPageId = $oNewPageAccount->pageId;
                $oNewPageAccountForgotPass->name         = 'account_forgot_password';
                $oNewPageAccountForgotPass->title        = 'Forgot your password';
                $oNewPageAccountForgotPass->content      = '<p>Please enter your email address. You will receive an email with a link that lets you reset your password.</p>';
                $oNewPageAccountForgotPass->shortTitle   = 'Forgot your password';
                $oNewPageAccountForgotPass->forceUrlPath('/account/forgot-password');
                $oNewPageAccountForgotPass->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountForgotPass->setIndexable(0);
                $oNewPageAccountForgotPass->setOnlineChangeable(0);
                $oNewPageAccountForgotPass->setDeletable(0);
                $oNewPageAccountForgotPass->setMayHaveSub(0);
                $oNewPageAccountForgotPass->setLockUrlPath(1);
                $oNewPageAccountForgotPass->setLockParent(1);
                $oNewPageAccountForgotPass->setHideImageManagement(1);
                $oNewPageAccountForgotPass->setHideFileManagement(1);
                $oNewPageAccountForgotPass->setHideLinkManagement(1);
                $oNewPageAccountForgotPass->setHideVideoLinkManagement(1);
                if ($oNewPageAccountForgotPass->isValid()) {
                    PageManager::savePage($oNewPageAccountForgotPass);
                } else {
                    _d($oNewPageAccountForgotPass->getInvalidProps());
                    die('Can\'t create page `account_forgot_password`');
                }
            }
        }

        if (!($oNewPageAccountForgotPassEdit = PageManager::getPageByName('account_forgot_password_edit', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_forgot_password_edit` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account forgot password edit page
                $oNewPageAccountForgotPassEdit               = new Page();
                $oNewPageAccountForgotPassEdit->languageId   = $oLocale->languageId;
                $oNewPageAccountForgotPassEdit->parentPageId = $oNewPageAccountForgotPass->pageId;
                $oNewPageAccountForgotPassEdit->name         = 'account_forgot_password_edit';
                $oNewPageAccountForgotPassEdit->title        = 'Edit password';
                $oNewPageAccountForgotPassEdit->content      = '<p>You will receive an email with a link that lets you reset your password. If you do not receive your check within minutes SPAM folder or <a href="/' . $oLocale->getURLPrefix(
                    ) . '/contact">contact</a> us.</p>';
                $oNewPageAccountForgotPassEdit->shortTitle   = 'Edit password';
                $oNewPageAccountForgotPassEdit->forceUrlPath('/account/forgot-password/edit');
                $oNewPageAccountForgotPassEdit->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountForgotPassEdit->setInMenu(0);
                $oNewPageAccountForgotPassEdit->setIndexable(0);
                $oNewPageAccountForgotPassEdit->setOnlineChangeable(0);
                $oNewPageAccountForgotPassEdit->setDeletable(0);
                $oNewPageAccountForgotPassEdit->setMayHaveSub(0);
                $oNewPageAccountForgotPassEdit->setLockUrlPath(1);
                $oNewPageAccountForgotPassEdit->setLockParent(1);
                $oNewPageAccountForgotPassEdit->setHideImageManagement(1);
                $oNewPageAccountForgotPassEdit->setHideFileManagement(1);
                $oNewPageAccountForgotPassEdit->setHideLinkManagement(1);
                $oNewPageAccountForgotPassEdit->setHideVideoLinkManagement(1);
                if ($oNewPageAccountForgotPassEdit->isValid()) {
                    PageManager::savePage($oNewPageAccountForgotPassEdit);
                } else {
                    _d($oNewPageAccountForgotPassEdit->getInvalidProps());
                    die('Can\'t create page `account_forgot_password_edit`');
                }
            }
        }

        if (!($oNewPageAccountForgotPassSaved = PageManager::getPageByName('account_forgot_password_saved', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_forgot_password_saved` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account forgot password saved page
                $oNewPageAccountForgotPassSaved               = new Page();
                $oNewPageAccountForgotPassSaved->languageId   = $oLocale->languageId;
                $oNewPageAccountForgotPassSaved->parentPageId = $oNewPageAccountForgotPass->pageId;
                $oNewPageAccountForgotPassSaved->name         = 'account_forgot_password_saved';
                $oNewPageAccountForgotPassSaved->title        = 'Password saved';
                $oNewPageAccountForgotPassSaved->content      = '<p>Your new password has been saved. You are instantly logged in.</p>';
                $oNewPageAccountForgotPassSaved->shortTitle   = 'Password saved';
                $oNewPageAccountForgotPassSaved->forceUrlPath('/account/forgot-password/saved');
                $oNewPageAccountForgotPassSaved->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountForgotPassSaved->setInMenu(0);
                $oNewPageAccountForgotPassSaved->setIndexable(0);
                $oNewPageAccountForgotPassSaved->setOnlineChangeable(0);
                $oNewPageAccountForgotPassSaved->setDeletable(0);
                $oNewPageAccountForgotPassSaved->setMayHaveSub(0);
                $oNewPageAccountForgotPassSaved->setLockUrlPath(1);
                $oNewPageAccountForgotPassSaved->setLockParent(1);
                $oNewPageAccountForgotPassSaved->setHideImageManagement(1);
                $oNewPageAccountForgotPassSaved->setHideFileManagement(1);
                $oNewPageAccountForgotPassSaved->setHideLinkManagement(1);
                $oNewPageAccountForgotPassSaved->setHideVideoLinkManagement(1);
                if ($oNewPageAccountForgotPassSaved->isValid()) {
                    PageManager::savePage($oNewPageAccountForgotPassSaved);
                } else {
                    _d($oNewPageAccountForgotPassSaved->getInvalidProps());
                    die('Can\'t create page `account_forgot_password_saved`');
                }
            }
        }

        if (!($oNewPageAccountLogout = PageManager::getPageByName('account_logout', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `account_logout` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create account logout page
                $oNewPageAccountLogout               = new Page();
                $oNewPageAccountLogout->languageId   = $oLocale->languageId;
                $oNewPageAccountLogout->parentPageId = $oNewPageAccount->pageId;
                $oNewPageAccountLogout->name         = 'account_logout';
                $oNewPageAccountLogout->title        = 'Log out';
                $oNewPageAccountLogout->content      = '';
                $oNewPageAccountLogout->shortTitle   = 'Log out';
                $oNewPageAccountLogout->forceUrlPath('/account/logout');
                $oNewPageAccountLogout->setControllerPath('/modules/customers/site/controllers/customer.cont.php');
                $oNewPageAccountLogout->setIndexable(0);
                $oNewPageAccountLogout->setOnlineChangeable(0);
                $oNewPageAccountLogout->setDeletable(0);
                $oNewPageAccountLogout->setMayHaveSub(0);
                $oNewPageAccountLogout->setLockUrlPath(1);
                $oNewPageAccountLogout->setLockParent(1);
                $oNewPageAccountLogout->setHideImageManagement(1);
                $oNewPageAccountLogout->setHideFileManagement(1);
                $oNewPageAccountLogout->setHideLinkManagement(1);
                $oNewPageAccountLogout->setHideVideoLinkManagement(1);
                if ($oNewPageAccountLogout->isValid()) {
                    PageManager::savePage($oNewPageAccountLogout);
                } else {
                    _d($oNewPageAccountLogout->getInvalidProps());
                    die('Can\'t create page `account_logout`');
                }
            }
        }
    }
}

if ($oDb->tableExists('template_groups') && $oDb->tableExists('template_groups')) {
    if (!($oTemplateGroupAccounts = TemplateGroupManager::getTemplateGroupByName('account'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template group `account`';
        if ($bInstall) {
            $oTemplateGroupAccounts                    = new TemplateGroup();
            $oTemplateGroupAccounts->name              = 'account';
            $oTemplateGroupAccounts->templateGroupName = 'Account';
            $oTemplateGroupAccounts->templateVariables = '
[customer_firstName]
[customer_insertion]
[customer_lastName]
[customer_fullName]
[customer_confirm_url]
[customer_forgot_password_edit_url]
[customer_confirmCode]
[customer_email]
            ';
            if ($oTemplateGroupAccounts->isValid()) {
                TemplateGroupManager::saveTemplateGroup($oTemplateGroupAccounts);
            } else {
                _d($oTemplateGroupAccounts->getInvalidProps());
                die('Can\'t create template group `account`');
            }
        }
    }
}

if (!empty($oTemplateGroupAccounts)) {
    if (!($oTemplate1 = TemplateManager::getTemplateByName('account_confirm', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template `account_confirm`';
        if ($bInstall) {
            $oTemplate1                  = new Template();
            $oTemplate1->languageId      = DEFAULT_LANGUAGE_ID;
            $oTemplate1->description     = 'Account bevestigen';
            $oTemplate1->type            = Template::TYPE_EMAIL;
            $oTemplate1->templateGroupId = $oTemplateGroupAccounts->templateGroupId;
            $oTemplate1->subject         = '[CLIENT_NAME] | account bevestingen';
            $oTemplate1->template        = '
<p>Beste [customer_firstName],</p>
<p>Bedankt voor uw aanmelding op [CLIENT_URL]. Klik op de onderstaande link om uw account te activeren.</p>
<p><a href="[customer_confirm_url]?code=[customer_confirmCode]&amp;email=[customer_email]">[customer_confirm_url]?code=[customer_confirmCode]&amp;email=[customer_email]</a></p>
<p>Indien de link niet werkt kunt u ook de link in de browser kopieren en plakken of de code invullen op het scherm wat u te zien kreeg na het bestelformulier.</p>
<p>De code is: [customer_confirmCode]</p>
<p>Mocht u nog vragen hebben kunt u contact met ons op nemen.</p>
<p>Met vriendelijke groet,</p>
<p>[CLIENT_NAME]</p>
            ';
            $oTemplate1->name            = 'account_confirm';
            $oTemplate1->setEditable(1);
            $oTemplate1->setDeletable(0);
            if ($oTemplate1->isValid()) {
                TemplateManager::saveTemplate($oTemplate1);
            } else {
                _d($oTemplate1->getInvalidProps());
                die('Can\'t create template `account_confirm`');
            }
        }
    }

    if (!($oTemplate2 = TemplateManager::getTemplateByName('account_new_password', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing template `account_new_password`';
        if ($bInstall) {
            $oTemplate2                  = new Template();
            $oTemplate2->languageId      = DEFAULT_LANGUAGE_ID;
            $oTemplate2->description     = 'Nieuw wachtwoord';
            $oTemplate2->type            = Template::TYPE_EMAIL;
            $oTemplate2->templateGroupId = $oTemplateGroupAccounts->templateGroupId;
            $oTemplate2->subject         = '[CLIENT_NAME] | nieuw wachtwoord';
            $oTemplate2->template        = '
<p>Beste [customer_firstName],</p>
<p>Klik op de onderstaande link om uw wachtwoord te bewerken.</p>
<p><a href="[customer_forgot_password_edit_url]?code=[customer_confirmCode]&amp;email=[customer_email]">[customer_forgot_password_edit_url]?code=[customer_confirmCode]&amp;email=[customer_email]</a></p>
<p>Indien de link niet werkt kunt u de link in de browser kopieren en plakken of de code invullen op het scherm wat u te zien kreeg na het bestelformulier.</p>
<p>De code is: [customer_confirmCode]</p>
<p>Mocht u vragen hebben kunt u contact met ons op nemen.</p>
<p>Met vriendelijke groet,</p>
<p>[CLIENT_NAME]</p>
            ';
            $oTemplate2->name            = 'account_new_password';
            $oTemplate2->setEditable(1);
            $oTemplate2->setDeletable(0);
            if ($oTemplate2->isValid()) {
                TemplateManager::saveTemplate($oTemplate2);
            } else {
                _d($oTemplate2->getInvalidProps());
                die('Can\'t create template `account_new_password`');
            }
        }
    }

    // check if extra language is installed
    $aLocales = LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]);
    if (count($aLocales) > 0) {
        foreach ($aLocales as $oLocale) {
            if (!($oTemplate1 = TemplateManager::getTemplateByName('account_confirm', $oLocale->languageId))) {
                $aLogs[$sModuleName]['errors'][] = 'Missing template `account_confirm` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
                if ($bInstall) {
                    $oTemplate1                  = new Template();
                    $oTemplate1->languageId      = $oLocale->languageId;
                    $oTemplate1->description     = 'Account bevestigen';
                    $oTemplate1->type            = Template::TYPE_EMAIL;
                    $oTemplate1->templateGroupId = $oTemplateGroupAccounts->templateGroupId;
                    $oTemplate1->subject         = '[CLIENT_NAME] | confirm account';
                    $oTemplate1->template        = '
<p>Dear [customer_firstName],</p>
<p>Thank you for signing up [CLIENT_URL]. Click the link below to activate your account.</p>
<p><a href="[customer_confirm_url]?code=[customer_confirmCode]&amp;email=[customer_email]">[customer_confirm_url]?code=[customer_confirmCode]&amp;email=[customer_email]</a></p>
<p>If the link does not work, you can also copy and paste the link in the browser, or enter the code on the screen you received after the order form.</p>
<p>Your code is: [customer_confirmCode]</p>
<p>Do you have any questions? Do not hesitate to contact us.</p>
<p>Kind regards,</p>
<p>[CLIENT_NAME]</p>
            ';
                    $oTemplate1->name            = 'account_confirm';
                    $oTemplate1->setEditable(1);
                    $oTemplate1->setDeletable(0);
                    if ($oTemplate1->isValid()) {
                        TemplateManager::saveTemplate($oTemplate1);
                    } else {
                        _d($oTemplate1->getInvalidProps());
                        die('Can\'t create template `account_confirm` for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                    }
                }
            }

            if (!($oTemplate2 = TemplateManager::getTemplateByName('account_new_password', $oLocale->languageId))) {
                $aLogs[$sModuleName]['errors'][] = 'Missing template `account_new_password` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
                if ($bInstall) {
                    $oTemplate2                  = new Template();
                    $oTemplate2->languageId      = $oLocale->languageId;
                    $oTemplate2->description     = 'Nieuw wachtwoord';
                    $oTemplate2->type            = Template::TYPE_EMAIL;
                    $oTemplate2->templateGroupId = $oTemplateGroupAccounts->templateGroupId;
                    $oTemplate2->subject         = '[CLIENT_NAME] | new password';
                    $oTemplate2->template        = '
<p>Dear [customer_firstName],</p>
<p>Click the link below to edit your password.</p>
<p><a href="[customer_forgot_password_edit_url]?code=[customer_confirmCode]&amp;email=[customer_email]">[customer_forgot_password_edit_url]?code=[customer_confirmCode]&amp;email=[customer_email]</a></p>
<p>If the link does not work, you can also copy and paste the link in the browser, or enter the code on the screen you received after the order form.</p>
<p>Your code is: [customer_confirmCode]</p>
<p>Do you have any questions? Do not hesitate to contact us.</p>
<p>Kind regards,</p>
<p>[CLIENT_NAME]</p>
            ';
                    $oTemplate2->name            = 'account_new_password';
                    $oTemplate2->setEditable(1);
                    $oTemplate2->setDeletable(0);
                    if ($oTemplate2->isValid()) {
                        TemplateManager::saveTemplate($oTemplate2);
                    } else {
                        _d($oTemplate2->getInvalidProps());
                        die('Can\'t create template `account_new_password` for language `' . strtoupper($oLocale->getLanguage()->code) . '`');
                    }
                }
            }
        }
    }
}

if (!$oDb->tableExists('customers')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `customers`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `customers` (
          `customerId` int(11) NOT NULL AUTO_INCREMENT,
          `companyName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `companyAddress` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `companyPostalCode` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
          `companyCity` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `companyEmail` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
          `companyPhone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          `companyWebsite` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `gender` enum(\'U\',\'M\',\'F\') COLLATE utf8_unicode_ci NOT NULL DEFAULT \'U\',
          `firstName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `insertion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          `lastName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `houseNumber` int(11) NOT NULL,
          `houseNumberAddition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `postalCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `mobilePhone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `confirmCode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          `online` int(1) NOT NULL DEFAULT \'0\',
          `locked` timestamp NULL DEFAULT NULL,
          `lockedReason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `lastLogin` timestamp NULL DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`customerId`),
          UNIQUE KEY `u_customers_email` (`email`),
          UNIQUE KEY `u_customers_confirmCode` (`confirmCode`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
} else {
    if (!$oDb->columnExists('customers', 'deleted')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `customers`.`deleted`';
        //if ($bInstall) {
            $sQuery = "ALTER TABLE `customers` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `online`;";
            $oDb->query($sQuery, QRY_NORESULT);
        //}
    }
}

if (!$oDb->tableExists('customer_groups')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `customer_groups`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `customer_groups` (
          `customerGroupId` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`customerGroupId`),
          UNIQUE KEY `u_customers_name` (`name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('customer_group_relations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `customer_group_relations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `customer_group_relations` (
          `customerGroupId` int(11) NOT NULL,
          `customerId` int(11) NOT NULL,
          PRIMARY KEY (`customerGroupId`, customerId),
          UNIQUE KEY `customerGroupId_2` (`customerGroupId`,`customerId`),
          KEY `customerId` (`customerId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// check customer_group_relations constraints
if ($oDb->tableExists('customer_group_relations')) {
    if ($oDb->tableExists('customer_groups')) {
        // check customer_groups constraint
        if (!$oDb->constraintExists('customer_group_relations', 'customerGroupId', 'customer_groups', 'customerGroupId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `customer_group_relations`.`customerGroupId` => `customer_groups`.`customerGroupId`';
            if ($bInstall) {
                $oDb->addConstraint('customer_group_relations', 'customerGroupId', 'customer_groups', 'customerGroupId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('customers')) {
        // check customers constraint
        if (!$oDb->constraintExists('customer_group_relations', 'customerId', 'customers', 'customerId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `customer_group_relations`.`customerId` => `customers`.`customerId`';
            if ($bInstall) {
                $oDb->addConstraint('customer_group_relations', 'customerId', 'customers', 'customerId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

if ($oDb->tableExists('customer_groups') && moduleExists('customers')) {
    // add general customerGroup
    if (!($oCustomerGroupGeneral = CustomerGroupManager::getCustomerGroupByName('general'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing customerGroup `general`';
        if ($bInstall) {
            $oCustomerGroupGeneral        = new CustomerGroup();
            $oCustomerGroupGeneral->name  = 'general';
            $oCustomerGroupGeneral->title = 'Iedereen';
            if ($oCustomerGroupGeneral->isValid()) {
                CustomerGroupManager::saveCustomerGroup($oCustomerGroupGeneral);
            } else {
                _d($oCustomerGroupGeneral->getInvalidProps());
                die('Can\'t create customerGroup `general`');
            }
        }
    }
}


if (!$oDb->tableExists('locations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `locations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `locations` (
          `locationId` int(11) NOT NULL AUTO_INCREMENT,
          `customerId` int(11) NOT NULL,
          `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `notice` text NULL DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`locationId`),
          KEY `locations_customerid` (`customerId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('locations') && $oDb->tableExists('customers')) {

    if (!$oDb->columnExists('locations', 'deleted')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `locations`.`deleted`';
        //if ($bInstall) {
            $sQuery = "ALTER TABLE `locations` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `notice`;";
            $oDb->query($sQuery, QRY_NORESULT);
        //}
    }

    // check catalog_product_property_types constraint
    if (!$oDb->constraintExists('locations', 'customerId', 'customers', 'customerId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `locations`.`customerId` => `customers`.`customerId`';
        if ($bInstall) {
            $oDb->addConstraint('locations', 'customerId', 'customers', 'customerId', 'CASCADE', 'CASCADE');
        }
    }
}

if ($oDb->tableExists('customers')) {
    if (!$oDb->columnExists('customers', 'contactPersonPhone')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `customers`.`contactPersonPhone`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `customers` ADD `contactPersonPhone` VARCHAR(35) NULL DEFAULT NULL AFTER `contactPersonEmail`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
}

if ($oDb->tableExists('users_customers')) {
    if (!$oDb->columnExists('users_customers', 'uitbrInfo')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `users_customers`.`uitbrInfo`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `users_customers` ADD `uitbrInfo` TEXT NULL DEFAULT NULL AFTER `uitbreidingsmogelijkheden`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
}

if ($oDb->tableExists('users_customers')) {
    if (!$oDb->columnExists('users_customers', 'customer')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `users_customers`.`customer`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `users_customers` ADD `customer` TINYINT(1) NOT NULL DEFAULT '0' AFTER `finished`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
}

if ($oDb->tableExists('users_customers')) {
    if (!$oDb->columnExists('users_customers', 'orderNr')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing column `users_customers`.`orderNr`';
        if ($bInstall) {
            $sQuery = "ALTER TABLE `users_customers` ADD `orderNr` VARCHAR(200) NULL DEFAULT NULL AFTER `customerId`;";
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
}

if (!($oSetting = SettingManager::getSettingByName('infoEmail'))) {
    $aLogs[$sModuleName]['errors'][] = 'Missing setting `infoEmail`';
    if ($bInstall) {
        $oSetting        = new Setting();
        $oSetting->name  = 'infoEmail';
        $oSetting->value = '';
        if ($oSetting->isValid()) {
            SettingManager::saveSetting($oSetting);
        }
    }
}

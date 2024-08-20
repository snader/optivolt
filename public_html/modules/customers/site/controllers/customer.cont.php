<?php

/*
 * controller to handle the customer
 */


// make pageLayout Object
$oPageLayout = new PageLayout();

$sReferrer = http_session("frontendLoginReferrer", getCurrentUrlPath());

// set accountStatusUpdate
$sAccountStatusUpdate = http_session('accountStatusUpdate');
unset($_SESSION['accountStatusUpdate']); // only show once

// check if an email address exists
if (http_get('ajax') == 'checkEmail') {
    // return if the required fields are empty
    if (empty($_GET['email'])) {
        die(json_encode(null));
    }

    // return bool (true if email doesn't exists and vice versa)
    die(json_encode(!CustomerManager::emailExists(http_get('email'), http_get('customerId', null))));
}
// edit account
if (getCurrentUrlPath() == PageManager::getPageByName('account_edit')
        ->getUrlPath()) {
    // check if Customer is logged in
    if (empty(Customer::getCurrent()) || !is_numeric(Customer::getCurrent()->customerId)) {
        http_redirect(getBaseUrl() . '/' . http_get('controller'));
    }

    // get page by urlPath (/account/bewerken)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    // get the current Customer's info from the database
    $oCustomer = CustomerManager::getCustomerById(Customer::getCurrent()->customerId);

    // action = save
    if (http_post('action') == 'save' && CustomerCSRFSynchronizerToken::validate()) {

       
        $oCustomer->companyName         = http_post('companyName');
        
        $oCustomer->companyCity         = http_post('companyCity');
 

        // if object is valid, save
        if ($oCustomer->isValid()) {
            CustomerManager::saveCustomer($oCustomer); //save object
            CustomerManager::setCustomerInSession($oCustomer); // set Customer in session
            $_SESSION['accountStatusUpdate'] = _e(SiteTranslations::get('site_data_saved')); // set statusUpdate to session
            http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
        } else {
            $aCustomerErrors = [];
            if (!$oCustomer->isPropValid("email")) {
                $aCustomerErrors['email'] = _e(SiteTranslations::get('site_fill_in_your_email'));
            }
            if (!$oCustomer->isPropValid("emailExists")) {
                $aCustomerErrors['emailExists'] = _e(SiteTranslations::get('site_email_already_in_use'));
            }
            if (!$oCustomer->isPropValid("gender")) {
                $aCustomerErrors['gender'] = _e(SiteTranslations::get('site_select_gender'));
            }
            if (!$oCustomer->isPropValid("firstName")) {
                $aCustomerErrors['firstName'] = _e(SiteTranslations::get('site_enter_your_first_name'));
            }
            if (!$oCustomer->isPropValid("lastName")) {
                $aCustomerErrors['lastName'] = _e(SiteTranslations::get('site_enter_your_last_name'));
            }
            if (!$oCustomer->isPropValid("address")) {
                $aCustomerErrors['address'] = _e(SiteTranslations::get('site_enter_your_street_name'));
            }
            if (!$oCustomer->isPropValid("houseNumber")) {
                $aCustomerErrors['houseNumber'] = _e(SiteTranslations::get('site_enter_your_house_number'));
            }
            if (!$oCustomer->isPropValid("postalCode")) {
                $aCustomerErrors['postalCode'] = _e(SiteTranslations::get('site_enter_your_postal_code'));
            }
            if (!$oCustomer->isPropValid("city")) {
                $aCustomerErrors['city'] = _e(SiteTranslations::get('site_enter_your_city'));
            }

            Debug::logError("", "Frontend Customer module php validate error", __FILE__, __LINE__, "Tried to update Customer with wrong values despite javascript check.<br />" . _d($oCustomer, 1, 1), Debug::LOG_IN_EMAIL);
        }
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $oPageLayout->sViewPath = getSiteView('customer_edit', 'customers');
} // form to change the password (after receiving the forgot-password mail)
elseif (getCurrentUrlPath() == PageManager::getPageByName('account_forgot_password_edit')
        ->getUrlPath()) {
    // redirect to edit page if Customer is logged in
    if (!empty(Customer::getCurrent())) {
        http_redirect(PageManager::getPageByName('account_edit')
            ->getBaseUrlPath());
    }

    // get page by urlPath (/account/wachtwoord-vergeten/bewerken)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $sConfirmCode = http_post('code', http_get('code'));
    $sEmail       = http_post('email', http_get('email'));

    // action updatePassword
    if (http_post("action") == 'updatePassword' && CustomerCSRFSynchronizerToken::validate()) {
 

        if (!empty($sConfirmCode) && !empty($sEmail) && http_post('confirmPassword') && http_post('password') && http_post('password')==http_post('confirmPassword')) {
            // get the Customer
            $oCustomer = CustomerManager::getCustomerByConfirmCodeAndEmail($sConfirmCode, $sEmail);

            if ($oCustomer) {
                // define the password
                if (!empty($_POST['password']) && strlen(http_post('password')) >= 8) {
                    $oCustomer->password = hashPasswordForDb(http_post('password'));
                } else {
                    $oCustomer->password = null;
                }
            }

            if ($oCustomer && $oCustomer->isValid()) {
                CustomerManager::updatePasswordAndLogin($oCustomer); //update password and login
                CustomerManager::unlockCustomer($oCustomer, '');
                AccessLogManager::resetLoginAttempts($oCurrentAccessLog);
                http_redirect(PageManager::getPageByName('account_forgot_password_saved')
                    ->getBaseUrlPath());
            } elseif ($oCustomer) {
                $aErrorsChangePass = [];
                if (!$oCustomer->isPropValid('password')) {
                    $aErrors['password'] = _e(SiteTranslations::get('site_enter_safe_password_8_digits'));
                }
                Debug::logError("", "Frontend Customer module php validate error", __FILE__, __LINE__, "Tried to update a Customer's password with wrong values<br />" . _d($oCustomer, 1, 1), Debug::LOG_IN_EMAIL);
            } else {
                $aErrors                = [];
                $aErrors['combination'] = _e(SiteTranslations::get('site_code_email_incorrect'));
            }


        } else {
            $aErrors                = [];
            $aErrors['combination'] = _e(SiteTranslations::get('site_enter_email_code_new_password'));
        }
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $oPageLayout->sViewPath = getSiteView('customer_editPassword', 'customers');
} // email sent after forgot-password page
elseif (getCurrentUrlPath() == PageManager::getPageByName('account_forgot_password_saved')
        ->getUrlPath()) {
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $oPageLayout->sViewPath        = getSiteView('page_details', 'pages');
    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();
} // forgot password
elseif (getCurrentUrlPath() == PageManager::getPageByName('account_forgot_password')
        ->getUrlPath()) {
    // redirect to edit page if Customer is logged in
    if (!empty(Customer::getCurrent())) {
        http_redirect(PageManager::getPageByName('account_edit')
            ->getBaseUrlPath());
    }

    // get page by urlPath (/account/wachtwoord-vergeten)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    // action requestPassword
    if (http_post("action") == 'requestPassword' && CustomerCSRFSynchronizerToken::validate()) {
     

        if (http_post('email') && http_post('debnr') && ($oCustomer = CustomerManager::getCustomerByEmailDebNr(http_post('debnr'), http_post('email')))) {
            // create a unqiue confirmCode
            $oCustomer->confirmCode = substr(hash('sha512', uniqid(microtime() . $oCustomer->contactPersonEmail, 1)), 15, 30); 

            if ($oCustomer->isValid()) {
                CustomerManager::saveCustomer($oCustomer); //save object
                // mail Customer with confirm email
                $sTo = $oCustomer->contactPersonEmail;

                $oTemplate = TemplateManager::getTemplateByName('account_new_password', Locales::language());

                // check if template exists
                if (empty($oTemplate)) {
                    Debug::logError('', 'Template does not exists: `new password` (account_new_password)', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
                } else {
                    $oTemplate->replaceVariables($oCustomer);
                    $sSubject  = $oTemplate->getSubject();
                    $sMailBody = $oTemplate->getTemplate();
                    MailManager::sendMail($sTo, $sSubject, $sMailBody);

                }

                http_redirect(PageManager::getPageByName('account_forgot_password_edit')
                    ->getBaseUrlPath());
            } else {
                Debug::logError("", "Frontend Customer module php validate error", __FILE__, __LINE__, "Tried to reset Customer's password with wrong values<br />" . _d($oCustomer, 1, 1), Debug::LOG_IN_EMAIL);
            }
        } else {
            $aErrorsForgotPass          = [];
            $aErrorsForgotPass['email'] = _e(SiteTranslations::get('site_enter_valid_email'));
        }
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();


    $oPageLayout->sViewPath = getSiteView('customer_forgotPassword', 'customers');
} // confirm (activate) an account
elseif (getCurrentUrlPath() == PageManager::getPageByName('account_confirm')
        ->getUrlPath()) {

    // redirect to edit page if Customer is logged in
    if (!empty(Customer::getCurrent()) && is_numeric(Customer::getCurrent()->customerId)) {
        http_redirect(PageManager::getPageByName('account_edit')
            ->getBaseUrlPath());
    }

    // get page by urlPath (/account/bevestigen)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $sConfirmCode = http_post('code');
    $sEmail       = http_post('email');

    // confirm the account
    if (!empty($sConfirmCode) && !empty($sEmail) && CustomerManager::confirmCustomerByConfirmCodeAndEmail($sConfirmCode, $sEmail)) {
        unset($_SESSION['accountConfirmEmail']); // for no use after confirmation
        AccessLogManager::resetLoginAttempts($oCurrentAccessLog);
        CustomerManager::unlockCustomer($_SESSION['oCurrentCustomer'], '');

        // redirect to Thank you page
        http_redirect(PageManager::getPageByName('account_created')
            ->getBaseUrlPath());
    } elseif (!empty($sConfirmCode) && !empty($sEmail)) {
        $aErrorsActivate                       = [];
        $aErrorsActivate['combinationUnknown'] = _e(SiteTranslations::get('site_combination_email_code_incorrect'));
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $oPageLayout->sViewPath = getSiteView('customer_confirm', 'customers');
} // Thank you page after registering and confirming

 // logout
elseif (getCurrentUrlPath() == PageManager::getPageByName('account_logout')
        ->getUrlPath()) {

    CustomerManager::logout($sReferrer <> Request::getPath() ? $sReferrer : '/');
} // login and register form
else {
    // redirect to edit page if Customer is logged in
    if (Customer::getCurrent()) {
        http_redirect(PageManager::getPageByName('home')
            ->getBaseUrlPath());
    }

    // get page by urlPath (/account)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    // create Customer for registering
    $oCustomer = new Customer();

    $bLoginEnabled = $oCurrentAccessLog->loginEnabled();

    // login
    if (http_post("action") == 'login' && CustomerCSRFSynchronizerToken::validate()) {

        $bLoginCodeTemplate = false;
    
        if ( http_post('debnr') && http_post('password') && $oCustomer = CustomerManager::checkLoginSendCode(http_post('debnr'), http_post('password')) ) {
            
            $sTo = $oCustomer->contactPersonEmail;
            $sFrom = 'info@optivolt.nl';

            $oTemplate = TemplateManager::getTemplateByName('login_request', Locales::language());

            // check if template exists
            if (empty($oTemplate)) {
                Debug::logError('', 'Template does not exists: `login_request` (login_request)', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
            } else {

                $aReplace["[customer_logincode]"] = $oCustomer->confirmCode;
                $oTemplate->replaceVariables($oCustomer, $aReplace);            
                $sSubject  = $oTemplate->getSubject();
                $sMailBody = $oTemplate->getTemplate();
                MailManager::sendMail($sTo, $sSubject, $sMailBody, $sFrom);
                $bLoginCodeTemplate = true;

            }
    
            
            

        } elseif (http_post('logincode') && http_post('loginemail')) {

            $sConfirmCode = _e(http_post('logincode'));
            $sEmail = _e(http_post('loginemail'));          

            $oLoggedInCustomer = CustomerManager::getCustomerByContactEmail($sEmail);
           
            date_default_timezone_set('Europe/Amsterdam');
            $timeGiven = strtotime($oLoggedInCustomer->modified);
            // Haal de huidige tijd op als Unix-timestamp
            $timeNow = time();
            // Bereken het verschil in seconden
            $timeDifference = $timeNow - $timeGiven;
            // Converteer het verschil naar minuten
            $minutesPassed = floor($timeDifference / 60);           

            if ($minutesPassed<MINUTESLOGIN && CustomerManager::confirmCustomerByConfirmCodeAndEmail($sConfirmCode, $sEmail)) {

                AccessLogManager::resetLoginAttempts($oCurrentAccessLog);
                CustomerManager::unlockCustomer($oLoggedInCustomer, '');
    
                saveLog(
                    $sReferrer,
                    'klant login debiteur #' . http_post('debnr') . '(' . $oLoggedInCustomer->companyName . ')',
                    arrayToReadableText(object_to_array($oLoggedInCustomer))
                  );
    
                // redirect to referrer page
                http_redirect($sReferrer);
            } else {

                $aErrorsLogin['general'] = 'Verkeerde logincode of tijd verstreken';
                
            }
                
            
                
        } else {
            if (AccessLogManager::addLoginAttempt($oCurrentAccessLog, 'Username: `' . http_post('debnr') . '`')) {
                // IP is blocked or to many attempts, lock customer to block brute force attack
                CustomerManager::lockCustomerByEmail(http_post('debNr'), 'customer_to_many_failed_login_attempts');
                http_redirect(getCurrentUrl());
            }
            $bLoginAttemptsLeft = $oCurrentAccessLog->getLoginAttemptsLeft();
            $aErrorsLogin       = [];
            if (!http_post('debnr')) {
                $aErrorsLogin['signup-login-debnr'] = _e(SiteTranslations::get('site_fill_in_your_debnr'));
            } 
            if (!http_post('password')) {
                $aErrorsLogin['signup-login-password'] = _e(SiteTranslations::get('site_fill_in_your_password'));
            }
            if (!http_post('combination')) {
                $aErrorsLogin['general'] = _e(SiteTranslations::get('site_email_password_incorrect_after')) . ' ' . AccessLogManager::max_login_attempts_account_lock . ' ' . _e(
                        SiteTranslations::get('site_unsuccesful_attempts_your_account')
                    ) . ' ' . AccessLogManager::account_locked_time . ' ' . _e(SiteTranslations::get('site_minutes_blocked')) . '.<br /><b>' . $bLoginAttemptsLeft . ' ' . _e(SiteTranslations::get('site_attempts_left')) . '.</b>';
            }
        }
    }

    
    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $oPageLayout->sViewPath = getSiteView('customer_signUp', 'customers');
    $bSigninPage = true;
}

# Include the template
if (isset($bSigninPage)) {

    if (isset($bLoginCodeTemplate) && $bLoginCodeTemplate) {
       include_once getSiteView('login-code');
    } else {
        include_once getSiteView('login');
    }
} else {
    include_once getSiteView('layout');
}

?>

<section class="section">
    <!-- Navigation Breadcrumbs -->
    <?php include getSiteSnippet('navigationBreadcrumbs'); ?>
    <!-- /Navigation Breadcrumbs -->
    <div class="container">
        <div class="columns">
            <div class="column">
                <div class="content">
                    <h1 class="title is-size-1"><?= _e($oPage->title) ?></h1>
                    <?= $oPage->intro ?>
                    <?= $oPage->content ?>
                </div>
            </div>
        </div>
        <div class="columns is-desktop">
            <div class="column is-one-third-desktop">
                <h2 class="title is-size-2"><?= _e(SiteTranslations::get('site_already_customer')) ?></h2>
                <?php if ($bLoginEnabled) { ?>
                    <div class="box">
                        <form method="POST" class="validateFormInline">
                            <?= CustomerCSRFSynchronizerToken::field() ?>
                            <input type="hidden" name="action" value="login">
                            <?php
                            // hack for multiple errorBoxes on one page
                            $aErrors = [];
                            if (!empty($aErrorsLogin)) {
                                $aErrors = $aErrorsLogin;
                            }
                            include getSiteSnippet('errorBox');
                            ?>

                            <div class="field">
                                <label class="label" for="signup-login-debnr"><?= _e(SiteTranslations::get('site_debnr')) ?> *</label>
                                <div class="control has-icons-right">
                                    <input placeholder="<?= _e(SiteTranslations::get('site_debnr')) ?> *" class="input" autocomplete="off"
                                           data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_debnr')) ?>"
                                           
                                           id="signup-login-debnr" name="debnr" type="text" value="<?= _e(http_post('debnr')) ?>"/>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label" for="signup-login-password"><?= _e(SiteTranslations::get('site_password')) ?> *</label>
                                <div class="control has-icons-right">
                                    <input placeholder="<?= _e(SiteTranslations::get('site_password')) ?> *" class="input" autocomplete="off"
                                           data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_password')) ?>"
                                           id="signup-login-password" name="password" type="password" value="<?= _e(http_post('password')) ?>"/>
                                </div>
                            </div>
                            <button class="button is-primary" name="login"><?= _e(SiteTranslations::get('site_login')) ?></button>
                        </form>
                    </div>
                    <a href="<?= PageManager::getPageByName('account_forgot_password')->getBaseUrlPath() ?>" class="is-size-7"><?= _e(SiteTranslations::get('site_forgot_your_password')) ?></a>
                <?php } else { ?>
                    <div class="form-errors" style="display:block;">
                        <div class="title"><?= _e(SiteTranslations::get('site_account_temporarily_blocked')) ?></div>
                        <p>
                            U heeft <?= AccessLogManager::max_login_attempts_account_lock ?> keer verkeerd geprobeerd in te loggen. Uw gebruikersaccount is nu voor <?= AccessLogManager::account_locked_time ?> minuten geblokkeerd.
                        </p>
                    </div>
                <?php } ?>

            </div>
            <div class="column is-two-thirds-desktop">
                <h2 class="title is-size-2"><?= _e(SiteTranslations::get('site_want_to_become_a_customer')) ?></h2>
                <div class="box">
                    <form method="POST" action="<?= getBaseUrl() . getCurrentUrlPath() ?>" class="validateFormInline">
                        <?= CustomerCSRFSynchronizerToken::field() ?>
                        <input type="hidden" value="register" name="action"/>
                        <?php

                        // hack for multiple errorBoxes on one page
                        $aErrors = [];
                        if (!empty($aErrorsSignup)) {
                            $aErrors = $aErrorsSignup;
                        }
                        include getSiteSnippet('errorBox');
                        ?>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width"><?= _e(SiteTranslations::get('site_gender')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input class="radio" <?= _e($oCustomer->gender) == 'M' ? 'CHECKED' : '' ?> data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_select_gender')) ?>" id="signup-gender_M" type="radio" name="gender" value="M"/> <label for="signup-gender_M"><?= _e(SiteTranslations::get('site_male')) ?></label><br/>
                                        <input class="radio" <?= _e($oCustomer->gender) == 'F' ? 'CHECKED' : '' ?> data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_select_gender')) ?>" id="signup-gender_F" type="radio" name="gender" value="F"/> <label for="signup-gender_F"><?= _e(SiteTranslations::get('site_female')) ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-firstName"><?= _e(SiteTranslations::get('site_first_name')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_first_name')) ?> *" class="input full-width"
                                               data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_enter_your_first_name')) ?>" id="signup-firstName" type="text" name="firstName" value="<?= _e($oCustomer->firstName) ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-insertion"><?= _e(SiteTranslations::get('site_insertion')) ?></label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= SiteTranslations::get('site_enter_your_insertion') ?>" class="input full-width" id="signup-insertion" type="text" name="insertion" value="<?= _e($oCustomer->insertion) ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-lastName"><?= _e(SiteTranslations::get('site_last_name')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_last_name')) ?> *" class="input full-width" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_enter_your_last_name')) ?>" id="signup-lastName" type="text" name="lastName" value="<?= _e($oCustomer->lastName) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label" for="signup-email"><?= _e(SiteTranslations::get('site_email')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_email')) ?> *" class="input" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_email')) ?>" data-rule-email="true" data-msg-email="<?= _e(SiteTranslations::get('site_email_not_valid')) ?>" data-rule-remote="<?= getBaseUrl() ?>/<?= http_get('controller') ?>?ajax=checkEmail" data-msg-remote="<?= _e(SiteTranslations::get('site_email_already_in_use')) ?>" id="signup-email" name="email" type="email" value="<?= _e($oCustomer->email) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-password"><?= _e(SiteTranslations::get('site_desired_password')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_desired_password')) ?>*" class="input full-width" autocomplete="off" data-rule-required="true"
                                               data-msg-required="<?= _e(SiteTranslations::get('site_fill_in_your_password')) ?>" data-rule-minlength="8" data-msg-minlength="<?= _e(SiteTranslations::get('site_enter_safe_password_8_digits')) ?>" id="signup-password" type="password" name="password" value=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-confirmPassword"><?= _e(SiteTranslations::get('site_password_again')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_password_again')) ?> *" class="input full-width" utocomplete="off" data-rule-required="true"
                                               data-msg-required="<?= _e(SiteTranslations::get('site_you_did_not_confirm_your_password')) ?>" data-rule-equalTo="#signup-password"
                                               data-msg-equalTo="<?= _e(SiteTranslations::get('site_passwords_do_not_match')) ?>" id="signup-confirmPassword" name="confirmPassword" type="password" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-phone"><?= _e(SiteTranslations::get('site_phone')) ?></label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_phone')) ?>" class="input full-width" id="signup-phone" type="tel" name="phone" value="<?= _e($oCustomer->phone) ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-mobilePhone"><?= _e(SiteTranslations::get('site_mobile_phone')) ?></label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_mobile_phone')) ?>" class="input full-width" id="signup-mobilePhone" type="tel" name="mobilePhone" value="<?= _e($oCustomer->mobilePhone) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-companyName"><?= _e(SiteTranslations::get('site_company')) ?></label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_company')) ?>" class="input full-width" id="signup-companyName" name="companyName" type="text" value="<?= _e($oCustomer->companyName) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-address"><?= _e(SiteTranslations::get('site_street')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_street')) ?> *" class="input full-width" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_enter_your_street_name')) ?>" id="signup-address" type="text" name="address" value="<?= _e($oCustomer->address) ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-houseNumber"><?= _e(SiteTranslations::get('site_house_number')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_house_number')) ?> *" class="input full-width" data-rule-required="true"
                                               data-msg-required="<?= _e(SiteTranslations::get('site_enter_your_house_number')) ?>" data-rule-digits="true" data-msg-digits="<?= _e(SiteTranslations::get('site_enter_valid_house_number')) ?>" id="signup-houseNumber" type="text" name="houseNumber" value="<?= _e($oCustomer->houseNumber) ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-houseNumberAddition"><?= _e(SiteTranslations::get('site_addition')) ?></label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= SiteTranslations::get('site_enter_your_housenumber_addition') ?>" class="input full-width" id="signup-houseNumberAddition" type="text" name="houseNumberAddition" value="<?= _e($oCustomer->insertion) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-postalCode"><?= _e(SiteTranslations::get('site_postal_code')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_postal_code')) ?> *" class="input full-width" data-rule-required="true"
                                               data-msg-required="<?= _e(SiteTranslations::get('site_enter_your_postal_code')) ?>" id="signup-postalCode" type="text" name="postalCode" value="<?= _e($oCustomer->postalCode) ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="column">
                                <div class="field">
                                    <label class="label full-width" for="signup-city"><?= _e(SiteTranslations::get('site_city')) ?> *</label>
                                    <div class="control has-icons-right">
                                        <input placeholder="<?= _e(SiteTranslations::get('site_city')) ?> *" class="input full-width" data-rule-required="true" data-msg-required="<?= _e(SiteTranslations::get('site_enter_your_city')) ?>" id="signup-city" type="text" name="city" value="<?= _e($oCustomer->city) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="button is-primary" type="submit" name="register"><?= _e(SiteTranslations::get('site_register')) ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <h1 class="m-0"><i aria-hidden="true" class="fas fa-shapes fa-cogs"></i>&nbsp;&nbsp;Settings</h1></i>
            </div>
        </div>
    </div>
</div>
<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm" enctype="multipart/form-data">
    <input type="hidden" value="save" name="action" />
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-id-card pr-1"></i> <?= sysTranslations::get('settings_contact') ?></h3>
                        </div>
                        <div class="card-body">
                            <?php //if (moduleExists('pages')) {
                            ?>

                            <?php if (Settings::exists('clientName')) { ?>
                                <div class="form-group">
                                    <label for="clientName"><?= sysTranslations::get('global_name') ?> *</label>
                                    <input type="text" name="settings[clientName]" class="form-control" id="clientName" value="<?= _e(Settings::get('clientName')) ?>" title="<?= sysTranslations::get('settings_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('settings_name_tooltip') ?>">
                                </div>
                            <?php } ?>
                            <?php if (Settings::exists('clientStreet')) { ?>
                                <div class="form-group">
                                    <label for="clientStreet"><?= sysTranslations::get('global_street') ?> *</label>
                                    <input type="text" name="settings[clientStreet]" class="form-control" id="clientStreet" value="<?= _e(Settings::get('clientStreet')) ?>" title="<?= sysTranslations::get('settings_street_tooltip') ?>" required data-msg="<?= sysTranslations::get('settings_name_tooltip') ?>">
                                </div>
                            <?php } ?>
                            <?php if (Settings::exists('clientPostalCode')) { ?>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="clientPostalCode"><?= sysTranslations::get('global_postal_code') ?> *</label>
                                            <input type="text" name="settings[clientPostalCode]" class="form-control" id="clientPostalCode" value="<?= _e(Settings::get('clientPostalCode')) ?>" title="<?= sysTranslations::get('settings_postal_code_tooltip') ?>" maxlength="6">
                                        </div>
                                        <div class="col-8">
                                            <label for="clientCity"><?= sysTranslations::get('global_city') ?> *</label>
                                            <input type="text" name="settings[clientCity]" class="form-control" id="clientCity" value="<?= _e(Settings::get('clientCity')) ?>" title="<?= sysTranslations::get('settings_city_tooltip') ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (Settings::exists('clientEmail')) { ?>
                                <div class="form-group">
                                    <label for="clientEmail"><?= sysTranslations::get('global_email') ?> *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" name="settings[clientEmail]" class="form-control" id="clientEmail" value="<?= _e(Settings::get('clientEmail')) ?>" title="<?= sysTranslations::get('settings_email_tooltip') ?>" required data-msg="<?= sysTranslations::get('settings_email_tooltip') ?>">
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (Settings::exists('contact_email_to')) { ?>
                                <div class="form-group">
                                    <label for="contact_email_to"><?= sysTranslations::get('contact_email_to') ?> *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" name="settings[contact_email_to]" class="form-control" id="contact_email_to" value="<?= _e(Settings::get('contact_email_to')) ?>" title="<?= sysTranslations::get('settings_contact_email_to_tooltip') ?>: <?= CLIENT_DEFAULT_EMAIL_TO ?>">
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (Settings::exists('clientPhone')) { ?>
                                <div class="form-group">
                                    <label for="clientPhone"><?= sysTranslations::get('global_phone') ?> *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" name="settings[clientPhone]" class="form-control" id="clientPhone" value="<?= _e(Settings::get('clientPhone')) ?>" title="<?= sysTranslations::get('settings_phone_tooltip') ?>">
                                    </div>
                                </div>
                            <?php } ?>


                            <?php //}
                            ?>
                        </div>
                    </div>

                    <?php if (moduleExists('core')) { ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= sysTranslations::get('global_general_settings') ?></h3>
                            </div>
                            <div class="card-body">

                                <?php if (Settings::exists('defaultCountryId')) { ?>
                                    <div class="form-group">
                                        <label for="defaultCountryId"><?= sysTranslations::get('settings_defaultCountryId') ?> *</label>
                                        <select class="form-control" id="defaultCountryId" name="settings[defaultCountryId]">
                                            <option value=""><?= sysTranslations::get('global_no_default_country') ?></option>
                                            <?php
                                            foreach (CountryManager::getCountriesByFilter() as $oCountry) {
                                                echo '<option ' . ($oCountry->countryId == Settings::get('defaultCountryId') ? 'selected' : '') . ' value="' . $oCountry->countryId . '">' . _e($oCountry->getTranslations()->name) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <?php if (Settings::exists('infoEmail')) { ?>
                                    <div class="form-group">
                                        <label for="infoEmail">E-mailadres tbv 'info vanuit OMS'</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="email" name="settings[infoEmail]" class="form-control" id="infoEmail" value="<?= _e(Settings::get('infoEmail')) ?>" title="<?= sysTranslations::get('settings_email_tooltip') ?>" required data-msg="<?= sysTranslations::get('settings_email_tooltip') ?>">
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php } ?>

                </div>

                <!-- admin settings -->
                <?php if ($oCurrentUser->isAdmin()) { ?>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user-shield pr-1"></i> <?= sysTranslations::get('settings_admin') ?></h3>
                            </div>
                            <div class="card-body">

                                <?php if (moduleExists('pages')) { ?>
                                    <?php if (Settings::exists('pagesMaxLevels')) { ?>
                                        <div class="form-group">
                                            <label for="pagesMaxLevels"><?= sysTranslations::get('settings_page_levels') ?> *</label>
                                            <input type="number" name="settings[pagesMaxLevels]" class="form-control digits" id="pagesMaxLevels" value="<?= _e(Settings::get('pagesMaxLevels')) ?>" required>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (Settings::exists('2StepForced')) { ?>

                                    <div class="form-group">
                                        <div class="row border-bottom mb-2 pb-1">
                                            <div class="col-md-6">
                                                <label for="administrator"><?= sysTranslations::get('2_step_forced') ?></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="checkbox" id="2StepForced" name="settings[2StepForced]" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" <?= Settings::get('2StepForced') ? 'CHECKED ' : '' ?>>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                                <?php if (Settings::exists('2StepWhitelistIps')) { ?>

                                    <div class="form-group">
                                        <label for="pagesMaxLevels"><?= sysTranslations::get('2_step_whitelist_ips') ?></label>
                                        <input type="text" name="settings[2StepWhitelistIps]" class="form-control" id="2StepWhitelistIps" value="<?= _e(Settings::get('2StepWhitelistIps')) ?>" title="<?= sysTranslations::get('2_step_whitelist_ips_title') ?>">
                                    </div>

                                <?php } ?>

                            </div>
                        </div>
                    </div>


                <?php } ?>

                <div class="col-12">

                    <span class="float-sm-right">
                        <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                                <?= sysTranslations::get('global_back') ?>
                            </button>
                        </a>
                    </span>
                    <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />

                </div>



            </div>
        </div>
    </section>
</form>
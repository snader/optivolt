<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">

                <?php
                if (!UserManager::getCurrentUser()->isClientAdmin()) {
                ?>
                    <span class="float-right">
                        <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>">
                            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('dashboard_menu') ?>">
                                <?= sysTranslations::get('dashboard_menu') ?>
                            </button>
                        </a>
                    </span>
                <?php } ?>

                <span class="float-right">
                    <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>/klanten">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer_overview') ?>">
                            <?= sysTranslations::get('to_customer_overview') ?>
                        </button>
                    </a>
                </span>

                <span class="float-right">
                    <a class="backBtn right pl-1" href="<?= str_replace('bewerken', 'systems', getCurrentUrl()) ?>">
                        <button type="button" class="btn btn-default btn-sm" title="Systemen overzicht">
                            Naar systemen overzicht
                        </button>
                    </a>
                </span>

                <?php
                if (!empty($oCustomer->companyName)) {
                    $sTitle = _e($oCustomer->companyName);
                } else {

                    $sTitle = sysTranslations::get('systems_customer') . ', ' . sysTranslations::get('customer_locations') . ' & afspraken';
                }
                ?>
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;<?= $sTitle ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-<?= $oCustomer->customerId ? '4' : '6' ?>">
            <form method="POST" action="" class="validateForm" id="quickForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action" />
                <input type="hidden" value="<?= http_get("pl") && substr_count(http_get("pl"), '_') == 1 ? _e(http_get("pl")) : '' ?>" name="pl">
                <div class="card">
                    <div class="card-header">

                        <?php if ($oCustomer->customerId) { ?>
                            <span class="float-right d-md-none pl-1">
                                <a class="backBtn right" href="#locations-table">
                                    <button type="button" class="btn btn-default btn-sm" title="">
                                        &#8744 <?= sysTranslations::get('customer_locations') ?>
                                    </button>
                                </a>
                            </span>
                            <?php if (isset($aAppointment) && !empty($aAppointment)) { ?>
                                <span class="float-right d-md-none pl-1">
                                    <a class="backBtn right" href="#signature-pad">
                                        <button type="button" class="btn btn-default btn-sm" title="">
                                            &#8744 Handtekening
                                        </button>
                                    </a>
                                </span>
                            <?php } ?>
                            <span class="float-right pl-1">
                                <a class="backBtn right" href="<?= str_replace('bewerken', 'systems', getCurrentUrl()) ?>">
                                    <button type="button" class="btn btn-default btn-sm" title="Systemen overzicht">
                                        > Systemen
                                    </button>
                                </a>
                            </span>
                        <?php } ?>
                        <h3 class="card-title"><i class="fas fa-building pr-1"></i> <?= sysTranslations::get('customer_client') ?></h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">

                        <?php
                        if ($oCustomer->isOnlineChangeable()) {
                        ?>
                            <div class="form-group">
                                <div class="row border-bottom mb-2 pb-1">
                                    <div class="col-md-4">
                                        <label for="online"><?= sysTranslations::get('global_activated') ?></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="checkbox" title="<?= sysTranslations::get('customer_set_online') ?>" id="online" name="online" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>" <?= $oCustomer->online ? 'CHECKED' : '' ?>>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" value="<?= $oCustomer->online ?>" name="online" />
                        <?php } ?>

                        <div class="form-group">
                            <label for="name">Debiteuren# *</label>
                            <input type="text" name="debNr" class="form-control" id="debNr" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->debNr ?>" title="Debiteurennummer" required data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                            <span class="error invalid-feedback show"><?= $oCustomer->isPropValid("debNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>

                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('global_new_password') ?> **</label>
                            <input id="password" class="<?= $oCustomer->customerId === null ? 'required pasword' : 'password' ?> form-control" title="<?= sysTranslations::get('user_secure_password_tooltip') ?>" autocomplete="off" type="text"
                                   name="password" value=""/>
                            <span class="error invalid-feedback show"><em>&nbsp;(** <?= sysTranslations::get('user_password_empty') ?>)</em> <span class="error"><?= $oCustomer->isPropValid("password") ? '' : sysTranslations::get('global_field_not_completed') ?></span></span>
                        </div>


                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('global_company_name') ?> *</label>
                            <input type="text" name="companyName" class="form-control" id="companyName" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->companyName ?>" title="<?= sysTranslations::get('global_company_name') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                            <span class="error invalid-feedback show"><?= $oCustomer->isPropValid("companyName") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>
                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('global_address') ?> *</label>
                            <input type="text" name="companyAddress" class="form-control" id="companyAddress" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->companyAddress ?>" title="<?= sysTranslations::get('customer_company_address') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                            <span class="error invalid-feedback show"><?= $oCustomer->isPropValid("companyAddress") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4"><label for="companyPostalCode"><?= sysTranslations::get('global_postal_code') ?> *</label></div>
                                <div class="col-lg-8"><label for="companyCity"><?= sysTranslations::get('global_city') ?> *</label></div>
                            </div>
                            

                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" name="companyPostalCode" class="form-control" id="companyPostalCode" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->companyPostalCode ?>" title="<?= sysTranslations::get('customer_company_postal_code') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" name="companyCity" class="form-control" id="companyCity" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->companyCity ?>" title="<?= sysTranslations::get('customer_company_city') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                </div>
                            </div>


                            <span class="error invalid-feedback show"><?= ($oCustomer->isPropValid("companyPostalCode") || $oCustomer->isPropValid("companyCity")) ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>





                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('global_phone') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" name="companyPhone" class="form-control" id="companyPhone" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->companyPhone ?>" title="<?= sysTranslations::get('customer_company_phone') ?>" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"><?= $oCustomer->isPropValid("companyPhone") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="clientEmail"><?= sysTranslations::get('global_email') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="companyEmail" class="form-control" id="companyEmail" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> data-msg-email="<?= sysTranslations::get('global_valid_email_tooltip') ?>" data-msg-required="<?= sysTranslations::get('global_email_required_tooltip') ?>" title="<?= sysTranslations::get('global_enter_email_tooltip') ?>" value="<?= _e($oCustomer->companyEmail) ?>" />
                                <?php
                                $sEmailPropError = '';
                                if (!$oCustomer->isPropValid("email")) {
                                    $sEmailPropError .= ($sEmailPropError == '' ? '' : ', ') . sysTranslations::get('global_field_not_completed');
                                }
                                ?>
                                <span class="error invalid-feedback show"><?= $sEmailPropError ?></span>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('contact_person_name') ?> contactpersoon</label>
                            <input type="text" name="contactPersonName" class="form-control" id="contactPersonName" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->contactPersonName ?>" title="<?= sysTranslations::get('contact_person_name') ?>" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                            <span class="error invalid-feedback show"><?= $oCustomer->isPropValid("contact_person_name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>


                        <div class="form-group">
                            <label for="contactPersonEmail"><?= sysTranslations::get('global_email') ?> contactpersoon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="contactPersonEmail" class="form-control" id="contactPersonEmail" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> data-msg-email="<?= sysTranslations::get('global_valid_email_tooltip') ?>" title="<?= sysTranslations::get('global_enter_email_tooltip') ?> contactpersoon" value="<?= _e($oCustomer->contactPersonEmail) ?>" />
                                <?php
                                $sEmailPropError = '';
                                if (!$oCustomer->isPropValid("contactPersonEmail")) {
                                    $sEmailPropError .= ($sEmailPropError == '' ? '' : ', ') . sysTranslations::get('global_field_not_completed');
                                }
                                ?>
                                <span class="error invalid-feedback show"><?= $sEmailPropError ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('global_phone') ?> contactpersoon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" name="contactPersonPhone" class="form-control" id="contactPersonPhone" <?= (!$oCustomer->isEditable() ? 'readonly disabled ' : '') ?> value="<?= $oCustomer->contactPersonPhone ?>" title="<?= sysTranslations::get('customer_company_phone') ?> contactpersoon" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"><?= $oCustomer->isPropValid("contactPersonPhone") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <span class="float-right">
                            <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                                <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                                    <?= sysTranslations::get('to_customer_overview') ?>
                                </button>
                            </a>
                        </span>
                        <?php if ($oCustomer->isEditable()) { ?>
                            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
                        <?php } ?>
                    </div>

                </div>


            </form>
        </div>
        <div class="col-md-8">

            <?php
            if ($oCustomer->customerId) { ?>
                <a name="locations-table" id="locations-table"></a>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-thumbtack pr-1"></i> <?= sysTranslations::get('customer_locations') ?></h3>
                        <div class="card-tools">
                            <?php
                            if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
                            ?>
                                <div class="input-group input-group-sm" style="width: auto;">
                                    <div class="input-group-append">
                                        <a class="addBtn" href="<?= ADMIN_FOLDER ?>/locaties/toevoegen?customerId=<?= $oCustomer->customerId ?>" title="<?= sysTranslations::get('add_item') ?>">
                                            <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </a>&nbsp;
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">&nbsp;</th>
                                    <th><?= sysTranslations::get('customer_location') ?></th>
                                    <th style="text-align:center;"><?= sysTranslations::get('systems_count') ?></th>
                                    <th style="text-align:center;" title="Onderhoud <?= !empty($aAppointment) ? date('Y', strtotime($aAppointment['visitDate'])) : date('Y', time()) ?>"><i class="fas fa-wrench"></i> <?= !empty($aAppointment) ? date('Y', strtotime($aAppointment['visitDate'])) : date('Y', time()) ?></th>
                                    <th style="width: 10px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $aLocations = $oCustomer->getLocations();
                                $iMissedSystems = 0;
                                foreach ($oCustomer->getLocations() as $oLocation) {
                                    $iSystems = $oLocation->countSystems();

                                    $iVisitdate = (!empty($aAppointment) ? date('Y', strtotime($aAppointment['visitDate'])) : date('Y', time()));
                                    $iSystemsFinished = $oLocation->countSystems($iVisitdate);
                                    if ($iSystems - $iSystemsFinished > 0) {
                                        $iMissedSystems++;
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/locaties/bewerken/' . $oLocation->locationId ?>" title="<?= sysTranslations::get('location_edit') ?>">
                                                <i class="fas fa-x fa-<?= $oLocation->isEditable() ? 'pencil-alt' : 'search-location' ?>"></i>
                                            </a>
                                        </td>
                                        <td><?= $oLocation->name ?></td>
                                        <td style="text-align:center;"><?= $iSystems ?></td>
                                        <td <?= ($iSystems - $iSystemsFinished > 0) ? 'class="bg-danger color-palette"' : '' ?>style="text-align:center;"><?= $iSystemsFinished ?></td>

                                        <?php
                                        if ($oLocation->isDeletable()) {
                                        ?>
                                            <td><a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/locaties/verwijderen/' . $oLocation->locationId ?>" title="Verwijder locatie (en alle onderliggende data)" onclick="return confirmChoice('<?= _e($oCustomer->companyName . ' - ' . $oLocation->name) ?>');">
                                                    <i class="fas fa-trash"></i>
                                                </a></td>
                                        <?php } ?>


                                    </tr>
                                <?php
                                }
                                if (empty($aLocations)) {
                                    echo '<tr><td colspan="6"><i>' . sysTranslations::get('customer_no_locations') . '</i></td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                if ((http_get('param3') == 'afspraak-bewerken' && is_numeric(http_get('param4'))) || http_get('param3') == 'afspraak-toevoegen') {

                    require getAdminSnippet('adminAppointmentForm', 'customers');
                }

                // appointment detail
                if (isset($aAppointment) && !empty($aAppointment)) {
                ?>
                    <form method="POST" action="">
                        <?= CSRFSynchronizerToken::field() ?>
                        <input type="hidden" name="saveAppointment" value="save">
                        <input type="hidden" name="userId" value="<?= $aAppointment['userId'] ?>">
                        <input type="hidden" name="visitDate" value="<?= $aAppointment['visitDate'] ?>">
                        <div class="card <?= UserManager::getCurrentUser()->isClientAdmin() && $aAppointment['finished'] ? 'collapsed-card' : '' ?>">
                            <div class="card-header">
                            
                                <h3 class="card-title"><i class="fas fa-file-signature pr-1"></i> Opmerkingen <?= !empty($aAppointment) ? date('Y', strtotime($aAppointment['visitDate'])) : date('Y', time()) ?></h3>
                                <?php
                                if (UserManager::getCurrentUser()->isClientAdmin() && $oCustomer->customerId && http_get('param4') != '') {
                                ?>
                                    <span class="float-right">

                                        <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>">
                                            <button type="button" class="btn btn-default btn-sm" title="Afspraken overzicht">
                                                Afspraken overzicht
                                            </button>
                                        </a>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-<?= UserManager::getCurrentUser()->isClientAdmin() && $aAppointment['finished'] ? 'plus' : 'minus' ?>"></i>
                                        </button>
                                    </span>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="uitbreidingsmogelijkheden">Zijn er uitbreidingsmogelijkheden?</label>
                                    <select class="form-control" id="uitbreidingsmogelijkheden" name="uitbreidingsmogelijkheden" title="Selecteer een antwoord">
                                        <option value="">- Selecteer</option>
                                        <option <?= $aAppointment['uitbreidingsmogelijkheden'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                        <option <?= $aAppointment['uitbreidingsmogelijkheden'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                    </select>
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="form-group" id="uitbrInfoDiv" <?= $aAppointment['uitbreidingsmogelijkheden'] == 'Ja' ? '' : 'style="display:none;"' ?>>
                                    <label for="uitbrInfo">Opmerkingen</label>
                                    <textarea class="form-control" rows="3" name="uitbrInfo" id="uitbrInfo"><?= _e($aAppointment['uitbrInfo']) ?></textarea>
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="vLiner">Voor V-Liner?</label>
                                            <select class="form-control" id="vLiner" name="vLiner" title="Selecteer een antwoord">
                                                <option value="">- Selecteer</option>
                                                <option <?= $aAppointment['vLiner'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                                <option <?= $aAppointment['vLiner'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                            </select>
                                            <span class="error invalid-feedback show"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="ml">Voor ML?</label>
                                            <select class="form-control" id="ml" name="ml" title="Selecteer een antwoord">
                                                <option value="">- Selecteer</option>
                                                <option <?= $aAppointment['ml'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                                <option <?= $aAppointment['ml'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                            </select>
                                            <span class="error invalid-feedback show"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="koperenRailen">Originele koperen railen aanwezig?</label>
                                    <select class="form-control" id="koperenRailen" name="koperenRailen" title="Selecteer een antwoord">
                                        <option value="">- Selecteer</option>
                                        <option <?= $aAppointment['koperenRailen'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                        <option <?= $aAppointment['koperenRailen'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                        <option <?= $aAppointment['koperenRailen'] == 'NVT' ? 'selected ' : '' ?>value="NVT">NVT</option>
                                    </select>
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="form-group">
                                    <label for="PQkast">PQ Kast afgetekend?</label>
                                    <select class="form-control" id="PQkast" name="PQkast" title="Selecteer een antwoord">
                                        <option value="">- Selecteer</option>
                                        <option <?= $aAppointment['PQkast'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                        <option <?= $aAppointment['PQkast'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                    </select>
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="form-group">
                                    <label for="onderhoudssticker">Onderhoudssticker aanwezig?</label>
                                    <select class="form-control" id="onderhoudssticker" name="onderhoudssticker" title="Selecteer een antwoord">
                                        <option value="">- Selecteer</option>
                                        <option <?= $aAppointment['onderhoudssticker'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                        <option <?= $aAppointment['onderhoudssticker'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                    </select>
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="form-group">
                                    <label for="hoofdschakelaarTerug">Hoofdschakelaar teruggeschroefd?</label>
                                    <select class="form-control" id="hoofdschakelaarTerug" name="hoofdschakelaarTerug" title="Selecteer een antwoord">
                                        <option value="">- Selecteer</option>
                                        <option <?= $aAppointment['hoofdschakelaarTerug'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                                        <option <?= $aAppointment['hoofdschakelaarTerug'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                                        <option <?= $aAppointment['hoofdschakelaarTerug'] == 'NVT' ? 'selected ' : '' ?>value="NVT">NVT</option>
                                    </select>
                                    <span class="error invalid-feedback show"></span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
                            </div>
                        </div>
                    </form>

                    <div id="finishcard" class="card finish-card <?= ($iMissedSystems > 0) ? 'card-danger' : 'card-success' ?>">
                        <div class="card-header">
                            <a name="signature-pad"></a>
                            <h3 class="card-title"><i class="fas fa-flag-checkered pr-1"></i>
                                <?php
                                if ($aAppointment['finished']) {
                                    echo sysTranslations::get('report_finished');
                                    if ($aAppointment['mailed']) {
                                        echo ' & verzonden';
                                    }
                                ?>
                                
                                
                                
                                <?php    
                                } else { ?>
                                    <?= sysTranslations::get('report_finishing') ?>
                                <?php }

                                ?></h3>
                                <?php
                                if ($aAppointment['finished']) {?>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: auto;">
                                        <div class="input-group-append">
                                            <a class="addBtn" id="undo-signature" href="<?= getCurrentUrl() . '?undo-signature=' . $aAppointment['signature'] ?>" title="Verwijder handtekening">
                                                <button type="button" class="btn btn-danger btn-sm" style="min-width:32px;">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </a>&nbsp;
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                        </div>
                        
                        
                        <div class="card-body">
                            <div class="p-1">

                                <?php
                                if ($aAppointment['finished']) {
                                    echo 'Voltooid op ' . date('d-m-Y', strtotime($aAppointment['modified'])) . ' door ' . UserManager::getUserById($aAppointment['userId'])->getDisplayName() . '<br/>';
                                    if (!empty($aAppointment['signatureName'])) {
                                        echo 'Naam aftekening: ' . $aAppointment['signatureName'] . '<br/>';
                                    }
                                    echo 'Handtekening voor gezien:<br/>';

                                ?>
                                    <img src='<?= ADMIN_FOLDER ?>/klanten/sigs/<?= $aAppointment['signature'] ?>' height='300'><br />

                                <?php

                                } else {
                                ?>
                                    <form action="<?= ADMIN_FOLDER ?>/klanten/sig" method="POST" id="saveSignatureForm">
                                        <?= CSRFSynchronizerToken::field() ?>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="finish" value="1">
                                                <input type="hidden" name="userId" id="userId" value="<?= $aAppointment['userId'] ?>">
                                                <input type="hidden" name="customerId" id="customerId" value="<?= $oCustomer->customerId ?>">
                                                <input type="hidden" name="visitDate" id="visitDate" value="<?= $aAppointment['visitDate'] ?>">
                                                <textarea id="signature64" name="signature" style="display: none"></textarea>
                                                <label for="finish" class="custom-control-label">Afronden onderhoudsafspraak</label>
                                            </div>
                                        </div>

                                        <div id="signature-div">
                                            <div>
                                                <strong>Ingepland op:</strong> <?= date('d-m-Y', strtotime($aAppointment['visitDate'])) ?> -
                                                <strong>Datum voltooid:</strong> <?= date('d-m-Y', time()) ?><br />
                                                <strong>Handtekening namens:</strong> <?= $oCustomer->companyName ?><br />
                                                <strong>Naam aftekening:</strong> <input type="text" name="signatureName" placeholder="Anders dan contactpersoon" value="<?= $oCustomer->contactPersonName ?>">
                                            </div>

                                            <div class="signature-wrapper">
                                                <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
                                            </div>
                                            <button type="button" class="button btn btn-default clear" id="clear">Clear</button>
                                            <button class="button btn btn-success save" type="submit" id="save-signature">Voltooien</button>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>

                    <?php if ($aAppointment['finished'] && (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin())) {
                    ?>
                        <?php
                        if (!$aAppointment['mailed']) {
                        ?>
                            <form method="POST" action="#finishcard" id="mailed" style="float:right;">
                                <input type="hidden" name="mark" value="mailed">
                                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-envelope"></i> Markeer als verzonden
                                </button>

                            </form>
                        <?php } ?>
                        <?php
                        if (!$aAppointment['customer']) {
                        ?>
                            <form method="POST" action="#finishcard" id="showCustomer" style="float:right;">
                                <input type="hidden" name="mark" value="showCustomer">
                                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-eye"></i> Tonen aan klant
                                </button>

                            </form>
                        <?php } ?>
                        <form method="POST" action="">
                            <input type="hidden" name="export" value="PDF">
                            <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                <i class="fa fa-download"></i> Download onderhoudsformulier
                            </button>
                            <!--<select name="year" class="form-control mr-1" style="width:130px; float:left;">
                                <?php
                                for ($x = 2021; $x <= date('Y'); $x++) {
                                    echo '<option value="' . $x . '">' . ($x - 1) . ' - ' . $x . '</option>';
                                }
                                ?>
                            </select>-->
                        </form>

                <?php
                    }
                } elseif (isset($aAppointments)) {
                    require getAdminSnippet('adminAppointments', 'customers');
                }
                ?>



            <?php } ?>
        </div>

    </div>
</div>


<div class="modal fade" id="modal-signature">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Let op!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bij deze klant zijn nog niet alle systemen bezocht.<br /><br /><strong>Weet je zeker dat je deze afspraak wilt afronden?</strong></p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nee</button>
                <button type="button" class="btn btn-primary" id="do-finish">Ja, afronden</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-signature-del">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Let op!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Weet je zeker dat je deze handtekening wilt verwijderen?</strong></p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nee</button>
                <button type="button" class="btn btn-primary" id="do-finish-del">Ja, verwijderen</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<style>
    .signature-wrapper {
        position: relative;
        width: 100%;
        height: 400px;
        margin: 10px 0 10px 0;
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .signature-pad {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 400px;
        background-color: white;
    }
</style>

<?php



if (isset($aAppointment) && !empty($aAppointment) && !$aAppointment['finished']) {

    $sBottomJavascript = <<<EOT
<script type="text/javascript">



    $('#finish').on( "change", function() {

        if ( this.checked ) {

            if ( $('.finish-card').hasClass('card-danger') ) {
                $('#modal-signature').modal('show');
            } else {
                $('#signature-div').show();

            }
        } else {
            $('#signature-div').hide();
        }

    });

    $('#do-finish').on( "click", function() {
        $('#modal-signature').modal('hide');
        $('#signature-div').show();
    });

    $( document ).ready(function() {





        var canvas = document.getElementById('signature-pad');

        // Adjust canvas coordinate space taking into account pixel ratio,
        // to make it look crisp on mobile devices.
        // This also causes canvas to be cleared.
        function resizeCanvas() {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        $('#signature-div').hide();

       // var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
        $('#clear').click(function(e) {
            document.getElementById('clear').addEventListener('click', function () {
                 signaturePad.clear();
            });
        });

        $('#save-signature').on( "click", function() {

            event.preventDefault();

            if (signaturePad.isEmpty()) {
                alert("Er is nog geen handtekening gezet.");
                return false;
            }

            var data = signaturePad.toDataURL('image/png');
            $('#signature64').val(data);
            $('#saveSignatureForm').submit();
        });

    });
</script>

EOT;
    $oPageLayout->addJavascript($sBottomJavascript);
}

$iCustomerId = $oCustomer->customerId ? $oCustomer->customerId : 0;
$sBottomJavascript = <<<EOT
<script type="text/javascript">

    $('#undo-signature').click(function(event) {
        event.preventDefault();
        $('#modal-signature-del').modal('show');
    });
    $('#do-finish-del').on( "click", function() {
        $('#modal-signature-del').modal('hide');
        window.location.assign($('#undo-signature').attr('href'));
    });

    $('#debNr').on( "blur", function() {

        if (this.value) {

            var debNr = this.value;
            $.ajax('/dashboard/klanten/checkDebNr', {
				type: 'POST',  // http method
				data: { debNr: this.value, customerId: $iCustomerId },  // data to submit
				success: function (data, status, xhr) {
                    if (data=="exists") {
                        alert('Let op! Het debiteurennummer #' + debNr + ' is reeds in gebruik.');
                        $('#debNr').val('').focus();
                    }
				},
				error: function (jqXhr, textStatus, errorMessage) {
						alert("Something went wrong...");
				}
			});


        }


    });



        $('#uitbreidingsmogelijkheden').on( "change", function() {

            if ($('#uitbreidingsmogelijkheden').val() == 'Ja') {
                $('#uitbrInfoDiv').show();
            } else {
                $('#uitbrInfo').val('');
                $('#uitbrInfoDiv').hide();
            }

        });


    </script>

EOT;
$oPageLayout->addJavascript($sBottomJavascript);

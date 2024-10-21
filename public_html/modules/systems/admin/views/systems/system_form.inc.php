<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <span class="float-right">
                    <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer_overview') ?>">
                            <?= sysTranslations::get('to_customer_overview') ?>
                        </button>
                    </a>
                </span>
                <?php
                if (isset($oSystem) && !empty($oSystem->systemId)) {

                ?>
                    <span class="float-right pl-1">
                        <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>?customerId=<?= $oSystem->getLocation()->getCustomer()->customerId ?>">
                            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                                Naar systemen
                            </button>
                        </a>
                    </span>
                    <span class="float-right pl-1">
                        <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oSystem->getLocation()->getCustomer()->customerId ?>">
                            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                                Naar klant
                            </button>
                        </a>
                    </span>
                <?php } ?>

                <?php
                if (!empty($oCustomer->companyName)) {
                    $sTitle = _e($oCustomer->companyName);
                }
                ?>
                <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;<?= $sTitle ?></h1>

            </div>
        </div>
    </div>
</div>

<form method="POST" action="" class="validateForm" id="quickForm">
    <input type="hidden" value="save" name="action" />

    <input type="hidden" value="<?= $oSystem->locationId ? $oSystem->locationId : http_get('locationId') ?>" name="locationId" />

    <div class="container-fluid">

        <div class="row">
            <!-- left column -->
            <div class="col-md-3">

                <div class="card">
                    <div class="card-header">

                        <span class="float-right d-md-none">
                            <a class="backBtn right" href="#systemreports-table">
                                <button type="button" class="btn btn-default btn-sm" title="">
                                    <?= sysTranslations::get('system_reports') ?>
                                </button>
                            </a>
                        </span>
                        <?php
                        if (isset($oSystem) && !empty($oSystem->systemId)) {

                        ?>
                            <span class="float-right">
                                <select name="online" id="online">
                                    <option value="1"></option>
                                    <option value="0" <?= !$oSystem->online ? 'selected' : '' ?>>Vervallen</option>

                                </select>
                            </span>
                        <?php } ?>
                        <h3 class="card-title"><i aria-hidden="true" class="fa fa-shapes pr-1"></i> <?= sysTranslations::get('system_system') ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">



                        <div class="form-group">
                            <label for="locationId"><?= sysTranslations::get('systems_location') ?> *</label>
                            <select <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?>class="form-control" id="locationId" name="locationId" required title="<?= sysTranslations::get('system_location_tooltip') ?>">
                                <option value="">- <?= sysTranslations::get('system_location_tooltip') ?></option>
                                <?php
                                $aFilter = ['customerId' => $oCustomer->customerId];
                                foreach (LocationManager::getLocationsByFilter($aFilter) as $oLocation) {
                                    echo '<option ' . ($oLocation->locationId == $oSystem->locationId ? 'selected' : '') . ' value="' . $oLocation->locationId . '">' . $oLocation->name . '</option>';
                                }
                                ?>
                            </select>
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("locationId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>
                        <div class="form-group">
                            <label for="floor">Plaatsbepaling</label>
                            <input type="text" <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?>name="floor" class="form-control" id="floor" value="<?= _e($oSystem->floor) ?>" title="Plaatsbepaling" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("floor") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>
                        <div class="form-group">
                            <label for="systemTypeId"><?= sysTranslations::get('systems_type') ?> *</label>
                            <select <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?>class="form-control" id="systemTypeId" name="systemTypeId" required title="<?= sysTranslations::get('system_location_tooltip') ?>">
                                <option value="">- <?= sysTranslations::get('systemtype_select_tooltip') ?></option>
                                <?php

                                foreach (SystemTypeManager::getSystemTypesByFilter() as $oSystemType) {
                                    echo '<option ' . ($oSystemType->systemTypeId == $oSystem->systemTypeId ? 'selected' : '') . ' value="' . $oSystemType->systemTypeId . '">' . $oSystemType->name() . '</option>';
                                }
                                ?>
                            </select>
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("systemTypeId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>
                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('systems_name') ?> *</label>
                            <input type="text" <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?> name="name" class="form-control" id="name" value="<?= _e($oSystem->name) ?>" title="<?= sysTranslations::get('system_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>
                        <div class="form-group">
                            <label for="name">Positie *</label>
                            <input type="text" <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?> maxlength="5" name=" pos" class="form-control" id="pos" value="<?= _e($oSystem->pos) ?>" title="Positie" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("pos") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>


                        <div class="form-group">
                            <label for="model"><?= sysTranslations::get('systems_model') ?> *</label>
                            <input type="text" <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?> name="model" class="form-control" id="model" value="<?= _e($oSystem->model) ?>" title="<?= sysTranslations::get('system_model_tooltip') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("model") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>
                        <div class="form-group" id="divMachineNr" style="<?= $oSystem->systemTypeId == System::SYSTEM_TYPE_MULTILINER ? '' : 'display:none;' ?>">
                            <label for="machineNr">Machine#</label>
                            <input type="text" <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?> name="machineNr" class="form-control" id="machineNr" value="<?= _e($oSystem->machineNr) ?>" title="<?= sysTranslations::get('system_number_tooltip') ?>" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oSystem->isPropValid("machineNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>


                        <div class="form-group">

                                    <label for="date">Installatiedatum</label>
                                    <div class="input-group date" id="installDate" data-target-input="nearest">
                                        <input type="text" <?= (!$oSystem->isEditable() ? 'readonly disabled ' : '') ?> class="form-control datetimepicker" name="installDate" data-target="#installDate" value="<?= !empty($oSystem->installDate) ? date('d-m-Y', strtotime($oSystem->installDate)) : '' ?>">
                                        <div class="input-group-append" data-target="#installDate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>

                        </div>

                        <div class="form-group">
                            <label for="notice">Opmerkingen</label>
                            <div style="max-height:100px; overflow-y: auto;">

                                <?php
                                echo '<input name="notice[]" ' . (!$oSystem->isEditable() ? 'readonly disabled ' : '') . 'placeholder="Hier invoeren om toe te voegen" value="" class="form-control remark">';
                                $aList = explode(PHP_EOL, trim($oSystem->notice ?? ''));
                                foreach ($aList as $sNotice) {
                                    if (!empty($sNotice)) {
                              
                                        echo '<input ' . (!$oSystem->isEditable() ? 'readonly disabled ' : '') . 'name="notice[]" value="' . _e($sNotice) . '" class="form-control">';
                                    }
                                }
                                ?>
                            </div>
                            <div class="note pt-1">Alleen de bovenste gevulde regel wordt getoond in exports</div>
                        </div>
                    </div>

                    <div class="card-footer">

                     
                        <input <?=$oSystem->isEditable() ? '' : 'style="display:none"'?> type="submit" class="btn btn-primary firstsubmit" value="<?= sysTranslations::get('global_save') ?>" name="save" /><br />
                        <input <?=$oSystem->isEditable() ? '' : 'style="display:none"'?> type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?> > locatie" name="save" />
                        <input <?=$oSystem->isEditable() ? '' : 'style="display:none"'?> type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?> > klant" name="save" />
                       
                    </div>

                </div>


            </div>

            <?php
            if ($oSystem->systemId) {
            ?>
                <div class="col-md-9">
                    <a name="systemreports-table" id="systemreports-table"></a>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i aria-hidden="true" class="fa fa-file-contract pr-1"></i> Metingen</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: auto;">
                                    <div class="input-group-append">
                                        <?php
                                        $oAppointment = CustomerManager::getLastAppointment($oCustomer->customerId, UserManager::getCurrentUser()->userId);

                                        // admin can edit/add
                                        if (!$oAppointment && (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin())) {
                                            $oAppointment = CustomerManager::getLastAppointment($oCustomer->customerId, null);
                                        }

                                        if ( ($oAppointment && $oAppointment["finished"] == 0 && substr($oAppointment["visitDate"], 0, 4) == date('Y', time())) || (UserManager::getCurrentUser()->isEngineer() || UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) ) {
                                     
                                        ?>
                                            <a class="addBtn" href="<?= ADMIN_FOLDER ?>/system-reports/toevoegen?systemId=<?= $oSystem->systemId ?>
    <?= (UserManager::getCurrentUser()->isEngineer() || 
        UserManager::getCurrentUser()->isClientAdmin() || 
        UserManager::getCurrentUser()->isSuperAdmin() && isset($oAppointment["appointmentId"])) 
        ? '&warning=' . $oAppointment["appointmentId"] 
        : '' ?>" 
    title="<?= sysTranslations::get('add_item') ?> 
    <?= isset($oAppointment["visitDate"]) ? '(Afspraak op ' . date('d-m-Y', strtotime($oAppointment["visitDate"])) . ')' : '' ?>">

                                                <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                            </a>&nbsp;
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <!--/.col (left) -->
                            <div class="col-12">

                                <?php
                                include getAdminSnippet('systemReports/systemReportsList_' . $oSystem->systemTypeId, 'systems');
                                ?>

                            </div>

                        </div>

                    </div><!-- /.container-fluid -->
                </div>
            <?php } ?>

        </div>

    </div>


</form>

<div class="modal" id="onlineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Weet u het zeker?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Zodra het systeem op 'vervallen' staat is deze NA OPSLAAN alleen nog bereikbaar voor een Administrator.</p>
                <p>Indien je op vervallen klikt, wordt het opmerkingen veld onderaan dit formulier verplicht en de ingevoerde melding zal verstuurd worden naar Optivolt.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary vervallen">Vervallen</button>
                <button type="button" class="btn btn-secondary annuleer" data-dismiss="modal">Annuleer</button>
            </div>
        </div>
    </div>
</div>

<?php

$sMultiliner                = System::SYSTEM_TYPE_MULTILINER;
$sEOL = PHP_EOL;

$sBottomJavascript = <<<EOT
<script type="text/javascript">

$(document).on('change','#systemTypeId',function(){

    if ($(this).val() == $sMultiliner) {
        $('#divMachineNr').show();
    } else {
        $('#divMachineNr').hide();
    }



});


$(document).on('change','#online',function(){


    if ($(this).val() == 0) {
        $('#onlineModal').modal('show');
    }




});

$(document).on('click','.annuleer',function(){
    $('#online').val(1);
    $('#onlineModal').modal('hide');
});
$(document).on('click','.vervallen',function(){
    $('#onlineModal').modal('hide');
    $('.remark').prop("readonly", false).prop("disabled", false).focus().prop("required", true);
    $('.firstsubmit').show();
    //var notice = $('#notice').val();
    //notice = 'Vervallen' + "\\n" + notice;
    //$('#notice').val(notice);
});

//Date picker
$('#installDate').datetimepicker({
format: 'DD-MM-YYYY'
});

</script>

EOT;
$oPageLayout->addJavascript($sBottomJavascript);

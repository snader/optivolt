<form method="POST" action="" id="addAppointment">
  <a name="addAppointment"></a>
  <?= CSRFSynchronizerToken::field() ?>
  <input type="hidden" name="saveAppointmentUserAndDate" value="save">
  <input type="hidden" name="appointmentId" value="<?= $aEditAppointment["appointmentId"] ?>">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-calendar pr-1"></i> Afspraak <?= http_get('param3') == 'afspraak-bewerken' ? 'bewerken' : 'toevoegen' ?></h3>
    </div>
    <div class="card-body">

      <!-- Date -->
      <div class="form-group">
        <div class="row">
          <div class="col-lg-6"><label for="visitDate">Datum *</label></div>
          <div class="col-lg-6"><label for="orderNr">Order#</label></div>
        </div>
        
        <div class="row">
        <div class="col-lg-6">
          <div class="input-group date" id="visitDate" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" value="<?= $aEditAppointment["visitDate"] ? date('d-m-Y', strtotime($aEditAppointment["visitDate"])) : '' ?>" name="visitDate" data-inputmask-inputformat="dd-mm-YYYY" data-target="#visitDate" />
            <div class="input-group-append" data-target="#visitDate" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <input type="text" name="orderNr" placeholder="Ordernummer" class="form-control" id="orderNr" value="<?=$aEditAppointment['orderNr']?>" title="">
        </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="userId">Onderhoudsmonteur *</label>
        <select class="form-control " id="userId" name="userId" required title="Selecteer een onderhoudsmonteur">
          <?php
          $aFilter['userAccessGroupId'] = 2;
          foreach (UserManager::getUsersByFilter($aFilter) as $oUser) {
            echo '<option ' . ($oUser->userId == $aEditAppointment['userId'] ? 'selected' : '') . ' value="' . $oUser->userId . '">' . $oUser->name . '</option>';
          }
          ?>
        </select>
        <span class="error invalid-feedback show"><?= $oUser->isPropValid("userId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
      </div>


    </div>
    <div class="card-footer">
      <span class="float-sm-right">
        <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oCustomer->customerId ?>">
          <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
            <?= sysTranslations::get('global_back') ?>
          </button>
        </a>
      </span>
      <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />

    </div>
  </div>
</form>

<?php
$sBottomDateJavascript = <<<EOT
<script type="text/javascript">
//Date picker
$('#visitDate').datetimepicker({
format: 'DD-MM-YYYY'
});



</script>

EOT;
$oPageLayout->addJavascript($sBottomDateJavascript);
?>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fas fa-suitcase"></i>&nbsp;&nbsp;Loggers defaults</h1>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-calendar pr-1"></i> Standaard periode</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" class="validateForm" id="quickForm">
          <?= CSRFSynchronizerToken::field() ?>
          <input type="hidden" value="save" name="action" />
          <div class="card-body">

            <?php
            if ($oLoggersDefault->isOnlineChangeable()) {
            ?>
              <div class="form-group">
                <div class="row border-bottom mb-2 pb-1">
                  <div class="col-md-4">
                    <label for="online"><?= sysTranslations::get('global_activated') ?></label>
                  </div>
                  <div class="col-md-8">
                    <input type="checkbox" title="<?= sysTranslations::get('set_online') ?>" id="online" name="online" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>" <?= $oLoggersDefault->online ? 'CHECKED' : '' ?>>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <input type="hidden" value="<?= $oLoggersDefault->online ?>" name="online" />
            <?php } ?>

            <div class="form-group">
              <label for="name"><?= sysTranslations::get('global_name') ?> *</label>
              <input type="text" name="name" class="form-control" id="name" value="<?= $oLoggersDefault->name ?>" title="<?= sysTranslations::get('enter_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('enter_name_tooltip') ?>">
              <span class="error invalid-feedback show"><?= $oLoggersDefault->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>

            <div class="form-group">
              <div class="row mb-2 pb-1">
                <div class="col-md-4">
                  <label for="days">Aantal dagen</label>
                  <input type="number" name="days" class="form-control" id="days" min="1" value="<?= $oLoggersDefault->days ?>" title="<?= sysTranslations::get('enter_days_tooltip') ?>" required data-msg="<?= sysTranslations::get('enter_days_tooltip') ?>">
                </div>
              </div>
              <div class="card-footer">
                <span class="float-right">
                  <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                      <?= sysTranslations::get('to_overview') ?>
                    </button>
                  </a>
                </span>
                <?php if ($oLoggersDefault->isEditable()) { ?>
                  <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
                <?php } ?>
              </div>
        </form>

      </div>
    </div>
  </div>
</div>


<?php
$sBottomDateJavascript = <<<EOT
<script type="text/javascript">
//Date picker
$('#date').datetimepicker({
format: 'DD-MM-YYYY'
});


$('#dayNumber').change(function() {
  $('.datetimepicker').val('');
});





</script>

EOT;
$oPageLayout->addJavascript($sBottomDateJavascript);
?>
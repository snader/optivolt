<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fas fa-suitcase"></i>&nbsp;&nbsp;Loggers uitzonderingsdagen</h1>
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
          <h3 class="card-title"><i class="fas fa-calendar pr-1"></i> Uitzonderingsdag</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" class="validateForm" id="quickForm">
          <?= CSRFSynchronizerToken::field() ?>
          <input type="hidden" value="save" name="action" />
          <div class="card-body">

            <?php
            if ($oLoggersDay->isOnlineChangeable()) {
            ?>
              <div class="form-group">
                <div class="row border-bottom mb-2 pb-1">
                  <div class="col-md-4">
                    <label for="online"><?= sysTranslations::get('global_activated') ?></label>
                  </div>
                  <div class="col-md-8">
                    <input type="checkbox" title="<?= sysTranslations::get('set_online') ?>" id="online" name="online" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>" <?= $oLoggersDay->online ? 'CHECKED' : '' ?>>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <input type="hidden" value="<?= $oLoggersDay->online ?>" name="online" />
            <?php } ?>

            <div class="form-group">
              <label for="name"><?= sysTranslations::get('global_name') ?> *</label>
              <input type="text" name="name" class="form-control" id="name" value="<?= $oLoggersDay->name ?>" title="<?= sysTranslations::get('enter_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('enter_name_tooltip') ?>">
              <span class="error invalid-feedback show"><?= $oLoggersDay->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>

            <div class="form-group">
              <div class="row mb-2 pb-1">
                <div class="col-md-6">
                  <label for="dayNumber">Dag van de week</label>
                  <select class="custom-select rounded-0" name="dayNumber" class="form-control" id="dayNumber">
                    <option value="">-</option>
                    <option <?= $oLoggersDay->dayNumber == '1' ? 'selected ' : '' ?>value="1">Maandag</option>
                    <option <?= $oLoggersDay->dayNumber == '2' ? 'selected ' : '' ?>value="2">Dinsdag</option>
                    <option <?= $oLoggersDay->dayNumber == '3' ? 'selected ' : '' ?>value="3">Woensdag</option>
                    <option <?= $oLoggersDay->dayNumber == '4' ? 'selected ' : '' ?>value="4">Donderdag</option>
                    <option <?= $oLoggersDay->dayNumber == '5' ? 'selected ' : '' ?>value="5">Vrijdag</option>
                    <option <?= $oLoggersDay->dayNumber == '6' ? 'selected ' : '' ?>value="6">Zaterdag</option>
                    <option <?= $oLoggersDay->dayNumber == '0' ? 'selected ' : '' ?>value="0">Zondag</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="date">of specifieke datum</label>
                  <div class="input-group date" id="date" data-target-input="nearest">
                    <input type="text" name="date" class="form-control datetimepicker" data-target="#date" value="<?= !empty($oLoggersDay->date) ? date('d-m-Y', strtotime($oLoggersDay->date)) : '' ?>">
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>


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
              <?php if ($oLoggersDay->isEditable()) { ?>
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
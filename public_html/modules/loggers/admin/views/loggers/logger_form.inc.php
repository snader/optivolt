<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fas fa-suitcase"></i>&nbsp;&nbsp;Loggers</h1>
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
          <h3 class="card-title"><i class="fas fa-calendar pr-1"></i> Logger</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" class="validateForm" id="quickForm">
          <?= CSRFSynchronizerToken::field() ?>
          <input type="hidden" value="save" name="action" />
          <div class="card-body">

            <?php
            if ($oLogger->isOnlineChangeable()) {
            ?>
              <div class="form-group">
                <div class="row border-bottom mb-2 pb-1">
                  <div class="col-md-4">
                    <label for="online"><?= sysTranslations::get('global_activated') ?></label>
                  </div>
                  <div class="col-md-8">
                    <input type="checkbox" title="<?= sysTranslations::get('set_online') ?>" id="online" name="online" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>" <?= $oLogger->online ? 'CHECKED' : '' ?>>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <input type="hidden" value="<?= $oLogger->online ?>" name="online" />
            <?php } ?>

            <div class="form-group">
              <label for="name"><?= sysTranslations::get('global_name') ?> *</label>
              <input type="text" name="name" class="form-control" id="name" value="<?= $oLogger->name ?>" title="<?= sysTranslations::get('enter_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('enter_name_tooltip') ?>">
              <span class="error invalid-feedback show"><?= $oLogger->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>

            <div class="form-group">
              <div class="row mb-2 pb-1">
                <div class="col-md-4">
                  <label for="date">Beschikbaar vanaf</label>
                  <div class="input-group date" id="availableFrom" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker" name="availableFrom" data-target="#availableFrom" value="<?= !empty($oLogger->availableFrom) ? date('d-m-Y', strtotime($oLogger->availableFrom)) : '' ?>">
                    <div class="input-group-append" data-target="#availableFrom" data-toggle="datetimepicker">
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
              <?php if ($oLogger->isEditable()) { ?>
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
$('#availableFrom').datetimepicker({
format: 'DD-MM-YYYY'
});



</script>

EOT;
$oPageLayout->addJavascript($sBottomDateJavascript);
?>
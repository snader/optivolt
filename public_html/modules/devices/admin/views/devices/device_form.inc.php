<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fas fa-plug"></i>&nbsp;&nbsp;Apparaten</h1>
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
          <h3 class="card-title"><i class="fas fa-calendar pr-1"></i> Apparaat</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" class="validateForm" id="quickForm">
          <?= CSRFSynchronizerToken::field() ?>
          <input type="hidden" value="save" name="action" />
          <div class="card-body">

            <?php
            if ($oDevice->isOnlineChangeable()) {
            ?>
              <div class="form-group">
                <div class="row border-bottom mb-2 pb-1">
                  <div class="col-md-4">
                    <label for="online"><?= sysTranslations::get('global_activated') ?></label>
                  </div>
                  <div class="col-md-8">
                    <input type="checkbox" title="<?= sysTranslations::get('set_online') ?>" id="online" name="online" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" data-on-color="success" data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>" <?= $oDevice->online ? 'CHECKED' : '' ?>>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <input type="hidden" value="<?= $oDevice->online ?>" name="online" />
            <?php } ?>

            <div class="form-group">
              <label for="name"><?= sysTranslations::get('global_name') ?> *</label>
              <input type="text" name="name" class="form-control" id="name" value="<?= _e($oDevice->name) ?>" title="<?= sysTranslations::get('enter_name_tooltip') ?>" required data-msg="<?= sysTranslations::get('enter_name_tooltip') ?>">
              <span class="error invalid-feedback show"><?= $oDevice->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
            <div class="form-group">
              <label for="name">Merk</label>
              <input type="text" name="brand" class="form-control" id="brand" value="<?= _e($oDevice->brand) ?>" title="Voer merk in">
              <span class="error invalid-feedback show"><?= $oDevice->isPropValid("brand") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
            <div class="form-group">
              <label for="name">Type</label>
              <input type="text" name="type" class="form-control" id="type" value="<?= _e($oDevice->type) ?>" title="Voer type in">
              <span class="error invalid-feedback show"><?= $oDevice->isPropValid("type") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
            <div class="form-group">
              <label for="name">Serienummer</label>
              <input type="text" name="serial" class="form-control" id="serial" value="<?= _e($oDevice->serial) ?>" title="Voer serienummer in">
              <span class="error invalid-feedback show"><?= $oDevice->isPropValid("serial") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
           
            <div class="card-footer">
              <span class="float-right">
                <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                  <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                    <?= sysTranslations::get('to_overview') ?>
                  </button>
                </a>
              </span>
              <?php if ($oDevice->isEditable()) { ?>
                <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
              <?php } ?>
            </div>
        </form>

      </div>
    </div>
  </div>
</div>



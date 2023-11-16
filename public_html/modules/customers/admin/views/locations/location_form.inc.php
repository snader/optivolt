<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">

        <span class="float-sm-right">
          <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oLocation->getCustomer()->customerId ?>">
            <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?> <?= _e($oLocation->getCustomer()->companyName) ?>">
              <?= sysTranslations::get('to_customer') ?>
            </button>
          </a>
        </span>

        <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;<?= _e($oLocation->getCustomer()->companyName) ?></h1>
      </div>
    </div>
  </div>
</div>

<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
  <input type="hidden" value="save" name="action" />
  <input type="hidden" value="<?= $oLocation->customerId ? $oLocation->customerId : http_get('customerId') ?>" name="customerId" />

  <div class="container-fluid">

    <div class="row">
      <?php
      if ($oLocation->isEditable()) {
      ?>
        <!-- left column -->
        <div class="col-md-6">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?= sysTranslations::get('customer_location') ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="form-group">
                <label for="title"><?= sysTranslations::get('customer_location') ?> <?= strtolower(sysTranslations::get('global_name')) ?> *</label>
                <input type="text" name="name" class="form-control" id="name" value="<?= _e($oLocation->name) ?>" title="<?= sysTranslations::get('location_title_tooltip') ?>" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                <span class="error invalid-feedback show"><?= $oLocation->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
            </div>
            <div class="card-footer">

              <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?> > Klant" name="save" />
            </div>
          </div>

        </div>
      <?php } ?>
      <!--/.col (left) -->
      <?php
      if ($oLocation->locationId) {
      ?>
        <div class="col-md-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-thumbtack pr-1"></i> <?= sysTranslations::get('location_systems') ?>: <strong><?= _e($oLocation->name) ?></strong></h3>
              <div class="card-tools">

                <div class="input-group input-group-sm" style="width: auto;">
                  <?php
                  if ($bShowAddButton) {
                  ?>
                    <div class="input-group-append">
                      <a class="addBtn" href="<?= ADMIN_FOLDER . '/systems' ?>/toevoegen?locationId=<?= $oLocation->locationId ?>&customerId=<?= $oLocation->customerId ?>" title="Systeem toevoegen op deze locatie">
                        <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                          <i class="fas fa-plus-circle"></i>
                        </button>
                      </a>&nbsp;
                    </div>
                  <?php } else {
                  ?>
                    <span class="float-sm-right">
                      <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oLocation->customerId ?>">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?>">
                          <?= sysTranslations::get('to_customer') ?>
                        </button>
                      </a>
                    </span>
                  <?php
                  }
                  ?>
                </div>
              </div>

            </div>
            <div class="card-body">
              <?php
              $bShowCustomer = false;
              $bShowLocation = false;
              require getAdminSnippet('systems_list', 'systems');
              ?>
            </div>
            <div class="card-footer">
              <span class="float-sm-right">
                <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oLocation->customerId ?>">
                  <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?>">
                    <?= sysTranslations::get('to_customer') ?>
                  </button>
                </a>
              </span>

            </div>


          </div><!-- /.container-fluid -->

        </div>
      <?php } ?>

    </div>
  </div>
</form>
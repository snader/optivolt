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
  <div class="row mb-2">
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
                      <input type="hidden" value="0" name="online" />
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
                <label for="brand">Merk</label>
                <input type="text" name="brand" class="form-control" id="brand" value="<?= _e($oDevice->brand) ?>" title="Voer merk in">
                <span class="error invalid-feedback show"><?= $oDevice->isPropValid("brand") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" class="form-control" id="type" value="<?= _e($oDevice->type) ?>" title="Voer type in">
                <span class="error invalid-feedback show"><?= $oDevice->isPropValid("type") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="serial">Serienummer</label>
                <input type="text" name="serial" class="form-control" id="serial" value="<?= _e($oDevice->serial) ?>" title="Voer serienummer in">
                <span class="error invalid-feedback show"><?= $oDevice->isPropValid("serial") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                  <label>Apparaatgroepen</label><br />

                  <?php 
                  foreach (DeviceGroupManager::getAllDeviceGroups() as $oDeviceGroup) {                                                               
                      ?>
                      <label style="font-weight: normal;width:32%;" for="deviceGroup<?= $oDeviceGroup->deviceGroupId ?>">
                          <input type="checkbox" name="deviceGroupIds[]" id="deviceGroup<?= $oDeviceGroup->deviceGroupId ?>" value="<?= $oDeviceGroup->deviceGroupId ?>" 
                          
                          <?= ($oDevice->isLinkedToDeviceGroup($oDeviceGroup->deviceGroupId) || $oDeviceGroup->name == DeviceGroup::DEVICEGROUP_GENERAL) ? 'checked="checked"' : '' ?>>&nbsp;<?= _e($oDeviceGroup->title) ?>
                      </label>
                      
                  <?php 
                  }?>
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
                  <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?> > overzicht" name="save" />
                <?php } ?>
              </div>
          </form>

        </div>
      
      </div>

    </div>
    <!-- right column -->
    <div class="col-md-6">
      <?php
            if ($oDevice->deviceId) { ?>
                <a name="certificates-table" id="certificates-table"></a>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-thumbtack pr-1"></i> Testcertificaten</h3>
                        <div class="card-tools">
                            <?php
                            if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
                            ?>
                                <div class="input-group input-group-sm" style="width: auto;">
                                    <div class="input-group-append">
                                        <a class="addBtn" href="<?= ADMIN_FOLDER ?>/certificaten/toevoegen?deviceId=<?= $oDevice->deviceId ?>" title="<?= sysTranslations::get('add_item') ?>">
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
                                    <th style="text-align:left;">Keurmeester</th>
                                    <th style="text-align:center;">Datum</th>
                                    <th style="text-align:center;">Volgende controle</th>
                                    <th style="width: 10px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $aCertificates = $oDevice->getCertificates();
                                $iCount = 0;
                                foreach ($aCertificates as $oCertificate) {
                                          $iCount++;                                                
                                ?>
                                    <tr>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/certificaten/bewerken/' . $oCertificate->certificateId ?>" title="<?= sysTranslations::get('certificate_edit') ?>">
                                                <i class="fas fa-x fa-<?= $oCertificate->isEditable() ? 'pencil-alt' : 'search-certificate' ?>"></i>
                                            </a>
                                        </td>
                                        <td><?= $oCertificate->name ?></td>                                        
                                        <td align="center"><?= date('d-m-Y', strtotime($oCertificate->created)) ?></td>
                                        <td align="center"><?= ($iCount==1 && $oCertificate->nextcheck) ? date('d-m-Y', strtotime($oCertificate->nextcheck)) : '' ?></td>
                                        <?php
                                        if ($oCertificate->isDeletable()) {
                                        ?>
                                            <td><a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/certificaten/verwijderen/' . $oCertificate->certificateId ?>" title="Verwijder certificaat" onclick="return confirmChoice('<?= _e('Device ' . $oDevice->name . ' > certificaat ' . $oCertificate->certificateId) ?>');">
                                                    <i class="fas fa-trash"></i>
                                                </a></td>
                                        <?php } ?>


                                    </tr>
                                <?php
                                }
                                if (empty($aCertificates)) {
                                    echo '<tr><td colspan="6"><i>Geen certificaten gevonden</i></td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
      <?php } ?>    
    </div>
  </div>
</div>



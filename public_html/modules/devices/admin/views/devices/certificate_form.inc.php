<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fas fa-certificate"></i>&nbsp;&nbsp;Testcertificaat</h1>
      </div>
    </div>
  </div>
</div>

<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
<?= CSRFSynchronizerToken::field() ?>
<input type="hidden" value="save" name="action" />

<div class="container-fluid">
  <div class="row mb-2">
    <!-- left column -->
    <div class="col-md-6">

      <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-server pr-1"></i> Apparaat: <strong><?= $oDevice->name ?></strong></h3>
          </div>
          <!-- /.card-header -->
          
            <div class="card-body">

              <div class="form-group">
                <label for="name">Apparaat</label>
                <input type="text" readonly name="name" class="form-control" id="name" value="<?= _e($oDevice->name) ?>">                
              </div>
              <div class="form-group">
                <label for="brand">Merk</label>
                <input type="text" readonly name="brand" class="form-control" id="brand" value="<?= _e($oDevice->brand) ?>">   
              </div>
              <div class="form-group">
                <label for="name">Type</label>
                <input type="text" readonly name="type" class="form-control" id="type" value="<?= _e($oDevice->type) ?>">   
              </div>
              <div class="form-group">
                <label for="serial">Serienummer</label>
                <input type="text" readonly name="serial" class="form-control" id="serial" value="<?= _e($oDevice->serial) ?>">   
              </div>
                          
              
          

        </div>
      
      </div>

    </div>
    <!-- right column -->
    <div class="col-md-6">

      <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file pr-1"></i> Getest door: <strong><?= UserManager::getUserById($oCertificate->userId)->name ?></strong><?= $oCertificate->created ? ' op ' . date("d-m-Y", strtotime($oCertificate->created)) : ''?></h3>
          </div>
          <!-- /.card-header -->
          
            <div class="card-body">

              <div class="form-group">
                <label for="vbbNr">VBB nummer</label>
                <input type="text" name="vbbNr" class="form-control" id="vbbNr" value="<?= _e($oCertificate->vbbNr) ?>" title="<?= sysTranslations::get('enter_vbbnr_tooltip') ?>">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("vbbNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="testInstrument">Test instrument</label>
                <input type="text" name="testInstrument" class="form-control" id="testInstrument" value="<?= _e($oCertificate->testInstrument) ?>" title="Voer test instument in">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("testInstrument") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="testSerialNr">Serienummer</label>
                <input type="text" name="testSerialNr" class="form-control" id="testSerialNr" value="<?= _e($oCertificate->testSerialNr) ?>" title="Voer serienummer in">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("testSerialNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="nextcheck">Volgende keuring</label>                
                  <div class="input-group date" id="nextcheck" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker" name="nextcheck" data-target="#nextcheck" value="<?= !empty($oCertificate->nextcheck) ? date('d-m-Y', strtotime($oCertificate->nextcheck)) : '' ?>">
                    <div class="input-group-append" data-target="#nextcheck" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
              </div>
              
                         

        </div>
      
      </div>
      
    </div>

    <!-- checklist -->
    <div class="col-md-12">

      <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-check pr-1"></i> Checklist</h3>
          </div>
          <!-- /.card-header -->
          
            <div class="card-body">

              <div class="form-group">
                <label for="vbbNr">VBB nummer</label>
                <input type="text" name="vbbNr" class="form-control" id="vbbNr" value="<?= _e($oCertificate->vbbNr) ?>" title="<?= sysTranslations::get('enter_vbbnr_tooltip') ?>">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("vbbNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="testInstrument">Test instrument</label>
                <input type="text" name="testInstrument" class="form-control" id="testInstrument" value="<?= _e($oCertificate->testInstrument) ?>" title="Voer test instument in">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("testInstrument") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="testSerialNr">Serienummer</label>
                <input type="text" name="testSerialNr" class="form-control" id="testSerialNr" value="<?= _e($oCertificate->testSerialNr) ?>" title="Voer serienummer in">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("testSerialNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                
              </div>
            
              <div class="card-footer">
                <span class="float-right">
                  <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                      <?= sysTranslations::get('to_overview') ?>
                    </button>
                  </a>
                </span>
                <?php if ($oCertificate->isEditable()) { ?>
                  <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
                <?php } ?>
              </div>
          

        </div>
      
      </div>
      
    </div>
  </div>
</div>
</form>

<?php
$sBottomDateJavascript = <<<EOT
<script type="text/javascript">
//Date picker
$('#nextcheck').datetimepicker({
format: 'DD-MM-YYYY'
});



</script>

EOT;
$oPageLayout->addJavascript($sBottomDateJavascript);
?>
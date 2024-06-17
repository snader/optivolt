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

    <!-- checklist -->
    <div class="col-md-4">
      <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-check pr-1"></i> Checklist</h3>
          </div>
          <!-- /.card-header -->
          
            <div class="card-body">

              <div class="form-group">
                <label for="visualCheck">1. Visuele controle</label>
                <input type="text" name="visualCheck" class="form-control" id="visualCheck" value="<?= _e($oCertificate->visualCheck) ?>" title="Visuele controle">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("vbbNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="weerstandBeLeRPE">2. Weerstand bescherming leiding RPE</label>
                <input type="text" name="weerstandBeLeRPE" class="form-control" id="weerstandBeLeRPE" value="<?= _e($oCertificate->weerstandBeLeRPE) ?>" title="Weerstand bescherming leiding RPE">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("weerstandBeLeRPE") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="isolatieWeRISO">3. Isolatie weerstand RISO</label>
                <input type="text" name="isolatieWeRISO" class="form-control" id="isolatieWeRISO" value="<?= _e($oCertificate->isolatieWeRISO) ?>" title="Isolatie weerstand RISO">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("isolatieWeRISO") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="lekstroomIEA">4. Lekstroom via bescherming leiding IEA</label>
                <input type="text" name="lekstroomIEA" class="form-control" id="lekstroomIEA" value="<?= _e($oCertificate->lekstroomIEA) ?>" title="Lekstroom via bescherming leiding IEA">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("lekstroomIEA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
              <div class="form-group">
                <label for="lekstroomTouch">5. Lekstroom door aanraking</label>
                <input type="text" name="lekstroomTouch" class="form-control" id="lekstroomTouch" value="<?= _e($oCertificate->lekstroomTouch) ?>" title="Lekstroom door aanraking">
                <span class="error invalid-feedback show"><?= $oCertificate->isPropValid("lekstroomTouch") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
              </div>
           
            
              
          

        </div>

      </div>
    </div>    
    <!-- tester column -->
    <div class="col-md-4">

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
    <!-- apparaat info column -->
    <div class="col-md-4">

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

    <div class="col-md-12">
      <div class="card">

      <div class="card-footer">
                <span class="float-right">
                 <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/devices/bewerken/<?=$oCertificate->deviceId?>">
                    <button type="button" class="btn btn-default btn-sm" title="Naar apparaat">
                      Naar apparaat
                    </button>
                  </a>
                  <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                      <?= sysTranslations::get('to_overview') ?>
                    </button>
                  </a>
                </span>
                <?php if ($oCertificate->isEditable()) { ?>
                  <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />&nbsp;
                  <input type="submit" class="btn btn-primary" value="Opslaan > PDF" name="save" />&nbsp;
                  <input type="submit" class="btn btn-primary" value="Opslaan > Overzicht" name="save" /> 
                <?php } ?>
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
<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
  <input type="hidden" value="save" name="action" />

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <?php
          if ($oEvaluation->customerId) {
          ?>
            <span class="float-sm-right">
              <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oEvaluation->getCustomer()->customerId ?>">
                <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?> <?= _e($oEvaluation->getCustomer()->companyName) ?>">
                  <?= sysTranslations::get('to_customer') ?>
                </button>
              </a>
            </span>
          <?php } ?>
          <h1 class="m-0"><i aria-hidden="true" class="fa fa-star fa-th-star"></i>&nbsp;&nbsp;Evaluatie  
          <?php 
            if ($oEvaluation->customerId) { 
              
              _e("door " . $oEvaluation->getCustomer()->companyName); 
              ?>
              <input type="hidden" name="customerId" value="<?= $oEvaluation->customerId ?>">
              <?php
            
            } ?>
          </h1>
        </div>
      </div>
    </div>
  </div>


  <input type="hidden" value="<?= $oEvaluation->customerId ? $oEvaluation->customerId : http_get('customerId') ?>" name="customerId" />

  <div class="container-fluid">

    <div class="row">
      
      <!-- left column -->
      <div class="col-md-6">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Formulier</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <?php
          if (!$oEvaluation->customerId) { ?>
            <div class="form-group">
              <label for="title">Klant *</label>
              
                <select class="select2 form-control form-control-sm" style="width:100%;" name="customerId" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                    <option>selecteer klant</option>
                    <?php
                    foreach ($aAllCustomers as $oCustomer) {
                      echo '<option value="' . $oCustomer->customerId . '">' . $oCustomer->companyName . '</option>';
                    } 
                    ?>
                </select>             
               
              <span class="error invalid-feedback show"><?= $oEvaluation->isPropValid("customerId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
          <?php } ?>

            <div class="form-group">
                <label for="installSat">Is de installatie naar tevredenheid verlopen?</label>
                <select class="form-control" id="installSat" name="installSat" title="Selecteer een antwoord">
                    <option value="">- Selecteer</option>
                    <option <?= $aAppointment['installSat'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $aAppointment['installSat'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="anyDetails">Zijn er nog bijzonderheden?</label>
                <select class="form-control" id="anyDetails" name="anyDetails" title="Selecteer een antwoord">
                    <option value="">- Selecteer</option>
                    <option <?= $aAppointment['installSat'] == 'Ja' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $aAppointment['installSat'] == 'Nee' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>


          </div>
          <div class="card-footer">

            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?> > Klant" name="save" />
          </div>
        </div>

      </div>
 
      <!--/.col (left) -->
      

    </div>
  </div>
</form>
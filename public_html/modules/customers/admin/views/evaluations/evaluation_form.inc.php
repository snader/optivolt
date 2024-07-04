<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
  <input type="hidden" value="save" name="action" />

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
        <span class="float-right pl-1">
                    <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/evaluaties">
                        <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                            Overzicht
                        </button>
                    </a>
                </span>
          <?php
  
          if (!empty($oEvaluation->customerId)) {
          ?>
            <span class="float-sm-right">
              <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/klanten/bewerken/<?= $oEvaluation->customerId ?>">
                <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('to_customer') ?> <?= _e($oEvaluation->getCustomer()->companyName) ?>">
                  <?= sysTranslations::get('to_customer') ?>
                </button>
              </a>
            </span>
          <?php } ?>
          <h1 class="m-0"><i aria-hidden="true" class="fa fa-star fa-th-star"></i>&nbsp;&nbsp;Evaluatie  
          <?php 
            if (!empty($oEvaluation->customerId)) {
              
              echo _e("door " . $oEvaluation->getCustomer()->companyName); 
              ?>
              <input type="hidden" name="customerId" value="<?= $oEvaluation->customerId ?>">
              <?php
            
            } ?>
          </h1>
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
            <h3 class="card-title">Evaluatieformulier</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <?php
          if (!$oEvaluation->customerId) { ?>
            <div class="form-group">
              <label for="title">Klant *</label>
              
                <select class="select2 form-control form-control-sm" style="width:100%;" name="customerId" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                    <option value="">selecteer klant</option>
                    <?php
                    foreach ($aAllCustomers as $oCustomer) {
                      echo '<option value="' . $oCustomer->customerId . '">' . $oCustomer->companyName . '</option>';
                    } 
                    ?>
                </select>             
               
              <span class="error invalid-feedback show"><?= $oEvaluation->isPropValid("customerId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
          <?php } else { ?>

            <div class="form-group">
                <label for="installSat">Is de installatie naar tevredenheid verlopen?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="installSat" name="installSat" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->installSat) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->installSat == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->installSat == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="anyDetails">Zijn er nog bijzonderheden?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="anyDetails" name="anyDetails" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->anyDetails) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->anyDetails == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->anyDetails == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="conMeasured">Zijn de MultiLiners/PowerLiners aangesloten en gemeten?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="conMeasured" name="conMeasured" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->conMeasured) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->conMeasured == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->conMeasured == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="prepSat">Waren de voorbereidingen goed uitgevoerd?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="prepSat" name="prepSat" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->prepSat) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->prepSat == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->prepSat == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="workSat">Zijn de werkzaamheden naar tevredenheid uitgevoerd?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="workSat" name="workSat" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->workSat) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->workSat == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->workSat == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="answers">Zijn eventuele vragen beantwoord?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="answers" name="answers" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->answers) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->answers == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->answers == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="friendlyHelpfull">Waren de monteurs vriendelijk en behulpzaam?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="friendlyHelpfull" name="friendlyHelpfull" title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->friendlyHelpfull) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->friendlyHelpfull == '1' ? 'selected ' : '' ?>value="Ja">Ja</option>
                    <option <?= $oEvaluation->friendlyHelpfull == '0' ? 'selected ' : '' ?>value="Nee">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="grade">Welk cijfer (tussen 1 – 10) zou u OptiVolt geven?</label>
                <select class="form-control" <?= $oEvaluation->isEditable() ? '' : 'disabled="true"' ?> id="grade" name="grade" title="Selecteer een cijfer">
                    <option <?= empty($oEvaluation->grade) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                      ?>
                      <option <?= $oEvaluation->grade == $i ? 'selected ' : '' ?>value="<?=$i?>"><?=$i?></option>;
                      <?php
                    }
                    ?>
                    
                    
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
              <label for="remarks">Opmerkingen</label>
              <textarea name="remarks" <?= $oEvaluation->isEditable() ? '' : 'readonly' ?> id="remarks" class="form-control" rows="6"><?= _e($oEvaluation->remarks);?></textarea>
            </div>

            <?php } ?>
          </div>
          <div class="card-footer">
          <?php if (empty($oEvaluation->dateSend) && !$oEvaluation->digitalSigned) { ?>
            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
            <?php          
            if (empty($oEvaluation->dateSend) && (empty($oEvaluation->digitalSigned || $oEvaluation->digitalSigned==0))) { ?>
              <input type="submit" class="btn btn-primary" value="Opslaan & verzenden" name="save" />
            <?php }
            ?>
          <?php } ?>
           
          </div>
        </div>

      </div>
 
      <!--/.col (left) -->
      <?php 
      if ($oEvaluation->evaluationId) {
      ?>
      <div class="col-md-6">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Digitale handtekening</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <?php
            if ($oEvaluation->digitalSigned) {
            ?>
            
              <div class="form-group">
                <label for="nameSigned">Ondertekening</label>
                <input type="text" name="nameSigned" readonly class="form-control" id="nameSigned" value="<?= _e($oEvaluation->nameSigned) ?>" title="">                                    
              </div>
              <div class="form-group">
                <label for="dateSigned">Datum</label>
                <input type="text" name="nameSigned" readonly class="form-control" id="dateSigned" value="<?= $oEvaluation->dateSigned ? date('d-m-Y', strtotime($oEvaluation->dateSigned))  : '' ?>" title="">                                    
              </div>
            <?php 
            } else {
              ?>
              <?php 
                if (empty($oEvaluation->dateSend)) { 
                   echo '<div>Dit evaluatieformulier is nog niet verzonden.</div>'; 
                } else {
                  echo '<div>Dit evaluatieformulier is verzonden op ' . date('d-m-Y', strtotime($oEvaluation->dateSend)) . '.</div>'; 
                  if (!$oEvaluation->digitalSigned) {
                    echo '<div>Dit evaluatieformulier is nog niet ondertekend.</div>'; 
                  } 
                }
             
                
                


              ?>
              <?php
            }?>  
            </div>
            <div class="card-footer">
            <?php
              if ($oEvaluation->evaluationId) {                
                if (empty($oEvaluation->dateSend) && !$oEvaluation->digitalSigned) { ?>
                  <input type="submit" class="btn btn-primary"  value="Opslaan & verzenden" name="save" />
                <?php } else { 
                  if (!$oEvaluation->digitalSigned) {
                  ?>
                  <input type="submit" class="btn btn-primary" value="Opnieuw verzenden" name="save" />
                <?php 
                  }  
                }                            
              } 
              
              if ($oEvaluation->digitalSigned) {
                ?><input type="submit" class="btn btn-primary"  value="Exporteer als PDF" name="pdf" /> <?php
              }
              ?>

            </div>
            </div>
        </div>    
      </div>
      <?php 
      } ?>
    </div>
  </div>
</form>
<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
  <input type="hidden" value="save" name="action" />
  <input type="hidden" value="loginHash" name="<?= $oEvaluation->loginHash ?>" />

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
        
          
          <h1 class="m-0"><i aria-hidden="true" class="fa fa-star fa-th-star"></i>&nbsp;&nbsp;Evaluatie  
          <?php 
            if (!empty($oEvaluation->customerId)) {
              
              echo _e("- " . $oEvaluation->getCustomer()->companyName); 
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
        
         
            <div class="form-group">
                <label for="installSat">Is de installatie naar tevredenheid verlopen?</label>
                <select class="form-control" id="installSat" name="installSat" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->installSat) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->installSat == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->installSat == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="anyDetails">Zijn er nog bijzonderheden?</label>
                <select class="form-control" id="anyDetails" name="anyDetails" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->anyDetails) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->anyDetails == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->anyDetails == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="conMeasured">Zijn de MultiLiners/PowerLiners aangesloten en gemeten?</label>
                <select class="form-control" id="conMeasured" name="conMeasured" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->conMeasured) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->conMeasured == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->conMeasured == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="prepSat">Waren de voorbereidingen goed uitgevoerd?</label>
                <select class="form-control" id="prepSat" name="prepSat" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->prepSat) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->prepSat == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->prepSat == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="workSat">Zijn de werkzaamheden naar tevredenheid uitgevoerd?</label>
                <select class="form-control" id="workSat" name="workSat" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->workSat) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->workSat == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->workSat == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="answers">Zijn eventuele vragen beantwoord?</label>
                <select class="form-control" id="answers" name="answers" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->answers) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->answers == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->answers == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
                <label for="friendlyHelpfull">Waren de monteurs vriendelijk en behulpzaam?</label>
                <select class="form-control" id="friendlyHelpfull" name="friendlyHelpfull" required title="Selecteer een antwoord">
                    <option <?= empty($oEvaluation->friendlyHelpfull) ? 'selected ' : '' ?>value="">- Selecteer</option>
                    <option <?= $oEvaluation->friendlyHelpfull == '1' ? 'selected ' : '' ?>value="1">Ja</option>
                    <option <?= $oEvaluation->friendlyHelpfull == '0' ? 'selected ' : '' ?>value="0">Nee</option>
                </select>
                <span class="error invalid-feedback show"></span>
            </div>
            <div class="form-group">
              <label for="remarks">Opmerkingen</label>
              <textarea name="remarks" id="remarks" class="form-control" rows="6"><?= _e($oEvaluation->remarks);?></textarea>
            </div>

            
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
            if (!$oEvaluation->digitalSigned) {
            ?>
            
              <div class="form-group">
                <label for="nameSigned">Uw naam</label>
                <input type="text" name="nameSigned" class="form-control" id="nameSigned" value="<?= $oEvaluation->nameSigned ? _e($oEvaluation->nameSigned) : $oCustomer->contactPersonName ?>" title="">                                    
              </div>
              <div class="form-group">
                <label for="dateSigned">Datum</label>
                <div class="input-group date" id="dateSigned" data-target-input="nearest">
                  <input type="text" readonly class="form-control datetimepicker" name="dateSigned" data-target="#dateSigned" value="<?= $oEvaluation->dateSigned ? date('d-m-Y', strtotime($oEvaluation->dateSigned)) : date('d-m-Y', time()) ?>">
                  <div class="input-group-append" data-target="#dateSigned" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                </div>                
              </div>
            <?php 
            } ?>  
            </div>
            <div class="card-footer">
            <?php
              if ($oEvaluation->evaluationId) {                
                if (empty($oEvaluation->dateSend) && !$oEvaluation->digitalSigned) { ?>
                  <input type="submit" class="btn btn-primary"  value="Formulier ondertekenen en verzenden" name="save" />                
                <?php 
                 
                }                            
              } ?>

            </div>
            </div>
        </div>    
      </div>
      <?php 
      } ?>
    </div>
  </div>
</form>


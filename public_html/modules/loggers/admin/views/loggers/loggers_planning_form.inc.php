<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">

        <span class="float-sm-right">
          <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/planning">
            <button type="button" class="btn btn-default btn-sm" title="Naar overzicht">
              Naar overzicht
            </button>
          </a>

        </span>

        <h1 class="m-0"><i aria-hidden="true" class="fa fa-building fa-th-large"></i>&nbsp;&nbsp;Inplannen logger</h1>
      </div>
    </div>
  </div>
</div>


<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
  <input type="hidden" value="save" name="action" />

  <div class="container-fluid">

    <div class="row">
      <?= CSRFSynchronizerToken::field() ?>
      <input type="hidden" value="save" name="action" />
      <div class="card-body">

        <div class="form-group">
          <div class="row mb-2 pb-1">
            <div class="col-md-6">

              <label for="loggerId" style="font-size:14pt;"><span id="xSelected">0</span> Logger<?= ($oPlanning->planningId ? '' : '(s)') ?></label>
              <select class="custom-select rounded-0 select2" multiple="multiple" data-placeholder="Selecteer logger(s)" name="loggerId[]" class="form-control" id="loggerId" required>
                <option value="">Selecteer</option>
                <?php
                $iAmountLoggers = 0;
                foreach (LoggerManager::getLoggersOnlyByFilter() as $oPossibleLogger) {
                  $bSelected = false;
                  foreach ($aLoggers as $oLogger) {
                    if ($oLogger->loggerId == $oPossibleLogger->loggerId) {
                      $iAmountLoggers++;
                      $bSelected = true;
                    }
                  }
                ?>
                  <option <?= $bSelected ? 'selected ' : '' ?>value="<?= $oPossibleLogger->loggerId ?>"><?= $oPossibleLogger->name ?></option>
                <?php
                }
                ?>
              </select>


            </div>
          </div>

          <div class="row mb-2 pb-1" <?php if (http_get("param1") == 'planning-bewerken' && is_numeric(http_get("param2"))) {
                                        echo 'style="display:none;"';
                                      } ?>>
            <div class="col-md-12">
              <button type="button" id="autoSelect" class="btn btn-info btn-xs mb-1" title="Selecteer automatisch een aantal loggers op basis van ingevoerde gegevens">
                Selecteer
              </button> <select id="nrOfLoggers">
                <?php
                for ($i = 2; $i <= 20; $i++) {
                ?><option value="<?= $i ?>" <?= $i == $iAmountLoggers ? ' selected' : '' ?>><?= $i ?></option>
                <?php } ?>
              </select> loggers op basis van onderstaande gegevens

            </div>
          </div>

          <div class="row mb-2 pb-1">
            <div class="col-md-6 mb-6">

              <label for="klantId" class="pr-1" style="width:100%;"><a class="float-right" title="Klant toevoegen" href="/dashboard/klanten/toevoegen<?= http_get("param2") && substr_count(http_get("param2"), '_') == 1 ? '?pl=' . http_get("param2") : '' ?>"><button type="button" class="btn btn-default btn-xs" style="min-width:20px;">
                    <i class="fas fa-plus-circle "></i>
                  </button></a>Klant *</label>
              <select class="select2 form-control custom-select js-example-responsive" style="width:100%;" name="customerId" required>
                <option value="">Selecteer klant</option>
                <?php
                $sCustomer = '';
                foreach ($aAllCustomers as $oCustomer) {
                  if ($oPlanning->customerId && $oPlanning->customerId == $oCustomer->customerId) {
                    $sCustomer = $oCustomer->companyName;
                  }
                  echo '<option ' . ($oPlanning->customerId && $oPlanning->customerId == $oCustomer->customerId ? 'selected' : '') . ' value="' . $oCustomer->customerId . '">' . $oCustomer->companyName . '</option>';
                }
                ?>
              </select>


            </div>
          </div>
          <div class="row mb-2 pb-1">
            <div class="col-md-6 mb-6">

              <label for="comment">Opmerking</label>
              <input type="text" name="comment" class="form-control" id="comment" value="<?= _e($oPlanning->comment) ?>" title="Opmerking">

            </div>
          </div>

          <div class="row mb-2 pb-1">
            <div class="col mb-6">

              <label>Accountmanager(s)</label>

              <?php
              $aAccountmanagerIds = [];
              if (is_array($oPlanning->getAccountManagers())) {
                foreach ($oPlanning->getAccountManagers() as $oUser) {
                  $aAccountmanagerIds[] = $oUser->userId;
                }
              }

              foreach (UserManager::getAccountmanagers() as $oUser) {
              ?>
                <div class="form-check">
                  <input class="form-check-input" name="accountmanagers[]" value="<?= $oUser->userId ?>" id="user<?= $oUser->userId ?>" type="checkbox" <?= in_array($oUser->userId, $aAccountmanagerIds) ? 'checked=""' : '' ?>>
                  <label class="form-check-label" for="user<?= $oUser->userId ?>"><?= $oUser->name ?></label>
                </div>
              <?php
              }
              ?>
              </select>


            </div>
          </div>


          <div class="row mb-2 pb-1">
            <div class="col-md-2">
              <div class="form-group">
                <label>Datum</label>
                <div class="input-group date" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" name="startDate" id="startDate" data-target="#startDate" value="<?= date('d-m-Y', strtotime($oPlanning->startDate)) ?>" required />
                  <div class="input-group-append" data-target="#startDate" data-inputmask-inputformat="dd-mm-YYYY" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Aantal dagen</label>
                <select class="form-control" name="days" id="days">
                  <?php
                  $bSelected = false;
                  foreach (LoggersDefaultsManager::getLoggersDefaultsByFilter() as $oLoggersDay) {
                    if ($oLoggersDay->days == $oPlanning->days) {
                      $bSelected = true;
                    }
                    if ($iDays)
                  ?>
                    <option <?= $oLoggersDay->days == $oPlanning->days ? 'selected' : '' ?> value="<?= $oLoggersDay->days ?>"><?= _e($oLoggersDay->name) ?></option>
                  <?php
                  }
                  ?>
                  <option <?= $bSelected ? '' : 'selected' ?> value="-">Anders namelijk:</option>
                </select>
              </div>
              <div class="form-group" id="daysManual" style="display:none;">

                <input type="number" name="daystoo" class="form-control" id="daystoo" min="1" value="<?= $bSelected ? '' : $oPlanning->days ?>" title="Voer het aantal dagen in" data-msg="Voer het aantal dagen in">
              </div>

            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Kleur</label>
                <select class="form-control <?= $oPlanning->getColor() ?>" name="color" id="color">
                  <option value="">Automatisch</option>
                  <?php

                  for ($i = 1; $i <= 18; $i++) {
                    $bSelected = false;
                    if ($oPlanning->color == $i) {
                      $bSelected = true;
                    }
                  ?>
                    <option <?= $bSelected ? 'selected' : '' ?> class="soc-<?= $i ?>" value="<?= $i ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php
                  }
                  ?>

                </select>
              </div>


            </div>

          </div>


          <div class="callout " id="warning" style="display:none;">
            <p id="warning-content"></p>
          </div>

          <div class="row mb-2 pb-1">
            <div class="col-md-6">

              <div class="card-footer">
                <span class="float-right">
                  <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                      Terug
                    </button>
                  </a>
                </span>
                <?php if ($oPlanning->isEditable()) { ?>
                  <input type="submit" id="submitbutton" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
                <?php } ?>

                <?php if ($oPlanning->planningId && $oPlanning->isDeletable()) { ?>
                  <a class="btn btn-danger btn ml-5" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/planning-verwijderen/' . $oPlanning->planningId . '?' . CSRFSynchronizerToken::query() ?>" title="Verwijder planningsitem" onclick="return confirmChoice('planningsitem voor <?= $sCustomer ?>');">
                    <i class="fas fa-trash"></i>
                  </a>

                <?php } ?>
              </div>

            </div>
          </div>
        </div>



      </div>
    </div>
  </div>
</form>

<script src="<?= getAdminJs('loggers', 'loggers') ?>?t=<?= time() ?>"></script>
<?php



$iPlanningId = 'null';
if (http_get('param1') != 'inplannen' && http_get('param2')) {
  $iPlanningId = http_get('param2');
}
$sCurrentUrl = getCurrentUrl();
$sBottomJavascript = <<<EOT
<script type="text/javascript">

  var iPlanningId = $iPlanningId;
  var loggersSelectDisable = [];

  $(document).ready(function(){

    // Date picker
    $('#startDate').datetimepicker({
      format: 'DD-MM-YYYY'
    });

    // Color changer
    $("#color").change(function(){
      $('#color').attr('class', function(i, c){
        return c.replace(/(^|\s)soc-\S+/g, '');
      });
      $(this).addClass('soc-' + $(this).val());
      $(this).blur();
    });

    if ($('#days').val() == "-") {
        $('#daysManual').show();
    }
    $("#days").change(function(){
        if ($(this).val() != '-') {
          $('#daysManual').hide();
        } else {
          $('#daysManual').show();
          $('#daystoo').focus();
        }
        //checkIfPossible(iPlanningId);
        disableLoggers();
    });

    $("#startDate").on("change.datetimepicker", ({date, oldDate}) => {
        //checkIfPossible(iPlanningId);
        disableLoggers();
    });

    $("#daystoo").change(function(){
      if ($(this).val()<1) {
        $(this).val('');
      }
        //checkIfPossible(iPlanningId);
       disableLoggers();
    });

    $("#loggerId").change(function(){
       countSelected();
       checkIfPossible(iPlanningId);

    });


    countSelected();

    //checkIfPossible(iPlanningId);
    disableLoggers();

  });

//////////////
// DisableLoggers that are not available
//////////////
function disableLoggers() {


 var loggerRemoved = 0;
  console.log('disableLoggers()');

  // Reset loggersSelectDisable
  $("#loggerId option").each(function() {
      loggersSelectDisable[$(this).val()] = false;
  });

  // remove all disabled props
  $("#loggerId option").prop('disabled', false);

  var days = $('#days').val();
  if (days == '-') {
    days = $('#daystoo').val();
  }
  var startDate = $('#startDate').val();

  var postData = {};
  postData.ajaxCallLog = 1;
  postData.days = days;
  postData.startDate = startDate;
  postData.planningId = $iPlanningId;


  $.ajax('/dashboard/planning', {
      type: 'POST',  // http method

      data: postData,  // data to submit
      success: function(response){
          var result = $.parseJSON(response)

          $.each(result, function( index, value ) {
            //console.log(index + ' - ' + value);
            if (index>0) {
              if (value == 1) {
                $("#loggerId option[value='"+ index + "']").prop('disabled', true);

                if ($("#loggerId option[value='"+ index + "']").prop('selected') == true) {
                  $("#loggerId option[value='"+ index + "']").prop('selected', false);
                  addWarning('<li>Geselecteerde logger ' +  $("#loggerId option[value='"+ index + "']").html() + ' is niet beschikbaar in de gekozen periode en daarom uit de select verwijderd.</li>');
                  
                }
              } else {
                $("#loggerId option[value='"+ index + "']").prop('disabled', false);
                
              }
            }
            loggersSelectDisable[index] = value;



          });

          console.log(loggersSelectDisable);
          
          setTimeout(function() {
            $("#loggerId").change();
          checkIfPossible($iPlanningId);
        }, 2000);
          
      },
      error: function (jqXhr, textStatus, errorMessage) {
            alert('Error' + errorMessage);
      }
  });




}

//////////
// Autoselect loggers based on availability
//////////
$("#autoSelect").click(function(){

  $("#loggerId option").each(function() {
    $(this).prop('selected',false);
  });

  //$("#loggerId").trigger('change');
  //disableLoggers();

  var nrOfLoggers = $("#nrOfLoggers").val();
  var iCount = 0;
  var iIterrate = 0;

  //console.log('auto');
  //console.log(loggersSelectDisable);

  $("#loggerId option").each(function() {

    if (iIterrate>0) {

      var isDisabled = loggersSelectDisable[$(this).val()];

      //console.log($(this).val());
      //console.log(loggersSelectDisable);
      console.log($(this).val() + ' - ' + isDisabled);

      if (iCount<nrOfLoggers && !isDisabled ) {
          $(this).prop('selected',true);
          iCount++;
      }
    }
      iIterrate++;
  });

  $("#loggerId").change();

});


</script>

EOT;
$oPageLayout->addJavascript($sBottomJavascript);

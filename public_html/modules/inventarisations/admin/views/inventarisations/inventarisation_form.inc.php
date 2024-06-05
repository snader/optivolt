<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                

                <h1 class="m-0"><i aria-hidden="true" class="fa fa-check-double fa-th-large"></i>&nbsp;&nbsp;

                    Inventarisatie
                </h1>

                
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    <div class="row">
        
        <div class="col-md-12">
            <!-- form start -->
            <form method="POST" action="" class="validateForm" id="quickForm" autocomplete="off">
                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                <input type="hidden" value="save" name="action" />                
                                
                <!-- Card and header -->
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="card-title">
                                    <i aria-hidden="true" class="fa fa-check-double pr-1"></i>
                                    Checklist

                                </h3>
                            </div>
                            <div class="col-md-9" >
                               
                                <div class="row ">
                                    <div class="col-md-<?= ($oInventarisation->customerId ? 'style="12"' : '8') ?> form-group">
                                        <select class="form-control select2" name="customerId" id="customerId" style="width:100%;">
                                        <option value="">- Selecteer bestaande klant of &raquo; &raquo; &raquo; </option>
                                            <?php
                                            foreach ($aCustomers as $oCustomer) {
                                                echo "<option" . ($oInventarisation->customerId == $oCustomer->customerId ? ' selected=\'selected\'' : '') . " value='" . $oCustomer->customerId . "'>" . _e($oCustomer->companyName) . " (" . _e($oCustomer->debNr) . ")" . "</option>";
                                            }
                                            ?>                                    
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group" <?= ($oInventarisation->customerId ? 'style="display:none"' : '') ?>>
                                        <input  type="text" placeholder="Voer klantnaam in" id="customerName" name="customerName" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" required id="customerName" value="<?= _e($oInventarisation->customerName) ?>" title="Klant" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    </div>
                                </div>

                               
                            </div>
                        </div>


                    </div>
                    <div class="card-body">

                        <!-- first table -->
                        <div class="row">
                            <div class="col-sm-4 col-md-3 form-group">
                                <label for="name">Transformator naam/nr</label>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <label for="kva">KV/Ampere</label>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <label for="loggerId">Logger?</label>
                            </div>
                            <div class="col-sm-4 col-md-3 form-group">
                                <label for="position">Position, Foto#</label>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <label for="freeFieldAmp">Vrij veld + hoeveel Amp (NH0, NH1, NH3)?</label>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <label for="stroomTrafo">Stroomtrafo J/N</label>
                            </div>
                        </div>
                        <!-- first table row -->
                        <div id="addRowsHere">
                            <div class="row">
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input type="text" name="name" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="name" value="<?= _e($oInventarisation->name) ?>" title="Transformator naam/nr" required data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>  
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input type="number" name="kva" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="kva" value="<?= _e($oInventarisation->kva) ?>" title="KV/Ampere" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("kva") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <select class="form-control" id="loggerId" name="loggerId" title="Selecteer een logger">
                                        <option value="">- Kies</option>
                                        <?php
                                        foreach ($aLoggers as $oLogger) {
                                            echo "<option" . ($oInventarisation->loggerId == $oLogger->loggerId ? ' selected' : '') . ($oLogger->online ? '' : ' style=\'color:red;\'') . " value='" . $oLogger->loggerId . "'>" . $oLogger->name . "</option>";
                                        }
                                        ?>
                                    </select>                                    
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("loggerId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input type="text" name="position" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="position" value="<?= _e($oInventarisation->position) ?>" title="Positie" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("position") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-2 form-group">
                                    <input type="text" name="freeFieldAmp" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="freeFieldAmp" value="<?= _e($oInventarisation->freeFieldAmp) ?>" title="Vrij veld aanwezig + hoeveel Amp. (NH0, NH1, NH3)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("freeFieldAmp") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-2 form-group">
                                    <input type="text" name="stroomTrafo" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="stroomTrafo" value="<?= _e($oInventarisation->stroomTrafo) ?>" title="Stroomtrafo present?" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("stroomTrafo") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group-append" <?= !$oInventarisation->isEditable() ? 'style="display:none;"' : '' ?>>
                        <a class="addBtn" id="addRow" href="#" title="Regel toevoegen">
                            <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                            <i class="fas fa-plus-circle"></i>
                            </button>
                        </a><br />&nbsp;
                        </div>

                        <!-- rowToBeAdded to table one -->
                        <div style="display:none;" id="rowToBeAdded">
                        <div class="row">
                            <input type="hidden" value="" name="inventarisationIdExtra[]">
                            <span style="float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" class="removeRow"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
                            <div class="col-sm-4 col-md-3 form-group">
                                <input type="text" name="nameExtra[]" class="form-control" id="nameExtra[]" value="" title="Transformator naam/nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>  
                            <div class="col-sm-4 col-md-1 form-group">
                                <input type="number" name="kvaExtra[]" class="form-control" id="kvaExtra[]" value="" title="KV/Ampere" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <select class="form-control" id="loggerIdExtra[]" name="loggerIdExtra[]" title="Selecteer een logger">
                                    <option value="">- Kies</option>
                                    <?php
                                    foreach ($aLoggers as $oLogger) {
                                        echo "<option" . ($oLogger->online ? '' : ' style=\'color:red;\'') . " value='" . $oLogger->loggerId . "'>" . $oLogger->name . "</option>";
                                    }
                                    ?>
                                </select>                                    
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-3 form-group">
                                <input type="text" name="positionExtra[]" class="form-control" id="positionExtra[]" value="" title="Positie" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <input type="text" name="freeFieldAmpExtra[]" class="form-control" id="freeFieldAmpExtra[]" value="" title="Vrij veld aanwezig + hoeveel Amp. (NH0, NH1, NH3)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <input type="text" name="stroomTrafoExtra[]" class="form-control" id="stroomTrafoExtra[]" value="" title="Stroomtrafo present?" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                        </div>
                        </div>


                        <!-- second table -->
                        <div class="row">
                            <div class="col-sm-3 col-md-3 form-group">
                                <label for="type">Type engine</label>
                            </div>
                            <div class="col-sm-1 col-md-1 form-group">
                                <label for="control">Control</label>
                            </div>
                            <div class="col-sm-1 col-md-1 form-group">
                                <label for="relaisNr">Relais#</label>
                            </div>
                            <div class="col-sm-3 col-md-1 form-group">
                                <label for="engineKw">KW Engine+30kW</label>
                            </div>
                            <div class="col-sm-3 col-md-1 form-group">
                                <label for="turningHours">Turning hours</label>
                            </div>
                            <div class="col-sm-3 col-md-3 form-group">
                                <label for="photoNrs">Position, Foto#</label>
                            </div>
                            <div class="col-sm-3 col-md-1 form-group">
                                <label for="trafoNr">Trafo#</label>
                            </div>
                            <div class="col-sm-3 col-md-1 form-group">
                                <label for="mlProposed">ML Proposed</label>
                            </div>
                        </div>
                        <!-- second table row -->
                        <div id="addRowsHereSecond">
                            <div class="row">
                                <div class="col-sm-3 col-md-3 form-group">
                                    <input type="text" name="type" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="type" value="<?= _e($oInventarisation->type) ?>" title="Type engine (mixer, compressor..)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("type") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>  
                                <div class="col-sm-1 col-md-1 form-group">
                                <select class="form-control" id="control" name="control" title="Control">
                                        <option value="">- Kies</option>
                                        <option value="SD">SD</option>
                                        <option value="D">D</option>
                                        <option value="SS">SS</option>
                                        <option value="YY">YY</option>                                        
                                    </select>                                    
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("control") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                <input type="text" name="relaisNr" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="relaisNr" value="<?= _e($oInventarisation->relaisNr) ?>" title="Relais nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("relaisNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input type="text" name="engineKw" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="engineKw" value="<?= _e($oInventarisation->engineKw) ?>" title="KW engine +30 kW" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("engineKw") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input type="text" name="turningHours" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="turningHours" value="<?= _e($oInventarisation->turningHours) ?>" title="Turning hours" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("turningHours") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input type="text" name="photoNrs" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="photoNrs" value="<?= _e($oInventarisation->photoNrs) ?>" title="Position, Foto#" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("photoNrs") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                <input type="text" name="trafoNr" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="trafoNr" value="<?= _e($oInventarisation->trafoNr) ?>" title="Which trafo number?" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("trafoNr") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                <input type="text" name="mlProposed" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="mlProposed" value="<?= _e($oInventarisation->mlProposed) ?>" title="ML Proposed (3750 or 3300 ...)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("mlProposed") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group-append" <?= !$oInventarisation->isEditable() ? 'style="display:none;"' : '' ?>>
                        <a class="addBtn" id="addRowSecond" href="#" title="Regel toevoegen">
                            <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                            <i class="fas fa-plus-circle"></i>
                            </button>
                        </a><br />&nbsp;
                        </div>

                        <!-- rowToBeAdded to table two -->
                        <div style="display:none;" id="rowToBeAddedSecond">
                        <div class="row">
                            <input type="hidden" value="" name="inventarisationIdExtra[]">
                            <span style="float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" class="removeRowSecond"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
                            <div class="col-sm-3 col-md-3 form-group">
                                <input type="text" name="typeExtra[]" class="form-control" id="typeExtra[]" value="" title="Type engine (mixer, compressor..)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>  
                            <div class="col-sm-1 col-md-1 form-group">
                            <select class="form-control" id="controlExtra[]" name="controlExtra[]" title="Control">
                                    <option value="">- Kies</option>
                                    <option value="SD">SD</option>
                                    <option value="D">D</option>
                                    <option value="SS">SS</option>
                                    <option value="YY">YY</option>                                        
                                </select>                                    
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                            <input type="text" name="relaisNrExtra[]" class="form-control" id="relaisNrExtra[]" value="" title="Relais nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <input type="text" name="engineKwExtra[]" class="form-control" id="engineKwExtra[]" value="" title="KW engine +30 kW" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <input type="text" name="turningHoursExtra[]"  class="form-control" id="turningHoursExtra[]" value="" title="Turning hours" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-3 form-group">
                                <input type="text" name="photoNrsExtra[]"  class="form-control" id="photoNrsExtra[]" value="" title="Position, Foto#" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                            <input type="text" name="trafoNrExtra[]"  class="form-control" id="trafoNrExtra[]" value="" title="Which trafo number?" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                            <input type="text" name="mlProposedExtra[]"  class="form-control" id="mlProposedExtra[]" value="" title="ML Proposed (3750 or 3300 ...)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="remarks">Extra notes/remarks</label>
                            </div>
                            <div class="col-md-12 form-group">
                                <textarea name="remarks" id="remarks" class="form-control" rows="8"></textarea>
                            </div>
                        </div>
                        


                    </div>
                    <div class="card-footer">

                            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />&nbsp;
                            <input type="submit" class="btn btn-primary" value="Opslaan > Systemen" name="save" />
                        
                    </div>
                </div>
            </form>
        </div>


    </div>
</div>

<?php
$sBottomJavascript = <<<EOT
<script type="text/javascript">

$('#addRow').on( "click", function() {
    rowToBeAdded = $("#rowToBeAdded").html();
    
    countRows = $("#addRowsHere").children(".row").length + 1;
    aantalRows = "K" + countRows;
    rowToBeAdded = rowToBeAdded.replace("replace", aantalRows);

    $( "#addRowsHere" ).append( rowToBeAdded );
});
$(document).on("click", ".removeRow" , function() {
    $(this).parent().remove();
});

$('#addRowSecond').on( "click", function() {
    rowToBeAdded = $("#rowToBeAddedSecond").html();
    
    countRows = $("#addRowsHereSecond").children(".row").length + 1;
    aantalRows = "K" + countRows;
    rowToBeAdded = rowToBeAdded.replace("replace", aantalRows);

    $( "#addRowsHereSecond" ).append( rowToBeAdded );
});
$(document).on("click", ".removeRowSecond" , function() {
    $(this).parent().remove();
});


$('#customerId').on( "change", function() {
    if ($(this).val()!='') {
        $("input").prop('required',false);
        $('#customerName').hide();
    } else {
        $('#customerName').show();
        $("input").prop('required',true);
    }
});

</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
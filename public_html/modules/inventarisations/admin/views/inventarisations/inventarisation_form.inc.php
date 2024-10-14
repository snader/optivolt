<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm" autocomplete="off">
                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                <input type="hidden" value="save" name="action" />                
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">

                <h1 class="m-0"><i aria-hidden="true" class="fa fa-check-double fa-th-large"></i>&nbsp;&nbsp;
                    Inventarisatie
                </h1>
   
            </div>
            <div class="col-sm-4 form-group">
                <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>required class="form-control" name="userId" id="userId" style="width:100%;">
                    <option value="">- Selecteer uitvoerende </option>
                    <?php
                    foreach (UserManager::getUsersByFilter() as $oUser) {
                        if ($oUser->userId>1) {
                            echo "<option" . ($oInventarisation->userId == $oUser->userId ? ' selected=\'selected\'' : '') . " value='" . $oUser->userId . "'>" . _e($oUser->name) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-2 form-group">
                          
                <div class="input-group date" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker" <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>required id="created" name="created" data-target="#created" value="<?= !empty($oInventarisation->created) ? date('d-m-Y', strtotime($oInventarisation->created)) : '' ?>">
                <div class="input-group-append" data-target="#created" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">

    <div class="row">
        
        <div class="col-md-12">
            
                                
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
                                    
                                    <div class="col-md-<?= ($oInventarisation->customerId ? 'style="8"' : '4') ?> form-group">
                                        <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control select2" name="customerId" id="customerId" style="width:100%;">
                                        <option value="">- Selecteer bestaande klant of &raquo; &raquo; &raquo; </option>
                                            <?php
                                            foreach ($aCustomers as $oCustomer) {
                                                echo "<option" . ($oInventarisation->customerId == $oCustomer->customerId ? ' selected=\'selected\'' : '') . " value='" . $oCustomer->customerId . "'>" . _e($oCustomer->companyName) . " (" . _e($oCustomer->debNr) . ")" . "</option>";
                                            }
                                            ?>                                    
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group" <?= ($oInventarisation->customerId ? 'style="display:none"' : '') ?>>
                                        <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" placeholder="Voer klantnaam in" id="customerName" name="customerName"  class="form-control" <?= ($oInventarisation->customerId ? '' : 'required') ?> id="customerName" value="<?= _e($oInventarisation->customerName) ?>" title="Klant" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    </div>
                                </div>

                               
                            </div>
                        </div>


                    </div>
                    <div class="card-body">

                        <!-- first table -->
                        <div class="row">
                            <div class="col-sm-4 col-md-3 form-group">
                                <label>Transformator naam/nr</label>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <label>KV/Ampere</label>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <label>Logger?</label>
                            </div>
                            <div class="col-sm-4 col-md-3 form-group">
                                <label>Remark</label>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <label>Vrij veld?</label>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <label>Stroomtrafo?</label>
                            </div>
                        </div>
                        <!-- first table row -->
                        
                        <?php
                        // Table 1 - SUB systemReports here with parentID = $oInventarisation->inventarisationId
                        
                        if (isset($aInventarisations) && !empty($aInventarisations)) {

                            
                            foreach ($aInventarisations as $oSubInventarisation) {

                                // reset the array
                                $aSubFreeFieldAmpExtra = []; 
                              
                                if (empty($oSubInventarisation->name) && 
                                    empty($oSubInventarisation->kva) && 
                                    empty($oSubInventarisation->loggerId) && 
                                    empty($oSubInventarisation->position) && 
                                    empty($oSubInventarisation->freeFieldAmp) && 
                                    empty($oSubInventarisation->stroomTrafo) 
                                    ) 
                                {                  
                                                                                  
                                    continue;
                                }

                                $aSubFreeFieldAmpExtra[] = explode("|", $oSubInventarisation->freeFieldAmp ?? '');
                        ?>
                            
                            <div class="row">
                                <input type="hidden" value="<?= _e($oSubInventarisation->inventarisationId) ?>" name="inventarisationIdExtraTableOne[]">
                                <span style="<?= ($oInventarisation->isReadOnly() ? 'display:none; ' : '') ?>float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" class="removeRow" id="<?= _e($oSubInventarisation->inventarisationId) ?>"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input required <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="nameExtra[]" class="form-control"  value="<?= _e($oSubInventarisation->name) ?>" title="Transformator naam/nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>  
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input required <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="number" name="kvaExtra[]" class="form-control"  value="<?= _e($oSubInventarisation->kva) ?>" title="KV/Ampere" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <select required <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control" name="loggerIdExtra[]" title="Selecteer een logger">
                                        <option value="">- Kies</option>
                                        <?php
                                        foreach ($aLoggers as $oLogger) {
                                            echo "<option" . ($oSubInventarisation->loggerId == $oLogger->loggerId ? ' selected' : '') . ($oLogger->online ? '' : ' style=\'color:red;\'') . " value='" . $oLogger->loggerId . "'>" . $oLogger->name . "</option>";
                                        }
                                        ?>
                                    </select>                                    
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input required <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="positionExtra[]" class="form-control" value="<?= _e($oSubInventarisation->position) ?>" title="Remark" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-4 col-md-2 form-group">
                                    <?php
                                    $aOptions = ['NH0 160A','NH1 250A','NH2 400A','NH3 630A','MCCB'];
                                    foreach ($aSubFreeFieldAmpExtra as $aSubFreeFieldAmpExtraStr) {
                                        
                                    ?>
                                    <select required <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control freeFieldAmpExtra" name="freeFieldAmpExtra[]" style="width:100%;">
                                        <option value="">- Selecteer &raquo; </option> 
                                        <?php
                                        $bSelected = false;
                                        foreach ($aOptions as $sOption) { 
                                            if ($sOption!='-' && $aSubFreeFieldAmpExtraStr[0]==$sOption) {
                                                $bSelected = true;
                                            }
                                            ?>
                                            <option <?= $aSubFreeFieldAmpExtraStr[0]==$sOption ? 'selected ' : ''?>value='<?=$sOption?>'><?=$sOption?></option>
                                        <?php
                                        }
                                        
                                        ?>                                                                                   
                                        <option <?= !$bSelected ? 'selected' : '' ?> value='-'>Overig</option>                              
                                    </select>
                                                                        
                                    <input required <?= $bSelected ? 'style="display:none;" ' : '' ?><?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="freeFieldAmpExtra[]" class="form-control freeFieldAmpExtraOverig" value="<?= _e($aSubFreeFieldAmpExtraStr[0]) ?>" title="Vrij veld aanwezig + hoeveel Amp. (NH0, NH1, NH3)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    
                                    <?php 
                                } ?>
                                </div>
                                <div class="col-sm-4 col-md-2 form-group">
                                    <select required <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control" name="stroomTrafoExtra[]" title="Stroomtrafo beschikbaar?">
                                        <option value="">- Kies</option>
                                        <option <?= $oSubInventarisation->stroomTrafo == "J" ? 'selected' : ''?> value="J">Ja</option>
                                        <option <?= $oSubInventarisation->stroomTrafo == "N" ? 'selected' : ''?> value="N">Nee</option>
                                        <option <?= $oSubInventarisation->stroomTrafo == "NVT" ? 'selected' : ''?> value="NVT">NVT</option>                                        
                                    </select>                                    
                                    <span class="error invalid-feedback show"></span>
                                </div>
                            
                            </div>
                        <?php
                            }
                        }
                        ?>    

                        <div id="addRowsHere"></div>
                        <div class="input-group-append" <?= $oInventarisation->isReadOnly() ? 'style="display:none;"' : '' ?>>
                        <a class="addBtn" id="addRow" href="#" title="Regel toevoegen">
                            <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                            <i class="fas fa-plus-circle"></i>
                            </button>
                        </a><br />&nbsp;
                        </div>

                        <!-- rowToBeAdded to table one -->
                        <div style="display:none;" id="rowToBeAdded">
                            <div class="row">
                                <input type="hidden" value="" name="inventarisationIdExtraTableOne[]">
                                <span style="float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" class="removeRow"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="nameExtra[]" class="form-control" value="" title="Transformator naam/nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>  
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="number" name="kvaExtra[]" class="form-control" value="" title="KV/Ampere" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control" name="loggerIdExtra[]" title="Selecteer een logger">
                                        <option value="">- Kies</option>
                                        <?php
                                        foreach ($aLoggers as $oLogger) {
                                            echo "<option" . ($oLogger->online ? '' : ' style=\'color:red;\'') . " value='" . $oLogger->loggerId . "'>" . $oLogger->name . "</option>";
                                        }
                                        ?>
                                    </select>                                    
                                    
                                </div>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="positionExtra[]" class="form-control" value="" title="Remark" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    
                                </div>
                                
                                <div class="col-sm-3 col-md-2 form-group" >  
                                                             
                                    <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control freeFieldAmpExtra" name="freeFieldAmpExtra[]" style="width:100%;">
                                        <option value="">- Selecteer &raquo; </option>                                            
                                        <option value="NH0 160A">NH0 160A</option>
                                        <option value="NH1 250A">NH1 250A</option>  
                                        <option value="NH2 400A">NH2 400A</option>
                                        <option value="NH3 630A">NH3 630A</option>
                                        <option value="MCCB">MCCB</option>  
                                        <option value="-">Overig</option>                              
                                    </select>
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="freeFieldAmpExtra[]" style="display:none;" class="form-control freeFieldAmpExtraOverig" value="" title="Vrij veld aanwezig + hoeveel Amp. (NH0, NH1, NH3)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="addhere"></span>
                                    <span class="addFreeFieldAmp" style="float:left;">+</span>
                                </div>
                                
                                <div class="col-sm-3 col-md-2 form-group">
                                    <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control" name="stroomTrafoExtra[]" title="Stroomtrafo beschikbaar?">
                                        <option value="">- Kies</option>
                                        <option <?= $oInventarisation->stroomTrafo == "J" ? 'selected' : ''?> value="J">Ja</option>
                                        <option <?= $oInventarisation->stroomTrafo == "N" ? 'selected' : ''?> value="N">Nee</option>
                                        <option <?= $oInventarisation->stroomTrafo == "NVT" ? 'selected' : ''?> value="NVT">NVT</option>                                        
                                    </select>                                             
                                    
                                </div>
                            </div>
                        </div>
                        <div style="display:none;" >
                            <div class="col-sm-3 col-md-2 form-group" id="freeFieldAmpDiv">
                                <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control freeFieldAmpExtra" name="freeFieldAmpExtra[]" style="width:100%;">
                                    <option value="">- Selecteer &raquo; </option>                                            
                                    <option value="NH0 160A">NH0 160A</option>
                                    <option value="NH1 250A">NH1 250A</option>  
                                    <option value="NH2 400A">NH2 400A</option>
                                    <option value="NH3 630A">NH3 630A</option>
                                    <option value="MCCB">MCCB</option>  
                                    <option value="-">Overig</option>                              
                                </select>
                                <input type="text" name="freeFieldAmpExtra[]" style="display:none;" class="form-control freeFieldAmpExtraOverig" value="" title="Vrij veld aanwezig + hoeveel Amp. (NH0, NH1, NH3)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                
                            </div>
                        </div>

                        <!-- **************************************************************************** -->
                         <!-- **************************************************************************** -->
                          <!-- **************************************************************************** -->

                        <!-- second table -->
                        <div class="row">
                            <div class="col-sm-3 col-md-3 form-group">
                                <label>Type engine</label>
                            </div>
                            <div class="col-sm-1 col-md-1 form-group">
                                <label>Control</label>
                            </div>
                            <div class="col-sm-1 col-md-1 form-group">
                                <label>Relais#</label>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <label>KW&nbsp;Engine+30kW</label>
                            </div>
                            <div class="col-sm-2 col-md-1 form-group">
                                <label>Turning hours</label>
                            </div>
                            <div class="col-sm-3 col-md-3 form-group">
                                <label>Remark</label>
                            </div>
                            <div class="col-sm-3 col-md-1 form-group">
                                <label>Trafo#</label>
                            </div>
                        
                        </div>
                        <!-- second table first row -->
                                                
                        <?php
                        // Table 2 - SUB systemReports here with parentID = $oInventarisation->inventarisationId
                        if (isset($aInventarisations) && !empty($aInventarisations)) {

                            foreach ($aInventarisations as $oSubInventarisation) {

                                if (empty($oSubInventarisation->type) && 
                                    empty($oSubInventarisation->control) && 
                                    empty($oSubInventarisation->relaisNr) && 
                                    empty($oSubInventarisation->engineKw) && 
                                    empty($oSubInventarisation->turningHours) && 
                                    empty($oSubInventarisation->photoNrs) && 
                                    empty($oSubInventarisation->trafoNr) 

                                    ) {
                                        continue;
                                    }
                        ?>
                            
                            <div class="row">
                                <input type="hidden" value="<?= _e($oSubInventarisation->inventarisationId) ?>" name="inventarisationIdExtraTableTwo[]">
                                <span style="float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" id="<?= _e($oSubInventarisation->inventarisationId) ?>" class="removeRow"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
                                <div class="col-sm-3 col-md-3 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="typeExtra[]" class="form-control" id="typeExtra[]" value="<?= _e($oSubInventarisation->type) ?>" title="Type engine (mixer, compressor..)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>  
                                <div class="col-sm-1 col-md-1 form-group">
                                <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control" id="controlExtra[]" name="controlExtra[]" title="Control">
                                        <option value="">- Kies</option>
                                        <option<?= ($oInventarisation->control == 'SD' ? ' selected' : '') ?> value="SD">SD</option>
                                        <option<?= ($oInventarisation->control == 'D' ? ' selected' : '') ?>  value="D">D</option>
                                        <option<?= ($oInventarisation->control == 'SS' ? ' selected' : '') ?>  value="SS">SS</option>
                                        <option<?= ($oInventarisation->control == 'YY' ? ' selected' : '') ?>  value="YY">YY</option>                                        
                                    </select>                                    
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-1 col-md-1 form-group">
                                <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="relaisNrExtra[]" class="form-control" id="relaisNrExtra[]" value="<?= _e($oSubInventarisation->relaisNr) ?>" title="Relais nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="engineKwExtra[]" class="form-control" id="engineKwExtra[]" value="<?= _e($oSubInventarisation->engineKw) ?>" title="KW engine +30 kW" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-3 col-md-1 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="turningHoursExtra[]"  class="form-control" id="turningHoursExtra[]" value="<?= _e($oSubInventarisation->turningHours) ?>" title="Turning hours" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-2 col-md-3 form-group">
                                    <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="photoNrsExtra[]"  class="form-control" id="photoNrsExtra[]" value="<?= _e($oSubInventarisation->photoNrs) ?>" title="Remark" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                <div class="col-sm-2 col-md-1 form-group">
                                <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="trafoNrExtra[]"  class="form-control" id="trafoNrExtra[]" value="<?= _e($oSubInventarisation->trafoNr) ?>" title="Which trafo number?" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                    <span class="error invalid-feedback show"></span>
                                </div>
                                
                            
                            </div>
                        <?php
                            }
                        }
                        ?> 

                        <div id="addRowsHereSecond"></div>
                        <div class="input-group-append" <?= $oInventarisation->isReadOnly() ? 'style="display:none;"' : '' ?>>
                        <a class="addBtn" id="addRowSecond" href="#" title="Regel toevoegen">
                            <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                            <i class="fas fa-plus-circle"></i>
                            </button>
                        </a><br />&nbsp;
                        </div>

                        <!-- rowToBeAdded to table two -->
                        <div style="display:none;" id="rowToBeAddedSecond">
                        <div class="row">
                            <input type="hidden" value="" class="inventarisationIdExtraTableTwo" name="inventarisationIdExtraTableTwo[]">
                            <span style="float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" class="removeRowSecond"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
                            <div class="col-sm-3 col-md-3 form-group">
                                <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="typeExtra[]" class="form-control" id="typeExtra[]" value="" title="Type engine (mixer, compressor..)" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>  
                            <div class="col-sm-1 col-md-1 form-group">
                            <select <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>class="form-control" id="controlExtra[]" name="controlExtra[]" title="Control">
                                    <option value="">- Kies</option>
                                    <option value="SD">SD</option>
                                    <option value="D">D</option>
                                    <option value="SS">SS</option>
                                    <option value="YY">YY</option>                                        
                                </select>                                    
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                            <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="relaisNrExtra[]" class="form-control" id="relaisNrExtra[]" value="" title="Relais nr" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="engineKwExtra[]" class="form-control" id="engineKwExtra[]" value="" title="KW engine +30 kW" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                                <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="turningHoursExtra[]"  class="form-control" id="turningHoursExtra[]" value="" title="Turning hours" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-3 form-group">
                                <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="photoNrsExtra[]"  class="form-control" id="photoNrsExtra[]" value="" title="Remark" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            <div class="col-sm-4 col-md-1 form-group">
                            <input <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>type="text" name="trafoNrExtra[]"  class="form-control" id="trafoNrExtra[]" value="" title="Which trafo number?" data-msg="<?= sysTranslations::get('global_field_not_completed') ?>">
                                <span class="error invalid-feedback show"></span>
                            </div>
                            
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="remarks">Extra notes/remarks</label>
                            </div>
                            <div class="col-md-12 form-group">
                                <textarea <?= ($oInventarisation->isReadOnly() ? 'readonly disabled ' : '') ?>name="remarks" id="remarks" class="form-control" rows="6"></textarea>
                            </div>
                        </div>                      
                    </div>
                    <div class="card-footer">
                        <?php
                        if (!$oInventarisation->isReadOnly()) { ?>
                            <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />&nbsp;
                            <input type="submit" class="btn btn-primary" value="Opslaan > PDF" name="save" />&nbsp;
                            <input type="submit" class="btn btn-primary" value="Opslaan > Overzicht" name="save" />    
                            <?php
                        }
                            ?>                    
                    </div>
                </div>
            
        </div>


    </div>
</div>
</form>
<div class="modal fade" id="modal-record-del">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Let op!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Weet je zeker dat je deze regel wilt verwijderen?</strong><br />(de regel wordt - indien eerder opgeslagen - direct uit de database verwijderd)</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nee</button>
                <button type="button" class="btn btn-primary" id="do-finish-del">Ja, verwijderen</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php
$sBottomJavascript = <<<EOT
<script type="text/javascript">

$('#created').datetimepicker({
    format: 'DD-MM-YYYY'
}); 

$('#created').datetimepicker({
    format: 'DD-MM-YYYY'
}); 


$('#addRow').on( "click", function(event){
    rowToBeAdded = $("#rowToBeAdded").html();
    
    countRows = $("#addRowsHere").children(".row").length + 1;
    aantalRows = "K" + countRows;
    rowToBeAdded = rowToBeAdded.replace("replace", aantalRows);
    
    $( "#addRowsHere" ).append( rowToBeAdded );

    $('input:visible, select:visible').each(function() {
        $(this).attr('required', true);
    });
    
});


$(document).on("click", ".addFreeFieldAmp" , function() {
var freeFieldAmpDiv = $('#freeFieldAmpDiv').html();    
    $(this).parent().find('.addhere').append( freeFieldAmpDiv );

});

$(document).on("click", ".removeRow" , function() {
        therow = $(this);       
        therowId = therow.attr('id')       
        event.preventDefault();
        $('#modal-record-del').modal('show');
        $('#do-finish-del').on( "click", function() {

            if (therowId>1) {
                // delete database record
    
                $.ajax('/dashboard/inventarisations/deleterow', {
                    type: 'POST',  // http method
                    data: { therowId: therowId },  // data to submit
                    success: function (data, status, xhr) {
                        $('#modal-record-del').modal('hide');           
                        therow.parent().remove();
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                            alert("Something went wrong...");
                    }
                });      
            } else {

                // delete a record that has not yet been saved
                $('#modal-record-del').modal('hide');           
                therow.parent().remove();
            }           


            
        });

    
});

$('#addRowSecond').on( "click", function(event){
    rowToBeAdded = $("#rowToBeAddedSecond").html();
    
    countRows = $("#addRowsHereSecond").children(".row").length + 1;
    aantalRows = "K" + countRows;
    rowToBeAdded = rowToBeAdded.replace("replace", aantalRows);

    $( "#addRowsHereSecond" ).append( rowToBeAdded );
});


$(document).on("click", ".removeRowSecond" , function() {
        therow = $(this);
        event.preventDefault();
        $('#modal-record-del').modal('show');
        $('#do-finish-del').on( "click", function() {
            $('#modal-record-del').modal('hide');                
            therow.parent().remove();
        });
    
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

$(document).on("change", ".freeFieldAmpExtra", function(event) {

    elementOverig = $(this).next(".freeFieldAmpExtraOverig");

    if ($(this).val()!='-') {
        elementOverig.val('').prop('required',false).hide();        
    } else {
        elementOverig.val('').prop('required',true).show();
    }
});


</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);

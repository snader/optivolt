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
        <!-- left column -->
        <div class="col-md-12">
            <!-- form start -->
            <form method="POST" action="" class="validateForm" id="quickForm" autocomplete="off">
                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                <input type="hidden" value="save" name="action" />
                <input type="hidden" value="" name="systemNotice" id="systemNotice" />
                <input type="hidden" value="1" name="online" />
                

                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">
                                    <i aria-hidden="true" class="fa fa-check-double pr-1"></i>
                                    Checklist

                                </h3>
                            </div>
                            <div class="col-md-6 ">
                                <h3 class="card-title float-right">
                                    info indien ingevuld en opgeslagen
                                </h3>
                            </div>
                        </div>


                    </div>
                    <div class="card-body">

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
                                <label for="position">Position</label>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <label for="freeFieldAmp">Vrij veld + hoeveel Amp (NH0, NH1, NH3)?</label>
                            </div>
                            <div class="col-sm-4 col-md-2 form-group">
                                <label for="stroomTrafo">Stroomtrafo J/N</label>
                            </div>
                        </div>
                        <div id="addRowsHere">
                            <div class="row">
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input type="text" name="name" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="name" value="<?= _e($oInventarisation->name) ?>" title="Transformator naam/nr" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>  
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input type="text" name="kva" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="kva" value="<?= _e($oInventarisation->kva) ?>" title="KV/Ampere" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("kva") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-1 form-group">
                                    <input type="text" name="loggerId" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="loggerId" value="<?= _e($oInventarisation->loggerId) ?>" title="Logger">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("loggerId") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-3 form-group">
                                    <input type="text" name="position" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="position" value="<?= _e($oInventarisation->position) ?>" title="Positie" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("position") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-2 form-group">
                                    <input type="text" name="freeFieldAmp" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="freeFieldAmp" value="<?= _e($oInventarisation->freeFieldAmp) ?>" title="Vrij veld aanwezig + hoeveel Amp. (NH0, NH1, NH3)" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("freeFieldAmp") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
                                <div class="col-sm-4 col-md-2 form-group">
                                    <input type="text" name="stroomTrafo" <?= !$oInventarisation->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="stroomTrafo" value="<?= _e($oInventarisation->stroomTrafo) ?>" title="Stroomtrafo present?" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                                    <span class="error invalid-feedback show"><?= $oInventarisation->isPropValid("stroomTrafo") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                                </div>
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
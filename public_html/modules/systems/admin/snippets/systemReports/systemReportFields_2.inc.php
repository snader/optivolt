<?php

/**
 * MultiLINER
 */
$sImageTitleSelect = '';
?>
<div class="row">
  <div class="col-sm-3 form-group">
    <label for="title">#</label>
  </div>
  <div class="col-sm-3 form-group">
    <label for="title">Fase 1 *</label>
  </div>
  <div class="col-sm-3 form-group">
    <label for="title">Fase 2 *</label>
  </div>
  <div class="col-sm-3 form-group">
    <label for="title">Fase 3 *</label>
  </div>
</div>

<div id="addRowsHere">
  <div class="row">
    <div class="col-sm-3 form-group">
      <input type="text" name="columnA" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="columnA" value="<?= empty($oSystemReport->columnA) ? '' : _e($oSystemReport->columnA) ?>" title="#" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("columnA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <?php $sImageTitleSelect .= '<option>' . _e($oSystemReport->columnA) . '</option>'; ?>
    <div class="col-sm-3 form-group">
      <input type="number" step="0.1" name="faseA" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseA" value="<?= _e($oSystemReport->faseA) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-3 form-group">
      <input type="number" step="0.1" name="faseB" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseB" value="<?= _e($oSystemReport->faseB) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseB") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-3 form-group">
      <input type="number" step="0.1" name="faseC" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseC" value="<?= _e($oSystemReport->faseC) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseC") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
  </div>
  <?php
  // SUB systemReports here with parentID = $oSystemReport->systemReportId
  if (isset($aSubSystemReports) && !empty($aSubSystemReports)) {

    foreach ($aSubSystemReports as $oSubSystemReport) {
  ?>
      <div class="row">
        <input type="hidden" value="<?= _e($oSubSystemReport->systemReportId) ?>" name="systemReportIdExtra[]">
        <div class="col-sm-3 form-group">
          <input type="text" name="columnAextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="columnAextra[]" value="<?= _e($oSubSystemReport->columnA) ?>" title="#" data-msg=" <?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("columnAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <?php if (!empty($oSubSystemReport->columnA)) {
          $sImageTitleSelect .= '<option>' . _e($oSubSystemReport->columnA) . '</option>';
        } ?>
        <div class="col-sm-3 form-group">
          <input type="number" step="0.1" name="faseAextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseAextra[]" value="<?= _e($oSubSystemReport->faseA) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseAextra") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-3 form-group">
          <input type="number" step="0.1" name="faseBextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseBextra[]" value="<?= _e($oSubSystemReport->faseB) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseBextra") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-3 form-group">
          <input type="number" step="0.1" name="faseCextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseCextra[]" value="<?= _e($oSubSystemReport->faseC) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseCextra") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
      </div>
  <?php
    }
  }

  ?>
</div>
<div class="input-group-append" <?= !$oSystemReport->isEditable() ? 'style="display:none;"' : '' ?>>
  <a class="addBtn" id="addRow" href="#" title="Meting toevoegen">
    <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
      <i class="fas fa-plus-circle"></i>
    </button>
  </a>&nbsp;
</div>


<!-- rowToBeAdded -->
<div style="display:none;" id="rowToBeAdded">
  <div class="row">
    <input type="hidden" value="" name="systemReportIdExtra[]">
    <span style="float:left;position:absolute;margin: 10px 0px 0px -8px;font-size:12px;" class="removeRow"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
    <div class="col-sm-3 form-group">
      <input type="text" name="columnAextra[]" class="form-control" id="columnAextra[]" value="replace" title="#" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("columnAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-3 form-group">
      <input type="number" step="0.1" name="faseAextra[]" class="form-control" id="faseAextra[]" value="" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-3 form-group">
      <input type="number" step="0.1" name="faseBextra[]" class="form-control" id="faseBextra[]" value="" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseBextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-3 form-group">
      <input type="number" step="0.1" name="faseCextra[]" class="form-control" id="faseCextra[]" value="" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseCextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
  </div>
</div>


<?php

$sImageTitleSelect = '<select id="imageTitle_1" class="form-control" name="title">' . $sImageTitleSelect . '</select>';
$sNotCompleteWarning = sysTranslations::get('global_field_not_completeds');

$sBottomJavascript = <<<EOT
<script type="text/javascript">

$('#addRow').on( "click", function() {
    rowToBeAdded = $("#rowToBeAdded").html();
    rowToBeAdded = rowToBeAdded.replace('class="form-control"', 'class="form-control" required data-msg="$sNotCompleteWarning"');

    countRows = $("#addRowsHere").children(".row").length + 1;
    aantalRows = "K" + countRows;
    rowToBeAdded = rowToBeAdded.replace("replace", aantalRows);

    $( "#addRowsHere" ).append( rowToBeAdded );
});

$(document).on("click", ".removeRow" , function() {
    $(this).parent().remove();
});

$('#imageTitle_1').replaceWith('$sImageTitleSelect');

</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
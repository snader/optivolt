<?php

/**
 * V-LINER
 */
$sImageTitleSelect = '';
?>

<div id="addRowsHere">
  <div class="row" style="border-bottom:1px solid #ddd;margin-bottom:15px;">
    <div class="col-md-12 form-group">
      <label for="title">Primair/Secundair *</label>
      <select type="text" name="columnA" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="columnA" value="<?= _e($oSystemReport->columnA) ?>" title="K#" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
        <option <?= $oSystemReport->columnA == 'Primair' ? 'selected' : '' ?>>Primair</option>
        <option <?= $oSystemReport->columnA == 'Secundair' ? 'selected' : '' ?>>Secundair</option>
      </select>
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("columnA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <?php $sImageTitleSelect .= '<option>' . _e($oSystemReport->columnA) . '</option>'; ?>
    <div class="col-sm-4 form-group">
      <label for="title">L1/L2 *</label>
      <input type="number" step="0.1" name="faseA" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseA" value="<?= _e($oSystemReport->faseA) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L1/L3 *</label>
      <input type="number" step="0.1" name="faseB" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseB" value="<?= _e($oSystemReport->faseB) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseB") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L2/L3 *</label>
      <input type="number" step="0.1" name="faseC" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseC" value="<?= _e($oSystemReport->faseC) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseC") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L1/PE *</label>
      <input type="number" step="0.1" name="faseD" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseD" value="<?= _e($oSystemReport->faseD) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseD") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L2/PE *</label>
      <input type="number" step="0.1" name="faseE" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseE" value="<?= _e($oSystemReport->faseE) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseE") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L3/PE *</label>
      <input type="number" step="0.1" name="faseF" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseF" value="<?= _e($oSystemReport->faseF) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseF") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
  </div>
  <?php
  // SUB systemReports here with parentID = $oSystemReport->systemReportId
  if (isset($aSubSystemReports) && !empty($aSubSystemReports)) {

    foreach ($aSubSystemReports as $oSubSystemReport) {
  ?>
      <div class="row" style="border-bottom:1px solid #ddd;margin-bottom:15px;">
        <input type="hidden" value="<?= _e($oSubSystemReport->systemReportId) ?>" name="systemReportIdExtra[]">
        <div class="col-md-12 form-group">
          <label for="title">Primair/Secundair *</label>
          <select type="text" name="columnAextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="columnAextra[]" title="Primair/secundair" required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
            <option <?= $oSubSystemReport->columnA == 'Primair' ? 'selected' : '' ?>>Primair</option>
            <option <?= $oSubSystemReport->columnA == 'Secundair' ? 'selected' : '' ?>>Secundair</option>
          </select>
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("columnAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <?php if (!empty($oSubSystemReport->columnA)) {
          $sImageTitleSelect .= '<option>' . _e($oSubSystemReport->columnA) . '</option>';
        } ?>
        <div class="col-sm-4 form-group">
          <label for="title">L1/L2 *</label>
          <input type="number" step="0.1" name="faseAextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseAextra[]" value="<?= _e($oSubSystemReport->faseA) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-4 form-group">
          <label for="title">L1/L3 *</label>
          <input type="number" step="0.1" name="faseBextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseBextra[]" value="<?= _e($oSubSystemReport->faseB) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseBextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-4 form-group">
          <label for="title">L2/L3 *</label>
          <input type="number" step="0.1" name="faseCextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseCextra[]" value="<?= _e($oSubSystemReport->faseC) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseCextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-4 form-group">
          <label for="title">L1/PE *</label>
          <input type="number" step="0.1" name="faseDextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseDextra[]" value="<?= _e($oSubSystemReport->faseD) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseDextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-4 form-group">
          <label for="title">L2/PE *</label>
          <input type="number" step="0.1" name="faseEextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseEextra[]" value="<?= _e($oSubSystemReport->faseE) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseEextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
        </div>
        <div class="col-sm-4 form-group">
          <label for="title">L3/PE *</label>
          <input type="number" step="0.1" name="faseFextra[]" <?= !$oSubSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseFextra[]" value="<?= _e($oSubSystemReport->faseF) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
          <span class="error invalid-feedback show"><?= $oSubSystemReport->isPropValid("faseFextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
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

  <div class="row" style="border-bottom:1px solid #ddd;margin-bottom:15px;">
    <input type="hidden" value="" name="systemReportIdExtra[]">
    <span style="float:left;position:absolute;margin: 4px 0px 0px -8px;font-size:12px;" class="removeRow"><a href="#"><i class="fas fa-minus-circle"></i></a>&nbsp;</span>
    <div class="col-md-12 form-group">
      <label for="title">Primair/Secundair *</label>
      <select type="text" name="columnAextra[]" class="form-control" id="columnAextra[]" title="" data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
        <option>Primair</option>
        <option>Secundair</option>
      </select>
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("columnAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L1/L2 *</label>
      <input type="number" step="0.1" name="faseAextra[]" class="form-control" id="faseAExtra[]" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseAextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L1/L3 *</label>
      <input type="number" step="0.1" name="faseBextra[]" class="form-control" id="faseBExtra[]" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseBextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L2/L3 *</label>
      <input type="number" step="0.1" name="faseCextra[]" class="form-control" id="faseCExtra[]" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseCextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L1/PE *</label>
      <input type="number" step="0.1" name="faseDextra[]" class="form-control" id="faseDextra[]" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseDextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L2/PE *</label>
      <input type="number" step="0.1" name="faseEextra[]" class="form-control" id="faseEextra[]" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseEExtra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
    </div>
    <div class="col-sm-4 form-group">
      <label for="title">L3/PE *</label>
      <input type="number" step="0.1" name="faseFextra[]" class="form-control" id="faseFextra[]" title="Voer het resultaat in. Gebruik een punt als decimaal teken." data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
      <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseFextra[]") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
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
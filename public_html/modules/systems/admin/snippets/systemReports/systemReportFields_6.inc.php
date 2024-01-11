<?php

/**
 * Actief Harmonisch Filter (AHF)
 */

?>

<div class="form-group">
  <label for="title">Fase 1 *</label>
  <input type="number" step="0.1" name="faseA" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseA" value="<?= _e($oSystemReport->faseA) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
  <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
</div>
<div class="form-group">
  <label for="title">Fase 2 *</label>
  <input type="number" step="0.1" name="faseB" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseB" value="<?= _e($oSystemReport->faseB) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
  <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseA") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
</div>
<div class="form-group">
  <label for="title">Fase 3 *</label>
  <input type="number" step="0.1" name="faseC" <?= !$oSystemReport->isEditable() ? 'readonly disabled ' : '' ?> class="form-control" id="faseC" value="<?= _e($oSystemReport->faseC) ?>" title="Voer het resultaat in. Gebruik een punt als decimaal teken." required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
  <span class="error invalid-feedback show"><?= $oSystemReport->isPropValid("faseC") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
</div>
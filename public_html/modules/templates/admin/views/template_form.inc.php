<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <h1 class="m-0"><i aria-hidden="true" class="fas fa-envelope"></i>&nbsp;&nbsp;Templates</h1>
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
          <h3 class="card-title"><i class="fas fa-envelope pr-1"></i> E-mail template</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" class="validateForm" id="quickForm">
          <?= CSRFSynchronizerToken::field() ?>
          <input type="hidden" value="save" name="action" />
          <?php if (is_numeric($oTemplate->templateId)) { ?>
            <input type="hidden" value="<?= $oTemplate->type ?>" name="type"/>
            <input type="hidden" value="<?= $oTemplate->templateGroupId ?>" name="templateGroupId"/>
          <?php } ?>
          <div class="card-body">

           <div class="form-group">
              <label for="description"><?= sysTranslations::get('global_message') ?> *</label>
              <input type="text" name="description" class="form-control" id="description" value="<?= $oTemplate->description ?>" title="<?= sysTranslations::get('global_message') ?>" required data-msg="<?= sysTranslations::get('global_message') ?>">
              <span class="error invalid-feedback show"><?= $oTemplate->isPropValid("description") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
            <div class="form-group">
              <label for="subject"><?= sysTranslations::get('templates_topic') ?> *</label>
              <input type="text" name="subject" class="form-control" id="subject" value="<?= $oTemplate->subject ?>" title="<?= sysTranslations::get('templates_topic') ?>" required data-msg="<?= sysTranslations::get('templates_topic') ?>">
              <span class="error invalid-feedback show"><?= $oTemplate->isPropValid("subject") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
            <div class="form-group">
              <label for="template"><?= sysTranslations::get('templates_topic') ?> *</label>
              <div>
              <textarea name="template" id="template" class="tiny_MCE_default tiny_MCE template_email"><?= $oTemplate->template ?></textarea>
            </div>
              <span class="error invalid-feedback show"><?= $oTemplate->isPropValid("subject") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
            </div>
            <div id="templateVariables"><?= nl2br($oTemplate->getTemplateGroup()->templateVariables) ?></div>
            
            <div class="card-footer">
              <span class="float-right">
                <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                  <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                    <?= sysTranslations::get('to_overview') ?>
                  </button>
                </a>
              </span>
              <?php if ($oTemplate->isEditable()) { ?>
                <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />
              <?php } ?>
            </div>
        </form>

      </div>
    </div>
  </div>
</div>




<?php

$sAdminFolder = ADMIN_FOLDER;
$sController  = http_get('controller');
$sJavascript  = '';

if ($oTemplate->type == 'email') {
    $sJavascript .= <<<EOT
<script>
    
    $(document).ready(function() {
        $('#template').summernote({
        tabsize: 2,
        height: 220,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
        });
    });
</script>
EOT;
}


$oPageLayout->addJavascript($sJavascript);
?>
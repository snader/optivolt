<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">

        <h1 class="m-0"><i aria-hidden="true" class="fas fa-envelope"></i>&nbsp;&nbsp;E-mail templates</h1></i>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Templates</h3>

            <div class="card-tools">
              <div class="input-group input-group-sm align-right" style="width: 50px;">

                <div class="input-group-append">
                  <a class="addBtn" href="<?= ADMIN_FOLDER . '/' . http_get('controller') ?>/toevoegen" title="<?= sysTranslations::get('add_item') ?>">
                    <button type="button" class="btn btn-default btn-sm" style="min-width:32px;">
                      <i class="fas fa-plus-circle"></i>
                    </button>
                  </a>&nbsp;
                </div>

              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width: 10px;">&nbsp;</th>
                  <th>Naam</th>
                  <th>Groep</th>
                  <th>Type</th>
                  <th>Technische naam</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($aTemplates AS $oTemplate) {
                ?>
                  <tr>
                    <td>
                      <?php

                      if ($oTemplate->isEditable()) {
                      ?>
                        <a class="btn btn-info btn-sm" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oTemplate->templateId ?>" title="Bewerken">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                      <?php } else {
                        echo '<i class="fas fa-pencil-alt"></i>';
                      } ?>
                    </td>
                    <td><?= _e($oTemplate->description) ?></td>
                    <td><?= ($oTemplate->getTemplateGroup() ? $oTemplate->getTemplateGroup()->templateGroupName : sysTranslations::get('global_unknown')) ?></td>
                    <td><?= _e($oTemplate->type) ?></td>
                    <td><?= _e($oTemplate->name) ?></td>
                    <td>
                     <?php
              
                      if ($oTemplate->isDeletable() && UserManager::getCurrentUser()->isSuperAdmin()) {
               
                      
                      ?>
                        <a class="btn btn-danger btn-xs" href="<?= ADMIN_FOLDER . '/' . http_get('controller') . '/verwijderen/' . $oTemplate->templateId ?>" title="Verwijderen" onclick="return confirmChoice('Verwijder <?= ' ' . $oTemplate->name ?>');">
                          <i class="fas fa-trash"></i>
                        </a>
                      <?php } else { ?><span class="btn btn-danger btn-xs disabled"><i class="fas fa-trash"></i></span><?php } ?>
                    </td>
                    
                  </tr>
                <?php
                }
                ?>

              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
</section>


<?php

$sJavascript = <<<EOT
<script>
    function sendTest(form){
        $.ajax({
            type: "POST",
            url: '/dashboard/templates-beheer/ajax-sendTest',
            data: $(form).serialize(),
            success: function(data){
                var dataObj = eval('('+data+')');
                console.log(dataObj);
                if(dataObj.success){
                    alert('Er is een test e-mail is verzonden naar `' + dataObj.to + '`');
                    $.fancybox.close();
                }else{
                    alert('De test e-mail kon niet worden verzonden naar `' + dataObj.to + '`');
                }
            }
        });
    }
</script>
EOT;
$oPageLayout->addJavascript($sJavascript);
?>

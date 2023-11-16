<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
    <input type="hidden" value="save" name="action" />
    <?= CSRFSynchronizerToken::field() ?>
  
    <div class="container-fluid">

        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= sysTranslations::get('customer_group') ?></h3>
                    </div>
                    <!-- /.card-header -->
                

                    <div class="card-body">
                    <div class="form-group">
                        <label for="title"><?= sysTranslations::get('global_name') ?> *</label>
                        <input type="text" name="title" class="form-control" id="title" value="<?= $oCustomerGroup->title ?>" title="<?= sysTranslations::get('customer_group_title_tooltip') ?>" 
                        required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                        <span class="error invalid-feedback show"><?= $oCustomerGroup->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                    </div>

                    <?php if ($oCurrentUser->isAdmin()) { ?>
                        
                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('customer_group_unique_name') ?> *</label>
                            <input type="text" name="name" class="form-control" id="name" data-rule-remote="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/ajax-checkName?customerGroupId=<?= $oCustomerGroup->customerGroupId ?>"
                            autocomplete="off" value="<?= $oCustomerGroup->name ?>" title="<?= sysTranslations::get('customer_group_unique_name') ?>" 
                            required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oCustomerGroup->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>                   
                        
                    <?php } ?>

                    </div>
               
                </div>
                
                
            </div>
            <!--/.col (left) -->
            <div class="col-12">
                              
                <span class="float-sm-right">
                <a class="backBtn right" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>">
                    <button type="button" class="btn btn-default btn-sm" title="<?= sysTranslations::get('global_back') . ' ' . sysTranslations::get('global_without_saving') ?>">
                    <?= sysTranslations::get('global_back') ?>
                    </button>
                </a>
                </span>
                <input type="submit" class="btn btn-primary" value="<?= sysTranslations::get('global_save') ?>" name="save" />

            </div>

        </div>    

    </div><!-- /.container-fluid -->
 


</form>


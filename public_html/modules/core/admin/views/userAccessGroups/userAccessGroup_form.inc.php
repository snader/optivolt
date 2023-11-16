<!-- form start -->
<form method="POST" action="" class="validateForm" id="quickForm">
    <input type="hidden" value="save" name="action" />

  
    <div class="container-fluid">

        <div class="row">
        <!-- left column -->
        <div class="col-md-6">

            <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= sysTranslations::get('userAccessGroup_userAccessGroup') ?></h3>
            </div>
            <!-- /.card-header -->
            

                <div class="card-body">

                <?php if ($oCurrentUser->isSuperAdmin()) { ?>
                    <div class="form-group">
                    <div class="row border-bottom mb-2 pb-1">
                        <div class="col-md-6">
                        <label for="editable"><?= sysTranslations::get('global_editable') ?></label>
                        </div>
                        <div class="col-md-6">
                        <input type="checkbox" id="editable" name="editable" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" 
                        data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>"
                        data-on-color="success" <?= $oUserAccessGroup->getEditable() ? 'CHECKED' : '' ?>>
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row border-bottom mb-2 pb-1">
                        <div class="col-md-6">
                        <label for="deletable"><?= sysTranslations::get('global_deletable') ?></label>
                        </div>
                        <div class="col-md-6">
                        <input type="checkbox" id="deletable" name="deletable" data-size="mini" data-bootstrap-switch data-off-color="danger" value="1" 
                        data-on-text="<?= sysTranslations::get('global_yes') ?>" data-off-text="<?= sysTranslations::get('global_no') ?>"
                        data-on-color="success" <?= $oUserAccessGroup->getDeletable() ? 'CHECKED' : '' ?>>
                        </div>
                    </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="displayName"><?= sysTranslations::get('userAccessGroup_displayName') ?> *</label>
                    <input type="text" name="displayName" class="form-control" id="displayName" value="<?= $oUserAccessGroup->displayName ?>" title="<?= sysTranslations::get('userAccessGroup_displayName_tooltip') ?>" 
                    required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                    <span class="error invalid-feedback show"><?= $oUserAccessGroup->isPropValid("displayName") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                </div>

                <?php if ($oCurrentUser->isSuperAdmin()) { ?>
                    <?php if ($oUserAccessGroup->isNameChangeable()) { ?>
                        <div class="form-group">
                            <label for="name"><?= sysTranslations::get('userAccessGroup_systemName') ?> *</label>
                            <input type="text" name="name" class="form-control" id="name" data-rule-remote="<?= ADMIN_FOLDER ?>/toegangsgroepen/ajax-checkName?userAccessGroupId=<?= $oUserAccessGroup->userAccessGroupId ?>"
                            autocomplete="off" value="<?= $oUserAccessGroup->name ?>" title="<?= sysTranslations::get('userAccessGroup_name_tooltip') ?>" 
                            required data-msg="<?= sysTranslations::get('global_field_not_completeds') ?>">
                            <span class="error invalid-feedback show"><?= $oUserAccessGroup->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span>
                        </div>                   
                    <?php } ?>
                <?php } ?>

                </div>
                </form>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                    <i class="fas fa-text-width"></i>
                    <?= sysTranslations::get('global_modules') ?>
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <ul>
                    <?php

                        foreach ($aModules AS $oModule) {
                            echo '<li>';
                            echo '<b>' . sysTranslations::get($oModule->linkName) . '</b>';
                            echo '<ul class="nestedCheckboxes" style="margin-top: 2px; margin-bottom: 5px;">';
                            $aFilter = [];
                            $aFilter['moduleId'] = $oModule->moduleId;
                            if (!$oCurrentUser->isSuperAdmin()) {
                                $aFilter['userAccessGroupId'] = $oCurrentUser->userAccessGroupId ? $oCurrentUser->userAccessGroupId : -1;
                            }

                            $aModuleActions = ModuleActionManager::getModuleActionsByFilter($aFilter);
                            foreach ($aModuleActions AS $oModuleAction) {
                                echo '<li>';
                                echo '<input class="alignCheckbox" type="checkbox" id="moduleActions_' . $oModuleAction->moduleActionId . '" name="moduleActionIds[]" ' . ($oUserAccessGroup->hasRightsForModuleAction(
                                        $oModuleAction->name
                                    ) ? 'CHECKED' : '') . ' value="' . $oModuleAction->moduleActionId . '" /> <label for="moduleActions_' . $oModuleAction->moduleActionId . '">' . $oModuleAction->displayName . '</label>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <!-- /.card-body -->
                </div>


        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">

        </div>
        <!--/.col (right) -->

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
        <!-- /.row -->

    

    </div><!-- /.container-fluid -->
   


</form>

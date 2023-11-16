<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('modules_module') ?></legend>
            <form method="POST" action="" class="validateForm">
                <input type="hidden" value="save" name="action" />
                <table class="withForm">
                    <tr>
                        <td class="withLabel" style="width: 160px;"><label for="name"><?= sysTranslations::get('modules_name') ?> *</label></td>
                        <td><input id="name" class="<?= $oModule->name == '' && $oModule->moduleId ? '' : 'required' ?> autofocus" title="<?= sysTranslations::get('modules_name_tooltip') ?>" type="text" autocomplete="off" name="name" value="<?= $oModule->name ?>" /></td>
                        <td><span class="error"><?= $oModule->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                    <tr>
                        <td class="withLabel"><label for="collapseName"><?= sysTranslations::get('modules_collapse_name') ?> *</label></td>
                        <td><input id="collapseName" class="" title="<?= sysTranslations::get('modules_collapse_name_tooltip') ?>" type="text" autocomplete="off" name="collapseName" value="<?= $oModule->collapseName ?>" /></td>
                        <td><span class="error"><?= $oModule->isPropValid("collapseName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="linkName"><?= sysTranslations::get('modules_link_name') ?> *</label></td>
                        <td><input id="linkName" class="required" title="<?= sysTranslations::get('modules_link_name_tooltip') ?>" type="text" autocomplete="off" name="linkName" value="<?= $oModule->linkName ?>" /></td>
                        <td><span class="error"><?= $oModule->isPropValid("linkName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="icon"><?= sysTranslations::get('modules_icon') ?> *</label></td>
                        <td><input id="linkName" class="" title="<?= sysTranslations::get('modules_link_name_tooltip') ?>" type="text" autocomplete="off" name="icon" value="<?= $oModule->icon ?>" /></td>
                        <td><span class="error"><?= $oModule->isPropValid("icon") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="hidden" name="showInMenu" value="0" /><?php // extra hidden field for workaround with checkboxes and _load function in controller
                                                                                ?>
                            <input class="alignCheckbox" type="checkbox" id="showInMenu" name="showInMenu" <?= ($oModule->showInMenu ? 'CHECKED' : '') ?> value="1" /> <label for="showInMenu"><?= sysTranslations::get(
                                                                                                                                                                                                    'modules_show_menu'
                                                                                                                                                                                                ) ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save" />
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('modules_moduleActions') ?></legend>
            <table class="sorted" style="width: 100%; margin-bottom: 10px;">
                <thead>
                    <tr class="topRow">
                        <td colspan="6">
                            <h2><?= sysTranslations::get('moduleActions_moduleActions') ?></h2>
                            <div class="right"><a alt="<?= sysTranslations::get('moduleActions_add_moduleAction') ?>" title="<?= sysTranslations::get('moduleActions_add_moduleAction') ?>" href="<?= ADMIN_FOLDER ?>/modules/ajax-addModuleAction?moduleId=<?= $oModule->moduleId ?>" class="addBtn textRight fancyBoxLink fancybox.ajax"><?= sysTranslations::get('module_add_moduleAction') ?></a></div>
                        </td>
                    </tr>
                    <tr>
                        <th class="{sorter:false} nonSorted"><?= sysTranslations::get('moduleActions_displayName') ?></th>
                        <?php if ($oCurrentUser->isSuperAdmin()) { ?>
                            <th class="{sorter:false} nonSorted"><?= sysTranslations::get('moduleActions_systemName') ?></th>
                        <?php } ?>
                        <th class="{sorter:false} nonSorted" style="width: 60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($aModuleActions as $oModuleAction) {
                        echo '<tr>';
                        echo '<td>' . _e($oModuleAction->displayName) . '</td>';
                        if ($oCurrentUser->isSuperAdmin()) {
                            echo '<td>' . _e($oModuleAction->name) . '</td>';
                        }
                        echo '<td>';
                        if ($oModuleAction->isEditable()) {
                            echo '<a class="action_icon edit_icon fancyBoxLink fancybox.ajax" href="' . ADMIN_FOLDER . '/modules/ajax-editModuleAction/' . $oModuleAction->moduleActionId . '"></a>';
                        } else {
                            echo '<span class="action_icon edit_icon grey"></span>';
                        }

                        if ($oModuleAction->isDeletable()) {
                            echo '<a class="action_icon delete_icon" href="' . ADMIN_FOLDER . '/modules/deleteModuleAction/' . $oModuleAction->moduleActionId . '" onclick="return confirmChoice(\'' . $oModuleAction->displayName . '\');"></a>';
                        } else {
                            echo '<span class="action_icon delete_icon grey"></span>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    if (empty($aModuleActions)) {
                        echo '<tr><td colspan="6"><i>' . sysTranslations::get('moduleActions_no_moduleActions') . '</i></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('pages_info') . ' <span class="locale">(' . sysTranslations::get('for_language') . ' ' . $oLocale->getLanguage()
                    ->getTranslations()->name . ')' ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <input type="hidden" value="<?= $oPage->parentPageId ?>" name="parentPageId"/>
                <table class="withForm" style="width: 100%;">
                    <?php if ($oPage->isOnlineChangeable()) { ?>
                        <tr>
                            <td><?= sysTranslations::get('global_online') ?> *</td>
                            <td>
                                <input class="alignRadio required" title="<?= sysTranslations::get('pages_online_tooltip') ?>" type="radio" <?= $oPage->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                        for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input class="alignRadio required" title="<?= sysTranslations::get('pages_offline_tooltip') ?>" type="radio" <?= !$oPage->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                        for="online_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="withLabel" style="width: 116px;"><label for="title"><?= sysTranslations::get('global_title') ?> *</label></td>
                        <td><input id="title" class="required autofocus default" title="<?= sysTranslations::get('pages_title_tooltip') ?>" minlength="3" type="text" autocomplete="off" name="title" value="<?= _e($oPage->title) ?>"/></td>
                        <td><span class="error"><?= $oPage->isPropValid("title") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="shortTitle"><?= sysTranslations::get('global_menu_link') ?>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('pages_link_tooltip') ?>">&nbsp;</div>
                            </label></td>
                        <td><input class="default" id="shortTitle" type="text" name="shortTitle" value="<?= _e($oPage->shortTitle) ?>" minlength="3" title="<?= sysTranslations::get('pages_link_min_3_tooltip') ?>"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><label for="intro"><?= sysTranslations::get('global_intro') ?></label></td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="intro" id="intro" class="tiny_MCE_page tiny_MCE"><?= $oPage->intro ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="3"><label for="content"><?= sysTranslations::get('global_content') ?></label></td>
                    </tr>
                    <tr>
                        <td colspan="3"><textarea name="content" id="content" class="tiny_MCE_page tiny_MCE"><?= $oPage->content ?></textarea></td>
                    </tr>
                    <?php if (moduleExists('forms') && !$oPage->hideFormManagement()) { ?>
                        <tr>
                            <td class="withLabel"><label><?= sysTranslations::get('pages_link_form') ?></label></td>
                            <td>
                                <select class="linkChoice default" name="formId" id="formId">
                                    <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                    <?php

                                    foreach (FormManager::getFormsByFilter() as $oForm) {
                                        echo '<option value="' . $oForm->formId . '" ' . ($oForm->formId == $oPage->formId ? 'selected' : '') . '>' . _e($oForm->name) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if ($oCurrentUser->isSEO()) { ?>
                        <tr>
                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_seo') ?></h2></td>
                        </tr>
                        <?php if ($oPage->pageId) {
                            $aLocales = $oPage->getLocales();
                            ?>
                            <tr>
                                <td><?= sysTranslations::get('global_current_url') ?><?= (count($aLocales) > 1 ? 's' : '') ?></td>
                                <td>
                                    <?php

                                    foreach ($aLocales as $oLocale) {
                                        echo getBaseUrl($oLocale) . $oPage->getUrlPath() . '<br />';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="withLabel"><label for="customCanonical"><?= sysTranslations::get('global_custom_canonical') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_custom_canonical_tooltip') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2"><input class="default" id="customCanonical" type="text" name="customCanonical" value="<?= _e($oPage->customCanonical) ?>"/></td>
                        </tr>
                        <td class="withLabel"><label for="windowTitle"><?= sysTranslations::get('global_window_title') ?></label>
                            <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_title_seo') ?>">&nbsp;</div>
                        </td>
                        <td colspan="2">
                            <div class="inline-block">
                                <input class="default charCounterWindowTitle" id="windowTitle" type="text" maxlength="255" name="windowTitle" value="<?= _e($oPage->windowTitle) ?>"/>
                                <div id="windowTitleCounter" style="text-align: right;"></div>
                            </div>
                        </td>
                        </tr>
                        <tr>
                            <td><label for="metaDescription"><?= sysTranslations::get('global_description') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_description_seo') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <div class="inline-block">
                                    <textarea class="charCounterMetaDescription default" id="metaDescription" maxlength="255" name="metaDescription"><?= _e($oPage->metaDescription) ?></textarea>
                                    <div id="metaDescriptionCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="metaKeywords"><?= sysTranslations::get('global_keywords') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_keywords_seo') ?>">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <div class="inline-block"><input class="default charCounterMetaKeywords" id="metaKeywords" type="text" maxlength="255" name="metaKeywords" value="<?= _e($oPage->metaKeywords) ?>"/> (<?= sysTranslations::get(
                                        'global_optional'
                                    ) ?>)
                                    <div id="metaKeywordsCounter" style="text-align: right;"></div>
                                </div>
                            </td>
                        </tr>
                        <?php if (!$oPage->getLockUrlPath()) { ?>
                            <tr>
                                <td class="withLabel"><label for="urlPart"><?= sysTranslations::get('global_seo_url') ?></label>
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('global_seo_url_tooltip') ?>: <?= CLIENT_HTTP_URL ?><?= $oPage->parentPageId ? $oPage->getParent()
                                        ->getUrlPath($aLocales[0]->localeId) : '' ?>/<?= sysTranslations::get('global_seo_url_tooltip_2') ?>">&nbsp;
                                    </div>
                                </td>
                                <td><input class="default" id="urlPart" type="text" name="urlPart" value="<?= _e($oPage->getUrlPart()) ?>"/></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="withLabel"><label for="urlParameters"><?= sysTranslations::get('pages_url_parameters') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('pages_url_parameters_tooltip_1') ?> <?= CLIENT_HTTP_URL ?><?= $oPage->parentPageId ? $oPage->getParent()
                                    ->getUrlPath($aLocales[0]->localeId) : '' ?><?= sysTranslations::get('pages_url_parameters_tooltip_1') ?>">&nbsp;
                                </div>
                            </td>
                            <td><input class="default" id="urlParameters" type="text" name="urlParameters" value="<?= _e($oPage->getUrlParameters()) ?>"/></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_in_menu') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_in_menu_tooltip') ?>" type="radio" <?= $oPage->getInMenu() ? 'CHECKED' : '' ?> id="inMenu_1" name="inMenu" value="1"/> <label
                                        for="inMenu_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_in_menu_tooltip') ?>" type="radio" <?= !$oPage->getInMenu() ? 'CHECKED' : '' ?> id="inMenu_0" name="inMenu"
                                       value="0"/> <label for="inMenu_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("inMenu") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_in_footer') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_in_footer_tooltip') ?>" type="radio" <?= $oPage->getInFooter() ? 'CHECKED' : '' ?> id="inFooter_1" name="inFooter" value="1"/> <label
                                        for="inFooter_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_in_footer_tooltip') ?>" type="radio" <?= !$oPage->getInFooter() ? 'CHECKED' : '' ?> id="inFooter_0" name="inFooter"
                                       value="0"/> <label for="inFooter_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("inFooter") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_indexable') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_indexable_tooltip') ?>" type="radio" <?= $oPage->getIndexable() ? 'CHECKED' : '' ?> id="indexable_1" name="indexable" value="1"/> <label
                                        for="indexable_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_indexable_tooltip') ?>" type="radio" <?= !$oPage->getIndexable() ? 'CHECKED' : '' ?> id="indexable_0"
                                       name="indexable" value="0"/> <label for="indexable_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("indexable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_include_parent_in_url_path') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_include_parent_in_url_path_tooltip') ?>" type="radio" <?= $oPage->getIncludeParentInUrlPath() ? 'CHECKED' : '' ?> id="includeParentInUrlPath_1"
                                       name="includeParentInUrlPath" value="1"/> <label
                                        for="includeParentInUrlPath_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_include_parent_in_url_path_tooltip') ?>" type="radio" <?= !$oPage->getIncludeParentInUrlPath() ? 'CHECKED' : '' ?>
                                       id="includeParentInUrlPath_0"
                                       name="includeParentInUrlPath" value="0"/> <label for="includeParentInUrlPath_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("includeParentInUrlPath") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                    <?php } ?>
                    <?php if ($oCurrentUser->isAdmin()) { ?>
                        <tr>
                            <td colspan="3" style="padding-top: 10px;"><h2><?= sysTranslations::get('global_admin_settings') ?></h2></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="name"><?= sysTranslations::get('pages_unique_name') ?></label></td>
                            <td><input id="name" class="default" data-rule-remote="<?= ADMIN_FOLDER ?>/paginas/ajax-checkName?pageId=<?= $oPage->pageId ?>" title="<?= sysTranslations::get('pages_unique_name_tooltip') ?>" type="text"
                                       name="name" value="<?= $oPage->name ?>"/></td>
                            <td><span class="error"><?= $oPage->isPropValid("name") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="controllerPath"><?= sysTranslations::get('pages_controller_path') ?> *</label></td>
                            <td><input class="required default" id="controllerPath" name="controllerPath" type="text" value="<?= _e($oPage->getControllerPath()) ?>"/></td>
                            <td><span class="error"><?= $oPage->isPropValid("controllerPath") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_online_changeable') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_online_changeable_tooltip') ?>" type="radio" <?= $oPage->getOnlineChangeable() ? 'CHECKED' : '' ?> id="onlineChangeable_1"
                                       name="onlineChangeable" value="1"/> <label for="onlineChangeable_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_online_changeable_tooltip') ?>" type="radio" <?= !$oPage->getOnlineChangeable() ? 'CHECKED' : '' ?>
                                       id="onlineChangeable_0" name="onlineChangeable" value="0"/> <label for="onlineChangeable_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('global_editable') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= $oPage->getEditable() ? 'CHECKED' : '' ?> id="editable_1" name="editable" value="1"/> <label
                                        for="editable_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_editable_tooltip') ?>" type="radio" <?= !$oPage->getEditable() ? 'CHECKED' : '' ?> id="editable_0" name="editable"
                                       value="0"/> <label for="editable_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("editable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('global_deletable') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_deletable_tooltip') ?>" type="radio" <?= $oPage->getDeletable() ? 'CHECKED' : '' ?> id="deletable_1" name="deletable" value="1"/> <label
                                        for="deletable_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_deletable_tooltip') ?>" type="radio" <?= !$oPage->getDeletable() ? 'CHECKED' : '' ?> id="deletable_0"
                                       name="deletable" value="0"/> <label for="deletable_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("deletable") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_has_subpages') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('page_has_subpages_tooltip') ?>" type="radio" <?= $oPage->getMayHaveSub() ? 'CHECKED' : '' ?> id="mayHaveSub_1" name="mayHaveSub" value="1"/> <label
                                        for="mayHaveSub_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('page_has_sub_tooltip') ?>" type="radio" <?= !$oPage->getMayHaveSub() ? 'CHECKED' : '' ?> id="mayHaveSub_0"
                                       name="mayHaveSub" value="0"/> <label for="mayHaveSub_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("mayHaveSub") ? '' : sysTranslations::get('global_field_not_completed') ?> </span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_lock_path') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_lock_path_tooltip') ?>" type="radio" <?= $oPage->getLockUrlPath() ? 'CHECKED' : '' ?> id="lockUrlPath_1" name="lockUrlPath" value="1"/> <label
                                        for="lockUrlPath_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_lock_path_tooltip') ?>" type="radio" <?= !$oPage->getLockUrlPath() ? 'CHECKED' : '' ?> id="lockUrlPath_0"
                                       name="lockUrlPath" value="0"/> <label for="lockUrlPath_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("lockUrlPath") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_lock_parent') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_lock_parent_tooltip') ?>" type="radio" <?= $oPage->getLockParent() ? 'CHECKED' : '' ?> id="lockParent_1" name="lockParent" value="1"/> <label
                                        for="lockParent_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_lock_parent_tooltip') ?>" type="radio" <?= !$oPage->getLockParent() ? 'CHECKED' : '' ?> id="lockParent_0"
                                       name="lockParent" value="0"/> <label for="lockParent_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("lockUrlPath") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_no_images') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_no_images_tooltip') ?>" type="radio" <?= $oPage->getHideImageManagement() ? 'CHECKED' : '' ?> id="hideImageManagement_1"
                                       name="hideImageManagement" value="1"/> <label for="hideImageManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_no_images_tooltip') ?>" type="radio" <?= !$oPage->getHideImageManagement() ? 'CHECKED' : '' ?>
                                       id="hideImageManagement_0" name="hideImageManagement" value="0"/> <label for="hideImageManagement_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("hideImageManagement") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_no_files') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_no_files_tooltip') ?>" type="radio" <?= $oPage->getHideFileManagement() ? 'CHECKED' : '' ?> id="hideFileManagement_1" name="hideFileManagement"
                                       value="1"/> <label for="hideFileManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_no_files_tooltip') ?>" type="radio" <?= !$oPage->getHideFileManagement() ? 'CHECKED' : '' ?>
                                       id="hideFileManagement_0" name="hideFileManagement" value="0"/> <label for="hideFileManagement_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("hideFileManagement") ? '' : sysTranslations::get('page_has_no_file_management') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_no_links') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_no_links_tooltip') ?>" type="radio" <?= $oPage->getHideLinkManagement() ? 'CHECKED' : '' ?> id="hideLinkManagement_1" name="hideLinkManagement"
                                       value="1"/> <label for="hideLinkManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_no_links_tooltip') ?>" type="radio" <?= !$oPage->getHideLinkManagement() ? 'CHECKED' : '' ?>
                                       id="hideLinkManagement_0" name="hideLinkManagement" value="0"/> <label for="hideLinkManagement_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("hideLinkManagement") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_no_video') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_no_video_tooltip') ?>" type="radio" <?= $oPage->getHideVideoLinkManagement() ? 'CHECKED' : '' ?> id="hideVideoLinkManagement_1"
                                       name="hideVideoLinkManagement" value="1"/> <label for="hideVideoLinkManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('page_has_no_video_management') ?>" type="radio" <?= !$oPage->getHideVideoLinkManagement() ? 'CHECKED' : '' ?>
                                       id="hideVideoLinkManagement_0" name="hideVideoLinkManagement" value="0"/> <label for="hideVideoLinkManagement_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("hideVideoLinkManagement") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td><?= sysTranslations::get('pages_no_brandbox') ?></td>
                            <td>
                                <input class="alignRadio" title="<?= sysTranslations::get('pages_no_brandbox_tooltip') ?>" type="radio" <?= $oPage->getHideBrandboxManagement() ? 'CHECKED' : '' ?> id="hideBrandboxManagement_1"
                                       name="hideBrandboxManagement" value="1"/> <label for="hideBrandboxManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('pages_no_brandbox_tooltip') ?>" type="radio" <?= !$oPage->getHideBrandboxManagement() ? 'CHECKED' : '' ?>
                                       id="hideBrandboxManagement_0" name="hideBrandboxManagement" value="0"/> <label for="hideBrandboxManagement_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oPage->isPropValid("hideBrandboxManagement") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <?php if (moduleExists('forms')) { ?>
                            <tr>
                                <td><?= sysTranslations::get('pages_no_form') ?></td>
                                <td>
                                    <input class="alignRadio" title="<?= sysTranslations::get('pages_no_video_tooltip') ?>" type="radio" <?= $oPage->getHideFormManagement() ? 'CHECKED' : '' ?> id="hideFormManagement_1"
                                           name="hideFormManagement" value="1"/> <label for="hideVideoLinkManagement_1"><?= sysTranslations::get('global_yes') ?></label>
                                    <input style="margin-left: 5px;" class="alignRadio" title="<?= sysTranslations::get('page_has_no_video_management') ?>" type="radio" <?= !$oPage->getHideFormManagement() ? 'CHECKED' : '' ?>
                                           id="hideFormManagement_0" name="hideFormManagement" value="0"/> <label for="hideFormManagement_0"><?= sysTranslations::get('global_no') ?></label>
                                </td>
                                <td><span class="error"><?= $oPage->isPropValid("hideFormManagement") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
        </fieldset>
        </form>
    </div>
    <?php
    if (!empty($oPage->pageId)) {
        /** @var $oAutocompleteManager AutocompleteManager */
        foreach ($aAutocompleters as $oAutocompleteManager) {
            echo $oAutocompleteManager->includeTemplate();
        }
    }
    ?>
    <?php if (moduleExists('brandboxItems') && !$oPage->hideBrandboxManagement()) { ?>
        <div class="contentColumn">
            <fieldset>
                <div class="contentColumn">
                    <table class="sorted" style="min-width: 100%">
                        <thead>
                        <tr class="topRow">
                            <td colspan="2">
                                <h2><?= sysTranslations::get('brandbox_all_items') ?></h2>
                                <br/>
                                <form id="BB" method="POST" action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/add-bb">
                                    <table width="100%">
                                        <tr>
                                            <td class="withLabel" width="30%">
                                                <label for="title"><?= sysTranslations::get('brandbox') ?></label>
                                            </td>
                                            <td width="60%">
                                                <select name="brandboxItemId" class="default" style="width: 100% !important;">
                                                    <option value=""><?= sysTranslations::get('brandbox_select') ?></option>
                                                    <?php

                                                    /** @var \BrandboxItem $oBrandbox */
                                                    foreach (BrandboxItemManager::getBrandboxItemsByFilter(['showAll' => true]) as $oBrandbox) {
                                                        if (in_array($oBrandbox->brandboxItemId, $oPage->getBrandboxItemIds())) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <option value="<?= $oBrandbox->brandboxItemId ?>"><?= $oBrandbox->name ?></option>
                                                        <?php

                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td width="10%">
                                                <?= CSRFSynchronizerToken::field() ?>
                                                <input type="hidden" name="pageId" value="<?= $oPage->pageId ?>"/>
                                                <input type="hidden" name="action" value="add"/>
                                                <input type="submit" name="submit" class="addBtn textRight" title="<?= sysTranslations::get('brandbox_add_tooltip') ?>"
                                                       alt="<?= sysTranslations::get('brandbox_add_tooltip') ?>" value="<?= sysTranslations::get('global_save') ?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <th class="{sorter:false} nonSorted"><?= sysTranslations::get('global_name') ?></th>
                            <th class="{sorter:false} nonSorted" style="width: 30px;">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="EstateAgentTable_">
                        <?php foreach ($oPage->getBrandboxItems(true) as $oBrandbox): ?>
                            <tr estate-agent-data="<?= $oBrandbox->brandboxItemId ?>">
                                <td><?= _e($oBrandbox->name) ?></td>
                                <td>
                                    <a class="action_icon delete_icon" title="<?= sysTranslations::get('brandbox_delete') ?>" onclick="return confirmChoice('<?= $oBrandbox->name ?>');"
                                       href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/remove-bb/<?= $oPage->pageId ?>/<?= $oBrandbox->brandboxItemId ?>?<?= CSRFSynchronizerToken::query() ?>"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (count($oPage->getBrandboxItems(true)) == 0): ?>
                            <tr>
                                <td colspan="2"><i><?= sysTranslations::get('brandbox_no_items') ?></i></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>
        <?php

    }
    if (!$oPage->hideImageManagement()) { ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('global_images') ?></legend>
                <?php

                if ($oPage->pageId !== null) {
                    $oImageManagerHTML->includeTemplate();
                } else {
                    echo '<p><i>' . sysTranslations::get('pages_images_warning') . '</i></p>';
                }
                ?>
            </fieldset>
        </div>
        <?php

    }
    if (!$oPage->hideFileManagement()) {
        ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('global_files') ?></legend>
                <?php

                if ($oPage->pageId !== null) {
                    $oFileManagerHTML->includeTemplate();
                } else {
                    echo '<p><i>' . sysTranslations::get('pages_files_warning') . '</i></p>';
                }
                ?>
            </fieldset>
        </div>
        <?php

    }
    if (!$oPage->hideLinkManagement()) {
        ?>

        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('global_links') ?></legend>
                <?php

                if ($oPage->pageId !== null) {
                    $oLinkManagerHTML->includeTemplate();
                } else {
                    echo '<p><i>' . sysTranslations::get('pages_links_warning') . '</i></p>';
                }
                ?>
            </fieldset>
        </div>
        <?php

    }
    if (!$oPage->hideVideoLinkManagement()) {
        ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('global_videolinks') ?></legend>
                <?php

                if ($oPage->pageId !== null) {
                    $oVideoLinkManagerHTML->includeTemplate();
                } else {
                    echo '<p><i>' . sysTranslations::get('pages_video_warning') . '</i></p>';
                }
                ?>
            </fieldset>
        </div>
        <?php

    }
    ?>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$oPageLayout->addJavascript(
    '
<script>
    initTinyMCE(".tiny_MCE_page", "/dashboard/paginas/link-list", "/dashboard/paginas/image-list/' . $oPage->pageId . '");
</script>
'
);
?>

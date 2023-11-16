<div class="cf">

    <form method="POST" action="" id="validateFormModuleGenerator">
        <input type="hidden" value="save" name="action"/>

        <div class="contentColumn" style="min-height: 600px;">
            <fieldset>
                <legend><?= sysTranslations::get('whats_the_name') ?></legend>
                <br/>
                <div style="border:1px solid #ccc;background:#eee;padding:10px;margin-bottom:10px;margin-top:10px;">
                    <input placeholder="Vul de gewenste modulenaam in" class="default" style="width:100%;" id="autoFill" type="text" autocomplete="off"/>
                    <br/><br/>
                    <?= sysTranslations::get('moduleGenerator_autofill_explain') ?>
                </div>
            </fieldset>
            <br/>
            <fieldset>
                <legend><?= sysTranslations::get('moduleGenerator_module_options') ?></legend>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('moduleGenerator_hasFiles') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasFiles_tooltip') ?>" type="radio" <?= $oModuleGeneratorItem->hasFiles ? 'CHECKED' : '' ?> id="hasFiles_1" name="hasFiles"
                                   value="1"/>
                            <label for="hasFiles_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasFiles_tooltip') ?>" type="radio" <?= !$oModuleGeneratorItem->hasFiles ? 'CHECKED' : '' ?> id="hasFiles_0" name="hasFiles"
                                   value="0"/>
                            <label for="hasFiles_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("hasFiles") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('moduleGenerator_hasImages') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasImages_tooltip') ?>" type="radio" <?= $oModuleGeneratorItem->hasImages ? 'CHECKED' : '' ?> id="hasImages_1"
                                   name="hasImages" value="1"/>
                            <label for="hasImages_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasImages_tooltip') ?>" type="radio" <?= !$oModuleGeneratorItem->hasImages ? 'CHECKED' : '' ?> id="hasImages_0"
                                   name="hasImages" value="0"/>
                            <label for="hasImages_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("hasImages") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('moduleGenerator_hasVideos') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasVideos_tooltip') ?>" type="radio" <?= $oModuleGeneratorItem->hasVideos ? 'CHECKED' : '' ?> id="hasVideos_1"
                                   name="hasVideos" value="1"/>
                            <label for="hasVideos_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasVideos_tooltip') ?>" type="radio" <?= !$oModuleGeneratorItem->hasVideos ? 'CHECKED' : '' ?> id="hasVideos_0"
                                   name="hasVideos" value="0"/>
                            <label for="hasVideos_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("hasVideos") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('moduleGenerator_hasLinks') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasLinks_tooltip') ?>" type="radio" <?= $oModuleGeneratorItem->hasLinks ? 'CHECKED' : '' ?> id="hasLinks_1" name="hasLinks"
                                   value="1"/>
                            <label for="hasLinks_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasLinks_tooltip') ?>" type="radio" <?= !$oModuleGeneratorItem->hasLinks ? 'CHECKED' : '' ?> id="hasLinks_0" name="hasLinks"
                                   value="0"/>
                            <label for="hasLinks_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("hasLinks") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('moduleGenerator_hasCategories') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasCategories_tooltip') ?>" type="radio" <?= $oModuleGeneratorItem->hasCategories ? 'CHECKED' : '' ?> id="hasCategories_1"
                                   name="hasCategories" value="1"/>
                            <label for="hasCategories_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('moduleGenerator_hasCategories_tooltip') ?>" type="radio" <?= !$oModuleGeneratorItem->hasCategories ? 'CHECKED' : '' ?> id="hasCategories_0"
                                   name="hasCategories" value="0"/>
                            <label for="hasCategories_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("hasCategories") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td><?= sysTranslations::get('moduleGenerator_hasUrls') ?> *</td>
                        <td>
                            <input class="alignRadio required radiohasUrl hasUrlsYes" title="<?= sysTranslations::get('moduleGenerator_hasUrls_tooltip') ?>" type="radio" <?= $oModuleGeneratorItem->hasUrls ? 'CHECKED' : '' ?>
                                   id="hasUrls_1" name="hasUrls"
                                   value="1"/>
                            <label for="hasUrls_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required radiohasUrl hasUrlsNo" title="<?= sysTranslations::get('moduleGenerator_hasUrls_tooltip') ?>" type="radio" <?= !$oModuleGeneratorItem->hasUrls ? 'CHECKED' : '' ?>
                                   id="hasUrls_0" name="hasUrls"
                                   value="0"/>
                            <label for="hasUrls_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("hasCategories") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                </table>
            </fieldset>

            <br>
            <fieldset>
                <legend><?= sysTranslations::get('moduleGenerator_module_naming') ?></legend>
                <table class="withForm">
                    <tr>
                        <td class="withLabel"><label for="defaultLocaleTranslationItem"><?= sysTranslations::get('moduleGenerator_defaultLocaleTranslationItem') ?> * </label></td>
                        <td><input placeholder="Bijv: postcode" class="required default" id="defaultLocaleTranslationItem" type="text" autocomplete="off"
                                   name="defaultLocaleTranslationItem" title="<?= sysTranslations::get('moduleGenerator_no_defaultLocaleTranslationItem') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("defaultLocaleTranslationItem") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="defaultLocaleTranslationItems"><?= sysTranslations::get('moduleGenerator_defaultLocaleTranslationItems') ?> * </label></td>
                        <td><input placeholder="Bijv: postcodes" class="required default" id="defaultLocaleTranslationItems" type="text" autocomplete="off"
                                   name="defaultLocaleTranslationItems" title="<?= sysTranslations::get('moduleGenerator_no_defaultLocaleTranslationItems') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("defaultLocaleTranslationItems") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="notDefaultLocaleTranslationItem"><?= sysTranslations::get('moduleGenerator_notDefaultLocaleTranslationItem') ?> * </label></td>
                        <td><input placeholder="Bijv: postalcode" class="required default" id="notDefaultLocaleTranslationItem" type="text" autocomplete="off"
                                   name="notDefaultLocaleTranslationItem" title="<?= sysTranslations::get('moduleGenerator_no_notDefaultLocaleTranslationItem') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("notDefaultLocaleTranslationItem") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="notDefaultLocaleTranslationItems"><?= sysTranslations::get('moduleGenerator_notDefaultLocaleTranslationItems') ?> * </label></td>
                        <td><input placeholder="Bijv: postalcodes" class="required default" id="notDefaultLocaleTranslationItems" type="text" autocomplete="off"
                                   name="notDefaultLocaleTranslationItems" title="<?= sysTranslations::get('moduleGenerator_no_notDefaultLocaleTranslationItems') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("notDefaultLocaleTranslationItems") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="moduleFolderName"><?= sysTranslations::get('moduleGenerator_moduleFolderName') ?> * </label></td>
                        <td><input placeholder="Bijv: postalCodes" class="required default" id="moduleFolderName" type="text" autocomplete="off" name="moduleFolderName"
                                   title="<?= sysTranslations::get('moduleGenerator_no_moduleFolderName') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("moduleFolderName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="classFileName"><?= sysTranslations::get('moduleGenerator_classFileName') ?> * </label></td>
                        <td><input placeholder="Bijv: PostalCode" class="required default" id="classFileName" type="text" autocomplete="off" name="classFileName"
                                   title="<?= sysTranslations::get('moduleGenerator_no_classFileName') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("classFileName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="moduleDescription"><?= sysTranslations::get('moduleGenerator_moduleDescription') ?> * </label></td>
                        <td><textarea placeholder="Module beschrijving" class="required default" id="moduleDescription" type="text"
                                      autocomplete="off" name="moduleDescription"
                                      title="<?= sysTranslations::get('moduleGenerator_no_moduleDescription') ?>"></textarea></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("moduleDescription") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="controllerFileName"><?= sysTranslations::get('moduleGenerator_controllerFileName') ?> * </label></td>
                        <td><input placeholder="Bijv: postalCodeItem" class="required default" id="controllerFileName" type="text" autocomplete="off" name="controllerFileName"
                                   title="<?= sysTranslations::get('moduleGenerator_no_controllerFileName') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("controllerFileName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="singleSystemFileName"><?= sysTranslations::get('moduleGenerator_singleSystemFileName') ?> * </label></td>
                        <td><input placeholder="Bijv: postalCodeItem" class="required default" id="singleSystemFileName" type="text" autocomplete="off" name="singleSystemFileName"
                                   title="<?= sysTranslations::get('moduleGenerator_no_singleSystemFileName') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("singleSystemFileName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="multipleSystemFileName"><?= sysTranslations::get('moduleGenerator_multipleSystemFileName') ?> * </label></td>
                        <td><input placeholder="Bijv: postalCodeItems" class="required default" id="multipleSystemFileName" type="text" autocomplete="off"
                                   name="multipleSystemFileName"
                                   title="<?= sysTranslations::get('moduleGenerator_no_multipleSystemFileName') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("multipleSystemFileName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="relationTableNamePrefix"><?= sysTranslations::get('moduleGenerator_relationTableNamePrefix') ?> * </label></td>
                        <td><input placeholder="Bijv: postalcode" class="required default" id="relationTableNamePrefix" type="text" autocomplete="off" name="relationTableNamePrefix"
                                   title="<?= sysTranslations::get('moduleGenerator_no_relationTableNamePrefix') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("relationTableNamePrefix") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="fontawesomeIcon"><?= sysTranslations::get('moduleGenerator_fontawesomeIcon') ?><br><?= sysTranslations::get('fontawesome_icon_link') ?> * </label></td>
                        <td><input placeholder="Bijv: fa-calculator" class="required default" id="fontawesomeIcon" type="text" autocomplete="off" name="fontawesomeIcon"
                                   title="<?= sysTranslations::get('moduleGenerator_no_fontawesomeIcon') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("fontawesomeIcon") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                </table>
            </fieldset>
        </div>

        <div class="contentColumn hasUrlsContainer <?= ($oModuleGeneratorItem->hasUrls ? '' : 'hide') ?>" style="min-height: 600px;">
            <fieldset>
                <legend><?= sysTranslations::get('moduleGenerator_page_settings') ?></legend>

                <table class="withForm">
                    <tr>
                        <td class="withLabel"><label for="defaultLocalePageControllerRoute"><?= sysTranslations::get('moduleGenerator_defaultLocalePageControllerRoute') ?> * </label></td>
                        <td><input placeholder="Bijv: postcodes" class="required default" id="defaultLocalePageControllerRoute" type="text" autocomplete="off"
                                   name="defaultLocalePageControllerRoute"
                                   title="<?= sysTranslations::get('moduleGenerator_no_defaultLocalePageControllerRoute') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("defaultLocalePageControllerRoute") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="notDefaultLocalePageControllerRoute"><?= sysTranslations::get('moduleGenerator_notDefaultLocalePageControllerRoute') ?> * </label></td>
                        <td><input placeholder="Bijv: postalcodes" class="required default" id="notDefaultLocalePageControllerRoute" type="text" autocomplete="off"
                                   name="notDefaultLocalePageControllerRoute" title="<?= sysTranslations::get('moduleGenerator_no_notDefaultLocalePageControllerRoute') ?>"/>
                        </td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("notDefaultLocalePageControllerRoute") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="defaultLocalePageTitle"><?= sysTranslations::get('moduleGenerator_defaultLocalePageTitle') ?> * </label></td>
                        <td><input placeholder="Bijv: Overzicht postcodes" class="required default" id="defaultLocalePageTitle" type="text" autocomplete="off"
                                   name="defaultLocalePageTitle"
                                   title="<?= sysTranslations::get('moduleGenerator_no_defaultLocalePageTitle') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("defaultLocalePageTitle") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="notDefaultLocalePageTitle"><?= sysTranslations::get('moduleGenerator_notDefaultLocalePageTitle') ?> * </label></td>
                        <td><input placeholder="Bijv: Overview postalcodes" class="required default" id="notDefaultLocalePageTitle" type="text" autocomplete="off"
                                   name="notDefaultLocalePageTitle" title="<?= sysTranslations::get('moduleGenerator_no_notDefaultLocalePageTitle') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("notDefaultLocalePageTitle") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="pageSystemName"><?= sysTranslations::get('moduleGenerator_pageSystemName') ?> * </label></td>
                        <td><input placeholder="Bijv: postal_codes" class="required default" id="pageSystemName" type="text" autocomplete="off" name="pageSystemName"
                                   title="<?= sysTranslations::get('moduleGenerator_no_pageSystemName') ?>"/></td>
                        <td><span class="error"><?= $oModuleGeneratorItem->isPropValid("pageSystemName") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>

                </table>


            </fieldset>
        </div>

        <br class="clear"><br>
        <input type="submit" value="<?= sysTranslations::get('moduleGenerator_generate_module') ?>" name="save"/>

    </form>

</div>


<?php
$sTranslationGenerating = sysTranslations::get('moduleGenerator_generating_module');
$sJS                    = <<<EOT
<script>
$('.radiohasUrl').click(function() {
    if($(this).val() == 1) {
        $('.hasUrlsContainer').show();
    } else {
        $('.hasUrlsContainer').hide();
    }
});

var keyupTimeout = null;
$(document).on('keyup', "#autoFill", function(el) {
    clearTimeout(keyupTimeout);
    keyupTimeout = setTimeout(function(){
        
        // Replace whitespace with - , make lower case
        var sValue = $(el.target).val().replace(/_/g,' ').replace(/\s\s+/g, " ");
        var sModuleValueSingle = pluralize.singular(sValue.normalize("NFD").replace(/[\u0300-\u036f]/g, "")).replace(/\s/g, "-").replace(/-+/g,'-').toLowerCase();
        var sModuleValuePlural = pluralize.plural(sValue.normalize("NFD").replace(/[\u0300-\u036f]/g, "")).replace(/\s/g, "-").replace(/-+/g,'-').toLowerCase();
        
        $('#defaultLocaleTranslationItem, #notDefaultLocaleTranslationItem, #defaultLocalePageTitle').val(ucfirst(pluralize.singular(sValue))); // translations, use user input
        $('#defaultLocaleTranslationItems, #notDefaultLocaleTranslationItems').val(ucfirst(pluralize.plural(sValue))); // translations, use user input
        $('#defaultLocalePageControllerRoute').val(sModuleValuePlural);
        $('#notDefaultLocalePageControllerRoute').val(sModuleValuePlural+'-en');
        $('#notDefaultLocalePageTitle').val(ucfirst(pluralize.plural(sValue))+' en');
        $('#moduleDescription').val('This is a module for: '+ucfirst(sModuleValuePlural));
        $('#singleSystemFileName, #controllerFileName').val(toCamelCase(sModuleValueSingle));
        $('#moduleFolderName, #multipleSystemFileName').val(toCamelCase(sModuleValuePlural));
        $('#classFileName').val(toPascalCase(sModuleValueSingle));
        $('#relationTableNamePrefix, #pageSystemName').val(sModuleValuePlural.replace(/-/g,'_'));

    },300)
});

/**
 * Transform a string to PascalCase
 * @param string sString
 * @return string
 */
function toPascalCase(sString)
{
    var aString = sString.split('-');
    var sPascal = '';
    for(var sProp in aString){
        sPascal += ucfirst(aString[sProp]);
    }

    return sPascal;
}

/**
 * Transform a string to camelCase
 * @param string sString
 * @return string
 */
function toCamelCase(sString)
{
    var aString = sString.split('-');
    var sCamel = '';
    var bFirst = true;
    for(var sProp in aString){
        if(!bFirst){
            sCamel += ucfirst(aString[sProp]);
        }else{
            bFirst = false;
            sCamel += aString[sProp];
        }
    }

    return sCamel;
}

/**
 * php ucfirst equivelant
 * @param string sString
 * @return string
 */
function ucfirst(sString) {
    return sString.charAt(0).toUpperCase() + sString.slice(1);
}

/* validate form with fancybox */
var formModuleGenerator = $("form#validateFormModuleGenerator");
formModuleGenerator.validate({
    focusInvalid: true,
    errorLabelContainer: "#validationErrors ul#errors",
    wrapper: "li",
    invalidHandler: function (form, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {
            setTimeout("$('#validationErrorsLink').click();", 200);
        }
    },
    submitHandler: function(form) {
        formModuleGenerator.find('input[type=submit]').val('{$sTranslationGenerating}');
        formModuleGenerator.find('input[type=submit]').attr('disabled', 'disabled');
        form.submit();
    },
    ignore: ":hidden:not('.do-validate')"
});
    
</script>
EOT;
$oPageLayout->addJavascript($sJS);
?>
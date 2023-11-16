<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('redirectBackToOverview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('redirectsNoSave') ?>)</span>
</div>
<div class="cf">
    <?php

    if ($oRedirect->type == Redirect::TYPE_SPECIFIC) {
        ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('redirectSpecific') ?></legend>
                <form method="POST" action="" class="validateForm">
                    <?= CSRFSynchronizerToken::field() ?>
                    <input type="hidden" value="save" name="action"/>
                    <input type="hidden" value="1" name="type"/>
                    <table class="withForm">
                        <tr>
                            <td><?= sysTranslations::get('global_online') ?> *</td>
                            <td>
                                <input class="alignRadio {validate:{required:true}}" title="<?= sysTranslations::get('redirect_offline_online') ?>" type="radio" <?= $oRedirect->online ? 'CHECKED' : '' ?> id="online_1" name="online"
                                       value="1"/> <label for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input class="alignRadio {validate:{required:true}}" title="<?= sysTranslations::get('redirect_offline_online') ?>" type="radio" <?= !$oRedirect->online ? 'CHECKED' : '' ?> id="online_0" name="online"
                                       value="0"/> <label for="online_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oRedirect->isPropValid("online") ? '' : 'Veld niet (juist) ingevuld' ?></span></td>
                        </tr>
                        <tr>
                            <td><label><?= sysTranslations::get('redirectsOldUrl') ?> *</label></td>
                            <td colspan="2"><input class="default {validate:{required:true}}" title="<?= sysTranslations::get('redirect_fill_in_old_url') ?>" id="pattern" type="text" autocomplete="off" name="pattern"
                                                   value="<?= $oRedirect->pattern ?>"/></td>
                            <td><span class="error"><?= $oRedirect->isPropValid("pattern") ? '' : sysTranslations::get('redirect_url_not_allowed') ?></span></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="newUrl"><?= sysTranslations::get('redirectsNewUrl') ?> *</label></td>
                            <td colspan="2"><input class="default {validate:{required:true}}" title="<?= sysTranslations::get('redirect_fill_in_destination_url') ?>" id="title" type="text" autocomplete="off" name="newUrl"
                                                   value="<?= $oRedirect->newUrl ?>"/></td>
                            <td><span class="error"><?= $oRedirect->isPropValid("newUrl") ? '' : sysTranslations::get('redirect_url_not_allowed') ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </div>
        <?php

    } else {
        ?>
        <div class="contentColumn">
            <fieldset>
                <legend><?= sysTranslations::get('redirectRegularExpression') ?></legend>
                <form method="POST" action="" class="validateForm">
                    <?= CSRFSynchronizerToken::field() ?>
                    <input type="hidden" value="save" name="action"/>
                    <input type="hidden" value="2" name="type"/>
                    <table class="withForm">
                        <tr>
                            <td><?= sysTranslations::get('global_online') ?> *</td>
                            <td>
                                <input class="alignRadio {validate:{required:true}}" title="<?= sysTranslations::get('redirect_offline_online') ?>" type="radio" <?= $oRedirect->online ? 'CHECKED' : '' ?> id="online_1" name="online"
                                       value="1"/> <label for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                                <input class="alignRadio {validate:{required:true}}" title="<?= sysTranslations::get('redirect_offline_online') ?>" type="radio" <?= !$oRedirect->online ? 'CHECKED' : '' ?> id="online_0" name="online"
                                       value="0"/> <label for="online_0"><?= sysTranslations::get('global_no') ?></label>
                            </td>
                            <td><span class="error"><?= $oRedirect->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="pattern"><?= sysTranslations::get('redirectPattern') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('redirect_fill_in_expresion_example') ?>: <br /><br />/nieuws\/(\d{1,})\/(([a-z])|([a-z-0-9\-])?)">&nbsp;</div>
                            </td>
                            <td colspan="2">
                                <input class="default {validate:{required:true}}" title="<?= sysTranslations::get('redirect_fill_in_expresion') ?>" id="pattern" type="text" autocomplete="off" name="pattern"
                                       value="<?= $oRedirect->pattern ?>"/>
                            </td>
                            <td><span class="error"><?= $oRedirect->isPropValid("pattern") ? '' : sysTranslations::get('redirect_url_not_allowed') ?></span></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="newUrl"><?= sysTranslations::get('redirectsNewUrl') ?></label>
                                <div class="hasTooltip tooltip" title="<?= sysTranslations::get('redirect_new_url_example') ?>: <br /><br />/news/$1/$2 <br /><br />of:<br /><br />/artikelen/2/artikel">&nbsp;</div>
                            </td>
                            <td colspan="2"><input class="default {validate:{required:true}}" title="<?= sysTranslations::get('redirect_fill_in_destination_url') ?>" id="newUrl" type="text" autocomplete="off" name="newUrl"
                                                   value="<?= $oRedirect->newUrl ?>"/></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                                <input class="fancybox" type="button" value="<?= sysTranslations::get('redirectTestExpression') ?>" name="testPopUp"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </div>
        <div class="contentColumn" id="fancybox-popup-form" style="display: none;">
            <fieldset>
                <legend><?= sysTranslations::get('redirectTestOutput') ?></legend>
                <form method="get">
                    <input type="hidden" name="action" value="testExpression"/>
                    <table class="withForm">
                        <tr>
                            <td class="withLabel"><label for="testUrl">
                                    <div class="hasTooltip tooltip" title="<?= sysTranslations::get('redirect_url_test_example') ?>: <br /><br />/nieuws/1/testbericht">&nbsp;</div><?= sysTranslations::get('redirectURL') ?></label></td>
                            <td colspan="2"><input class="default" id="testUrl" type="text" autocomplete="off" name="testUrl" value=""/></td>
                        </tr>
                        <tr>
                            <td class="withLabel"><label for="testOutput"><?= sysTranslations::get('redirectOutput') ?></label></td>
                            <td colspan="2"><input class="default" id="testOutput" type="text" autocomplete="off" name="testOutput" value="" disabled/></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="submit" value="<?= sysTranslations::get('redirectTestExpression') ?>" name="testExpression" id="testExpression" data-securityid="<?= CSRFSynchronizerToken::get() ?>"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </div>
        <?php

    }
    ?>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('redirectBackToOverview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('redirectsNoSave') ?>)</span>
</div>
<?php

# define and add javascript to bottom
$sBottomJavascript = <<< EOT
<script type="text/javascript">
        $('.linkChoice').change(function(){
            var filled = null;
            $('.linkChoice').each(function(index, element){
                if($(element).val() != ''){
                    filled = element;
                }
            });
            if(filled === null){
                $('.linkChoice').attr('disabled', false);
            }else{
                $('.linkChoice').not(filled).attr('disabled', true);
            }
        });

        $('#link').keyup(function(){
            $(this).change();
        });

        $('#link').change();

        $('.helpButton').click(function(){
            if($('.cheatsheet').is(":visible")){
            $('.cheatsheet').slideUp("fast", function(){
                $('.cheatsheet').hide();
                });
        }
        else {
        $('.cheatsheet').show(function(){
        $('.cheatsheet').slideDown("fast");
            });
        }
        });


        $('#testExpression').click(function(e){
           $.ajax({
            type: "GET",
            url: '/dashboard/redirect/ajax-testRedirect',
            data: {
                pattern: $('#pattern').val(), newUrl: $('#newUrl').val(), testUrl: $('#testUrl').val(), SecurityID: $(this).data('securityid')
            },
            async: true,
            success: function(data){
            console.log(data);
        $('#testOutput').val(data);
            }
        });
        e.preventDefault();
        });

            $('.fancybox').click(function () {
        $.fancybox([
            { href : '#fancybox-popup-form' }
        ]);
    });

</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
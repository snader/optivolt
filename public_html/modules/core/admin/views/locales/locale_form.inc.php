<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <form method="POST" class="validateForm">
            <?= CSRFSynchronizerToken::field() ?>
            <input type="hidden" name="action" value="save"/>
            <fieldset>
                <legend><?= sysTranslations::get('locale') ?></legend>
                <table class="withForm">
                    <tr>
                        <td><?= sysTranslations::get('global_online') ?> *</td>
                        <td>
                            <input class="alignRadio required" title="<?= sysTranslations::get('locale_set_online') ?>" type="radio" <?= $oLocale->online ? 'CHECKED' : '' ?> id="online_1" name="online" value="1"/> <label
                                    for="online_1"><?= sysTranslations::get('global_yes') ?></label>
                            <input class="alignRadio required" title="<?= sysTranslations::get('locale_set_online') ?>" type="radio" <?= !$oLocale->online ? 'CHECKED' : '' ?> id="online_0" name="online" value="0"/> <label
                                    for="online_0"><?= sysTranslations::get('global_no') ?></label>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("online") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="languageId"><?= sysTranslations::get('locales_languageId') ?> *</label></td>
                        <td>
                            <select id="languageId" class="required default format-variable" name="languageId" title="<?= sysTranslations::get('locales_set_languageId') ?>">
                                <option data-code="" value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                <?php

                                foreach (LanguageManager::getLanguagesByFilter(['showAll' => true]) as $oLanguage) {
                                    echo '<option data-code="' . strtolower($oLanguage->code) . '" ' . ($oLanguage->languageId == $oLocale->languageId ? 'selected' : '') . ' value="' . $oLanguage->languageId . '">' . strtoupper(
                                            $oLanguage->code
                                        ) . ' (' . $oLanguage->getTranslations()->name . ')' . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("languageId") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="countryId"><?= sysTranslations::get('locales_countryId') ?> *</label></td>
                        <td>
                            <select id="countryId" class="required default" name="countryId" title="<?= sysTranslations::get('locales_set_countryId') ?>">
                                <option data-code="" value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                <?php

                                foreach (CountryManager::getCountriesByFilter(['showAll' => true]) as $oCountry) {
                                    echo '<option data-code="' . strtolower($oCountry->code) . '" ' . ($oCountry->countryId == $oLocale->countryId ? 'selected' : '') . ' value="' . $oCountry->countryId . '">' . strtoupper(
                                            $oCountry->code
                                        ) . ' (' . $oCountry->getTranslations()->name . ')' . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("countryId") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel" style="width: 116px;"><label for="domain"><?= sysTranslations::get('global_domain') ?> *</label></td>
                        <td><input id="domain" class="required autofocus default format-variable" name="domain" title="<?= sysTranslations::get('locales_set_domain') ?>" type="text" value="<?= _e($oLocale->domain) ?>"/></td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("domain") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="subdomain"><?= sysTranslations::get('locales_subdomain') ?> *</label></td>
                        <td class="withLabel">
                            <input <?= $oLocale->subdomain == 'language' ? 'checked' : '' ?> class="alignRadio required format-variable select-language subdomain" id="subdomain_language" name="subdomain" type="radio" value="language"/>
                            <label for="subdomain_language"><?= sysTranslations::get('locales_language') ?> <span class="select-language"><?= $oLocale->getLanguage() ? ' (' . strtolower(
                                            $oLocale->getLanguage()->code
                                        ) . ')' : '' ?></span></label><br/>
                            <input <?= $oLocale->subdomain == 'country' ? 'checked' : '' ?> class="alignRadio required format-variable select-country subdomain" id="subdomain_country" name="subdomain" type="radio" value="country"/> <label
                                    for="subdomain_country"><?= sysTranslations::get('locales_country') ?> <span class="select-country"><?= $oLocale->getCountry() ? ' (' . strtolower(
                                            $oLocale->getCountry()->code
                                        ) . ')' : '' ?></span></label><br/>
                            <input <?= !$oLocale->subdomain ? 'checked' : '' ?> class="alignRadio required format-variable subdomain" id="subdomain_no" name="subdomain" type="radio" value=""/> <label
                                    for="subdomain_no"><?= sysTranslations::get('locales_no_subdomain') ?> <span class="subdomain"></span></label>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("subdomain") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="prefix1"><?= sysTranslations::get('locales_prefix1') ?> *</label></td>
                        <td class="withLabel">
                            <input <?= $oLocale->prefix1 == 'language' ? 'checked' : '' ?> class="alignRadio required format-variable select-language prefix1" id="prefix1_language" name="prefix1" type="radio" value="language"/> <label
                                    for="prefix1_language"><?= sysTranslations::get('locales_language') ?> <span class="select-language"><?= $oLocale->getLanguage() ? ' (' . strtolower(
                                            $oLocale->getLanguage()->code
                                        ) . ')' : '' ?></span></label><br/>
                            <input <?= $oLocale->prefix1 == 'country' ? 'checked' : '' ?> class="alignRadio required format-variable select-country prefix1" id="prefix1_country" name="prefix1" type="radio" value="country"/> <label
                                    for="prefix1_country"><?= sysTranslations::get('locales_country') ?> <span class="select-country"><?= $oLocale->getCountry() ? ' (' . strtolower(
                                            $oLocale->getCountry()->code
                                        ) . ')' : '' ?></span></label><br/>
                            <input <?= !$oLocale->prefix1 ? 'checked' : '' ?> class="alignRadio required format-variable prefix1" id="prefix1_no" name="prefix1" type="radio" value=""/> <label for="prefix1_no"><?= sysTranslations::get(
                                    'locales_no_prefix'
                                ) ?> <span class="prefix1"></span></label>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("prefix1") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="prefix2"><?= sysTranslations::get('global_prefix2') ?> *</label></td>
                        <td class="withLabel">
                            <input <?= $oLocale->prefix2 == 'country' ? 'checked' : '' ?> class="alignRadio required format-variable select-country prefix2" id="prefix2_country" name="prefix2" type="radio" value="country"/> <label
                                    for="prefix2_country"><?= sysTranslations::get('locales_country') ?> <span class="select-country"><?= $oLocale->getCountry() ? ' (' . strtolower(
                                            $oLocale->getCountry()->code
                                        ) . ')' : '' ?></span></label><br/>
                            <input <?= !$oLocale->prefix2 ? 'checked' : '' ?> class="alignRadio required format-variable prefix2" id="prefix2_no" name="prefix2" type="radio" value=""/> <label for="prefix2_no"><?= sysTranslations::get(
                                    'locales_no_prefix'
                                ) ?> <span class="prefix2"></span></label>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("prefix2") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td class="withLabel"><?= sysTranslations::get('locales_URLFormat') ?></td>
                        <td>
                            <input class="default required" id="urlFormat" name="urlFormat" readonly type="text" value="<?= $oLocale->getURLFormat(); ?>"/> <em>(<?= strtolower(sysTranslations::get('global_readonly')) ?>)</em>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("urlFormat") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td class="withLabel"><label for="dateFormat"><?= sysTranslations::get('locales_date_format') ?> *</label></td>
                        <td>
                            <select id="dateFormat" class="required default" name="dateFormat" title="<?= sysTranslations::get('locales_set_date_format') ?>">
                                <option value="">-- <?= sysTranslations::get('global_make_choice') ?> --</option>
                                <option value="<?= ACMS\Locale::DATE_FORMAT_NL ?>" <?= (ACMS\Locale::DATE_FORMAT_NL == $oLocale->dateFormat ? 'selected' : '') ?>>dd-mm-YYYY</option>
                                <option value="<?= ACMS\Locale::DATE_FORMAT_EN ?>" <?= (ACMS\Locale::DATE_FORMAT_EN == $oLocale->dateFormat ? 'selected' : '') ?>>mm-dd-YYYY</option>
                            </select>
                        </td>
                        <td class="withLabel"><span class="error"><?= $oLocale->isPropValid("countryId") ? '' : sysTranslations::get('global_field_incorrect') ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

$sControllerPath = ADMIN_FOLDER . '/' . http_get('controller');
$sJavascript     = <<<EOT
<script>

    $('#countryId').change(function(e){
        var code = $(this).find('option:selected').data('code');

        // set val of radios
        $('span.select-country').html(' (' + code + ')');
    });

    $('#languageId').change(function(e){
        var code = $(this).find('option:selected').data('code');

        // set val of radios
        $('span.select-language').html(' (' + code + ')');
    });

    $('.format-variable').on('click change keyup', function (e) {
        var domain = $('#domain').val();
        var subdomain = $('#' + $('input.subdomain:checked').val() + 'Id').find('option:selected').data('code');
        var prefix1 = $('#' + $('input.prefix1:checked').val() + 'Id').find('option:selected').data('code');
        var prefix2 = $('#' + $('input.prefix2:checked').val() + 'Id').find('option:selected').data('code');
        var URLFormat = (subdomain ? subdomain + '.' : '') + domain + (prefix1 ? '/' + prefix1 : '') + (prefix2 ? '_' + prefix2 : '');
        $('#urlFormat').val(URLFormat);
    });

    // check form option dependencies
    function resetFromOptions(element){
        var subdomain = $('input.subdomain:checked').val();
        var prefix1 = $('input.prefix1:checked').val();
        var prefix2 = $('input.prefix2:checked').val();

        // set each option
        $('#subdomain_language').prop('disabled', $('.select-language:checked:not(#subdomain_language)').length);
        $('#subdomain_country').prop('disabled', $('.select-country:checked:not(#subdomain_country)').length);

        $('#prefix1_language').prop('disabled', $('.select-language:checked:not(#prefix1_language)').length);
        $('#prefix1_country').prop('disabled', $('.select-country:checked:not(#prefix1_country)').length);
        $('#prefix1_no').prop('disabled', $('input.prefix2:not(#prefix2_no):checked').length);

        $('#prefix2_country').prop('disabled', $('.select-country:checked:not(#prefix2_country)').length || $('#prefix1_no:checked').length);

    }

    resetFromOptions();

    $('input.subdomain, input.prefix1, input.prefix2').click(function(e){
        resetFromOptions();
    });

    $('#languageId, #countryId').each(function(){
        $(this).rules('add', {
            remote: {
               url: '$sControllerPath/ajax-checkLanguageCountryCombination',
               type: 'post',
               data: {
                   localeId: '$oLocale->localeId',
                   languageId: function(){ return $('#languageId').val() },
                   countryId: function(){ return $('#countryId').val() }
               }
           }
       });
    });

    $('#urlFormat').each(function(){
        $(this).rules('add', {
            remote: {
                depends: function(element) {
                    return $('#languageId').val() && $('#languageId').valid() && $('#countryId').val() && $('#countryId').valid();
                },
                param: {
                    url: '$sControllerPath/ajax-checkURLFormat',
                    type: 'post',
                    data: {
                        localeId: '$oLocale->localeId',
                        languageId: function(){ return $('#languageId').val() },
                        countryId: function(){ return $('#countryId').val() },
                        domain: function(){ return $('#domain').val() },
                        subdomain: function(){ return $('input.subdomain:checked').val() },
                        prefix1: function(){ return $('input.prefix1:checked').val() },
                        prefix2: function(){ return $('input.prefix2:checked').val() }
                   }
               }
           }
       });
    });
</script>
EOT;
$oPageLayout->addJavascript($sJavascript);

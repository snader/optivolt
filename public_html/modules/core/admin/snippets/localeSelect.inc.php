<div style="float: left;">
    <?php

    if ($oCurrentUser) {
        if (count($aLocales) > 1) {
            ?>
            <form id="setLocaleForm" method="POST">
                <?= sysTranslations::get('global_select_country_language') ?>:
                <select id="localeId" name="localeId">
                    <?php

                    foreach ($aLocales AS $oLocaleForSelect) {
                        ?>
                        <option value="<?= $oLocaleForSelect->localeId ?>" <?= (AdminLocales::locale() == $oLocaleForSelect->localeId) ? 'selected' : '' ?>><?= _e($oLocaleForSelect->getCountry()->nativeName) ?> - <?= _e(
                                $oLocaleForSelect->getLanguage()->nativeName
                            ) ?></option>
                        <?php

                    }
                    ?>
                </select>
                <input type="hidden" name="action" value="setAdminLocale"/>
            </form>
            <?php

        } elseif (count($aLocales) == 1) {
            ?>
            <?= sysTranslations::get('global_country_language') ?>: <?= _e($aLocales[0]->getCountry()->nativeName) ?> - <?= _e($aLocales[0]->getLanguage()->nativeName) ?>
            <?php

        } else {
            ?>
            <a href="<?= ADMIN_FOLDER ?>/locales/toevoegen"><?= sysTranslations::get('global_create_local_first') ?></a>
            <?php

        }
    }
    ?>
</div>
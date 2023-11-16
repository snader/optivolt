<?php $aLocales = LocaleManager::getLocalesByFilter(['showAll' => true]); ?>
<h1><?= sysTranslations::get('core_languages') ?></h1>
<form method="POST" action="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>/save" class="validateForm">
    <table class="sorted withForm">
        <thead>
        <tr>
            <th class="{sorter:false} nonSorted">Native</th>
            <?php foreach($aLocales as $oLocale) { ?>
                <th class="{sorter:false} nonSorted"><?= $oLocale->getLocale() ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php /** @var \Country $oCountry */foreach ($aCountries as $oCountry) { ?>
            <tr>
                <td>
                    <?= $oCountry->nativeName ?>
                </td>
                <?php foreach($aLocales as $oLocale) { ?>
                    <td>
                        <?php if($oTranslation = $oCountry->getTranslations($oLocale->localeId)) { ?>
                        <input type="text" name="translation[<?= $oTranslation->countryTranslationId ?>]" value="<?= $oTranslation->name ?>" />
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="<?= 1 + count($aLocales) ?>">
                <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
            </td>
        </tr>
        </tbody>
    </table>
</form>

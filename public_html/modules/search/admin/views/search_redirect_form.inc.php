<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('searchredirect_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<div class="cf">
    <div class="contentColumn">
        <fieldset>
            <legend><?= sysTranslations::get('searchredirect_item') ?></legend>
            <form method="POST" action="" class="validateForm">
                <?= CSRFSynchronizerToken::field() ?>
                <input type="hidden" value="save" name="action"/>
                <table class="withForm">
                    <tr>
                        <td class="withLabel"><label for="searchword"><?= sysTranslations::get('searchredirect_searchword') ?> * </label></td>
                        <td><input class="required default" id="searchword" type="text" autocomplete="off" name="searchword" value="<?= _e($oSearchRedirect->searchword) ?>"
                                   title="<?= sysTranslations::get('searchredirect_no_searchword') ?>"/></td>
                        <td><span class="error"><?= $oSearchRedirect->isPropValid("searchword") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <tr <?= ($oSearchRedirect->languageId ? 'class="hide"' : '') ?> >
                        <td class="withLabel"><label for="languageId"><?= sysTranslations::get('locales_language') ?> * </label></td>
                        <td>
                            <select class="required default" title="<?= sysTranslations::get('locales_set_languageId') ?>" id="languageId" name="languageId">
                                <?php

                                foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) AS $oLocale) {
                                    ?>
                                    <option value="<?= $oLocale->languageId ?>" <?= ($oLocale->languageId == $oSearchRedirect->languageId) ? 'selected' : '' ?>><?= _e($oLocale->getLanguage()->nativeName) ?></option>
                                    <?php

                                }
                                ?>
                            </select>
                        </td>
                        <td><span class="error"><?= $oLocale->isPropValid("languageId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                    </tr>
                    <?php

                    if ($oSearchRedirect->languageId) {
                        ?>
                        <tr>
                            <td><label for="languageId"><?= sysTranslations::get('locales_language') ?> * </label></td>
                            <td>
                                <?= $oSearchRedirect->getLanguage()->nativeName ?>
                            </td>
                            <td><span class="error"><?= $oLocale->isPropValid("languageId") ? '' : sysTranslations::get('global_field_not_completed') ?></span></td>
                        </tr>
                        <?php if (moduleExists('pages')) { ?>
                            <tr>
                                <td class="withLabel"><label><?= sysTranslations::get('searchredirect_link_page') ?></label></td>
                                <td>
                                    <select name="pageId" class="linkChoice default">
                                        <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                        <?php

                                        function generateOptions($aPages, &$oSearchRedirect)
                                        {
                                            foreach ($aPages as $oPage) {
                                                $sLeadingChars = '';
                                                for ($iC = $oPage->level; $iC > 1; $iC--) {
                                                    $sLeadingChars .= '--';
                                                }
                                                echo '<option value="' . $oPage->pageId . '" ' . ($oPage->pageId == $oSearchRedirect->pageId ? 'selected' : '') . '>' . $sLeadingChars . $oPage->getShortTitle() . ' (' . $oPage->getLanguage(
                                                    )->code . ')</option>';
                                                generateOptions($oPage->getSubPages('online-all'), $oSearchRedirect);
                                            }
                                        }

                                        generateOptions(
                                            PageManager::getPagesByFilter(
                                                ['showAll' => 1, 'online' => 1, 'level' => 1, 'languageId' => $oSearchRedirect->languageId],
                                                null,
                                                0,
                                                $iFoundRows,
                                                ['`p`.`languageId`' => 'ASC', '`p`.`order`' => 'ASC', '`p`.`pageId`' => 'ASC']
                                            ),
                                            $oSearchRedirect
                                        );
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (moduleExists('newsItems')) { ?>
                            <tr>
                                <td class="withLabel"><label><?= sysTranslations::get('searchredirect_link_news') ?></label></td>
                                <td>
                                    <select class="linkChoice default" name="newsItemId" id="newsItemId">
                                        <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                        <?php

                                        if (NewsItemManager::getNewsItemsByFilter()) {
                                            foreach (NewsItemManager::getNewsItemsByFilter(
                                                ['languageId' => $oSearchRedirect->languageId],
                                                null,
                                                0,
                                                $iFoundRows,
                                                ['`ni`.`languageId`' => 'ASC', '`ni`.`date`' => 'DESC', '`ni`.`newsItemId`' => 'DESC']
                                            ) as $oNewsItem) {
                                                echo '<option value="' . $oNewsItem->newsItemId . '" ' . ($oNewsItem->newsItemId == $oSearchRedirect->newsItemId ? 'selected' : '') . '>' . $oNewsItem->title . ' (' . $oNewsItem->getLanguage(
                                                    )->code . ')</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (moduleExists('photoAlbums')) { ?>
                            <tr>
                                <td class="withLabel"><label><?= sysTranslations::get('searchredirect_link_photoalbum') ?></label></td>
                                <td>
                                    <select class="linkChoice default" name="photoAlbumId" id="photoAlbumId">
                                        <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                        <?php

                                        if (PhotoAlbumManager::getPhotoAlbumsByFilter()) {
                                            foreach (PhotoAlbumManager::getPhotoAlbumsByFilter(
                                                ['languageId' => $oSearchRedirect->languageId],
                                                null,
                                                0,
                                                $iFoundRows,
                                                ['`pa`.`languageId`' => 'ASC', '`pa`.`date`' => 'DESC', '`pa`.`photoAlbumId`' => 'DESC']
                                            ) as $oPhotoAlbum) {
                                                echo '<option value="' . $oPhotoAlbum->photoAlbumId . '" ' . ($oPhotoAlbum->photoAlbumId == $oSearchRedirect->photoAlbumId ? 'selected' : '') . '>' . $oPhotoAlbum->title . ' (' . $oPhotoAlbum->getLanguage(
                                                    )->code . ')</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (moduleExists('catalog')) { ?>
                            <tr>
                                <td class="withLabel"><label><?= sysTranslations::get('searchredirect_link_product') ?></label></td>
                                <td>
                                    <select class="linkChoice default" name="catalogProductId" id="catalogProductId">
                                        <option value=""><?= sysTranslations::get('global_make_choice') ?></option>
                                        <?php

                                        if (CatalogProductManager::getProductsByFilter()) {
                                            foreach (CatalogProductManager::getProductsByFilter(['languageId' => $oSearchRedirect->languageId]) as $oCatalogProduct) {
                                                echo '<option value="' . $oCatalogProduct->catalogProductId . '" ' . ($oCatalogProduct->catalogProductId == $oSearchRedirect->catalogProductId ? 'selected' : '') . '>' . $oCatalogProduct->getTranslations(
                                                        'auto-admin'
                                                    )->name . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php

                    } ?>
                    <tr>
                        <td colspan="3">
                            <input type="submit" value="<?= sysTranslations::get('global_save') ?>" name="save"/>
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>
</div>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('searchredirect_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# define and add javascript to bottom
$sBottomJavascript = <<<EOT
<script type="text/javascript">
    $('.linkChoice').change(function(){
        var filled = null;
        $('.linkChoice').each(function(index, element){
            if($(element).val() != ''){
                filled = element;
            }
        });
        if(filled === null){
            $('.linkChoice').prop('disabled', false);
        }else{
            $('.linkChoice').not(filled).prop('disabled', true);
        }
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>

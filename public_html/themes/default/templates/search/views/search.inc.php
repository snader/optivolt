<section class="section">
    <!-- Navigation Breadcrumbs -->
    <?php include getSiteSnippet('navigationBreadcrumbs'); ?>
    <!-- /Navigation Breadcrumbs -->
    <div class="container">
        <div class="columns">
            <!-- Navigation sub -->
            <?php include getSiteSnippet('navigationSub'); ?>
            <!-- Navigation sub -->

            <div class="column <?= !empty($oPageForMenu) ? 'is-three-quarters' : '' ?>">
                <div class="content">
                    <h1 class="title is-size-1"><?= _e($oPage->title) ?></h1>
                    <?= $oPage->intro ?>
                    <?= $oPage->content ?>
                </div>
                <form action="<?= getCurrentUrl() ?>" method="get">
                    <input type="hidden" name="action" value="search"/>
                    <div class="field has-addons">
                        <div class="control">
                           <input class="input" type="text" name="q" value="<?= Session::get('search_word') ?>"/>
                        </div>
                        <div class="control">
                            <button class="button is-primary" name="search"><?= SiteTranslations::get('site_search') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="columns is-multiline">
            <?php if ((http_post('searchword') || http_session('search_word')) && count($aResults) < 1) { ?>
                <div class="column">
                    <p><?= SiteTranslations::get('site_search_no_results') ?></p>
                </div>
            <?php } elseif ((http_post('searchword') || http_session('search_word')) && count($aResults) > 0) {
                foreach ($aSearchResults as $oObject) {
                    $sObjectUrl = (method_exists($oObject, 'getBaseUrlPath') ? $oObject->getBaseUrlPath() : $oObject->getTranslations()
                        ->getBaseUrlPath());

                    $oImageFile           = null;
                    $oImage               = null;
                    $bDisplayImage        = false;
                    $aImageFilePreference = ['crop_small', 'crop_large', 'detail'];

                    if (method_exists($oObject, 'getImages')) {
                        $oImage = $oObject->getImages('first-online');
                        if ($oImage) {
                            foreach ($aImageFilePreference as $sReference) {
                                $oImageFile = $oImage->getImageFileByReference($sReference);
                                if ($oImageFile) {
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                    <div class="column is-half-tablet is-3-widescreen <?= $oObject->searchClass ?>">
                        <div class="card item js-match-min-height">
                            <div class="card-image">
                                <figure class="image is-1by1">
                                    <?php if (!empty($oImageFile)) { ?>
                                        <img <?= $oImageFile->getImageAttr() ?> alt="<?= _e($oImageFile->title) ?>">
                                    <?php } else { ?>
                                        <img src="https://place-hold.it/400x400" alt="">
                                    <?php } ?>
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="content" data-mh="content">
                                    <h2 class="subtitle is-size-4"><?= getTitle($oObject) ?></h2>
                                    <p><?= firstXWords(getContent($oObject), 30) ?></p>
                                    <?php if ($oObject->searchClass == "CatalogProduct") { ?>
                                        <span class="is-size-5"><?= decimal2valuta($oObject->getSalePrice(true)) ?></span>
                                    <?php } ?>
                                </div>
                                <a href="<?= $sObjectUrl ?>" class="button is-primary"><?= SiteTranslations::get('site_read_more') ?></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="columns">
            <div class="column">
                <!-- Pagination -->
                <?= generatePaginationHTML($iPageCount, $iCurrPage) ?>
                <!-- /Pagination -->
                <!-- Media -->
                <?php include getSiteSnippet('videos'); ?>
                <?php include getSiteSnippet('files'); ?>
                <?php include getSiteSnippet('links'); ?>
                <!-- /Media -->
            </div>
        </div>
    </div>
</section>

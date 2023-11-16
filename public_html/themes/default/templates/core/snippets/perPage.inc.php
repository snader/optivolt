<div class="per-page">
    <form method="POST">
        <input type="hidden" name="setPerPage" value="1"/>
        <div class="control">
            <div class="select is-small">
                <select name="perPage" onchange="$(this).closest('form').submit();">
                    <option value=""><?= _e(SiteTranslations::get('site_all')) ?></option>
                    <option <?= $iPerPage == 6 ? 'SELECTED' : '' ?> value="6">6</option>
                    <option <?= $iPerPage == 12 ? 'SELECTED' : '' ?> value="12">12</option>
                    <option <?= $iPerPage == 24 ? 'SELECTED' : '' ?> value="24">24</option>
                    <option <?= $iPerPage == 48 ? 'SELECTED' : '' ?> value="48">48</option>
                    <option <?= $iPerPage == 96 ? 'SELECTED' : '' ?> value="96">96</option>
                </select>
            </div>
            <span><?= _e(SiteTranslations::get('site_per_page')) ?></span>
        </div>
    </form>
</div>
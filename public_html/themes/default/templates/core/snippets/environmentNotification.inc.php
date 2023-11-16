<!-- Environment notification -->
<div class="environment-notification">
    <div class="actions">
        <a title="<?= _e(SiteTranslations::get('site_close_environment_warning')) ?>" class="js-close-environment-notification" href="#">
            <i class="fas fa-times-circle"></i>
        </a>
    </div>
    <div class="env-message">
        <?= _e(SiteTranslations::get('site_environment_warning')) ?>
    </div>
    <div class="environment">
        <?= ENVIRONMENT ?>
    </div>
</div>
<!-- /Environment notification -->
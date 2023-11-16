<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout = new PageLayout();

// get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

$oPageLayout->sWindowTitle = sysTranslations::get('settings_sitemap');
$oPageLayout->sModuleName  = sysTranslations::get('settings_sitemap');
$oPageLayout->sViewPath    = getAdminView('advancedSitemap_overview', 'sitemaps');

if ($oCurrentUser->isAdmin() && http_get('executeCron') == 1 && CSRFSynchronizerToken::validate()) {
    ob_start();
    include DOCUMENT_ROOT . getAdminPath('cronjobs/advancedSitemap.cron.php', 'sitemaps');
    $sExecuteLog              = ob_get_clean();
    $_SESSION['statusUpdate'] = sysTranslations::get('sitemap_cron_performed');
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
}

// include default template
include_once getAdminView('layout');
?>
<div class="alert alert-danger" role="alert" <?= !isset($aErrors) || empty($aErrors) ? 'style="display:none;"' : '' ?>>
    <p><strong><?= _e(SiteTranslations::get('site_information_missing_or_incorrect')) ?></strong></p>
    
            <?php

            if (isset($aErrors) && !empty($aErrors)) {
                foreach ($aErrors AS $sField => $sError) {
                    echo '<div style="font-weight:normal;">- ' . $sError . '</div>';
                }
            }
            ?>
       
</div>

<div class="errorBox" <?= empty($aErrors) ? '' : 'style="display:block;"' ?>>
    <div class="title"><?= sysTranslations::get('errorbox_data_is_missing') ?></div>
    <p><?= sysTranslations::get('errorbox_cannot_send_form') ?></p>
    <ul>
        <?php

        if (!empty($aErrors)) {
            foreach ($aErrors AS $sField => $sError) {
                echo '<li><label for="' . $sField . '" generated="true" class="error">' . $sError . '</label></li>';
            }
        }
        ?>
    </ul>
</div>
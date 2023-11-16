<br class="clear">
<div class="errorBox" style="display:block;">

    <div class="title"><?= sysTranslations::get('imagemanager_webservices_error_notice_title') ?></div>

    <p><?php

        echo sysTranslations::get('imagemanager_webservices_isUp_errror_notice');

        if (!isDeveloper()) {
            echo '<br/>'.sysTranslations::get('imagemanager_webservices_isUp_errror_notice_end_user');
        }
        ?>


    </p>

</div>
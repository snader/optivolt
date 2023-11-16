<div>
    <div style="margin: 10px;">
        <?php

        if ($oCurrentUser->isAdmin()) {
            echo '<a href="?executeCron=1&'. CSRFSynchronizerToken::query() .'">Execute CRON</a>';
        }
        echo '<pre>';
        echo 'CRON log:' . PHP_EOL;
        //reverse log file
        $sFile = DOCUMENT_ROOT . CronManager::LOG_FOLDER . '/advancedSitemap.log';
        if (file_exists($sFile)) {
            $aLines = file($sFile);
            $aLines = array_reverse($aLines);
            echo implode('', $aLines);
        }
        echo '</pre>';
        ?>
    </div>
</div>
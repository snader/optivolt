<h1><?= sysTranslations::get('dev_build') ?></h1>
<?php

if ($aClassManifest) {
    ?>
    <h2>Found the following <?= count($aClassManifest) ?> classes</h2>
    <ul>
        <?php

        foreach ($aClassManifest as $sClass => $sPath) {
            ?>
            <li><?= $sClass ?></li>
            <?php

        }
        ?>
    </ul>
    <?php

}
?>
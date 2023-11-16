<?php if (isset($flush) && $flush) : ?>
    <?php include(getSiteView('dev/flush')); ?>
    <?php if ($cache) {
        include(getSiteView('dev/cache'));
    } ?>
<?php endif; ?>
    <h1>Class Manifest Build</h1>
<?php if ($aClassManifest) : ?>
    <h2>Found the following <?= count($aClassManifest) ?> classes</h2>
    <ul>
        <?php foreach ($aClassManifest as $sClass => $sPath) : ?>
            <li><?= $sClass ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
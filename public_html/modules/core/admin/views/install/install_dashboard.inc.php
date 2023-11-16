<h1><?= sysTranslations::get('install_installation') ?></h1>

<?php if (!empty($aLogs)) { ?>
    [ <a id="install-all" class="btn-default" href="?install=true&module=all-modules"><?= sysTranslations::get('core_install_all') ?></a> ]
    &nbsp;&nbsp;[ <a id="gen-build" class="btn-default" href="/dashboard/gen/build">Build system configuration files</a> ]

    <div style="margin-top: 15px;" class="install-messages">
        <!--
        <?php if (!moduleExists('themes')) { ?>
            <?php if (in_array(SITE_TEMPLATE, SITE_SUPPORTED_TEMPLATES)) { ?>
                <div><h1><?= ucfirst(SITE_TEMPLATE) ?> <?= sysTranslations::get('core_template_installer') ?></h1></div>
                <form class="module-selector" method="post">
                    <?php foreach ($aLogs as $sModule => $aErrors) {

                        $bKeep = false;
                        if (in_array($sModule, ThemeHelper::$baseModules) || in_array($sModule, $aSupportedModules) || is_dir(DOCUMENT_ROOT . SITE_MODULES_FOLDER . '/' . $sModule . '/site/' . SITE_TEMPLATE)) {
                            $bKeep = true;
                        } ?>

                        <div class="module-item">
                            <input id="<?= $sModule ?>_checkbox" <?= in_array($sModule, ThemeHelper::$baseModules) ? 'disabled' : '' ?> type="checkbox" name="keepModule[<?= $sModule ?>]" <?= $bKeep ? 'checked' : '' ?>>
                            <label for="<?= $sModule ?>_checkbox"><?= $sModule ?></label>
                        </div>

                    <?php } ?>
                </form>
                <hr style="margin-bottom: 0;"/>
            <?php } else { ?>
                <h1 style="color:red;"><?= sprintf(sysTranslations::get('core_template_does_not_exist'), ucfirst(SITE_TEMPLATE))  ?></h1>
            <?php } ?>
        <?php } ?>
            -->
        <ul style="list-style-type: none;">
            <li><?= sysTranslations::get('core_total_errors') ?>: <?= $iErrors ?></li>
            <li><?= sysTranslations::get('core_total_warnings') ?>: <?= $iWarnings ?></li>
        </ul>

        <hr style="margin-bottom: 0;" />
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php

            foreach ($aLogs as $sModule => $aErrors) {
                echo '<div class="panel panel-default">';

                echo '<div class="panel-heading" role="tab" id="heading1">
                                <h4 class="panel-title ' . (count($aErrors) == 1 ? 'green' : 'red') . '"">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . slugify($sModule) . '" aria-expanded="true" aria-controls="collapse' . slugify($sModule) . '">
                                    ' . $sModule . '
                                    </a>
                                </h4>
                            </div>';

                echo '<div id="collapse' . slugify($sModule) . '" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading' . slugify($sModule) . '">
                                <div class="panel-body">';

                if (!empty($aLogs[$sModule]['errors'])) {
                    echo '<a id="install-' . $sModule . '" href="?install=true&module=' . $sModule . '" style="margin-left: 20px;">[Install `' . $sModule . '`]</a>';
                }
                foreach ($aErrors as $sType => $aMsgs) {
                    echo '<div class="type-' . $sType . '">';
                    foreach ($aMsgs as $sMsg) {
                        echo '<div class="msg">' . $sMsg . '</div>';
                    }
                    echo '</div>';
                }

                echo '      </div>
                            </div>';


                echo '</div>';
            }
            ?>
        </div>
    </div>
<?php }

$sBottomJavascript = <<<EOT
<script>
    $('#install-all, #install-themes').click(function(e) {
        var form = $('form.module-selector');
            if (form.length > 0){
              e.preventDefault();
              form.attr('action', $(this).attr('href')).submit();
            }
    });
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);

?>
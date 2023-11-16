<h1><?= sysTranslations::get('redirectImport') ?></h1>
<div class="errorBox" <?= !empty($aErrors) ? 'style="display:block;"' : 'style="display: none;"' ?>>
    <div class="title"><?= sysTranslations::get('followingError') ?></div>
    <ul>
        <?php

        foreach ($aErrors AS $sField => $sError) {
            echo '<li><label for="' . $sField . '" style="display: block;">' . $sError . '</label></li>';
        }
        ?>
    </ul>
</div>
<?php if (!empty($aLogs)) { ?>
    <div class="import-messages">
        <h2><?= sysTranslations::get('redirectResult') ?> <?= $iSaved . '/' . $iTotal ?> <?= sysTranslations::get('redirectSaved') ?></h2>
        <ul style="margin-left: 16px;">
            <li><?= sysTranslations::get('redirectTotal') ?> <?= sysTranslations::get('redirectRows') ?>: <?= $iTotal ?></li>
            <li><?= sysTranslations::get('redirectTotal') ?> <?= sysTranslations::get('redirectErrors') ?>: <?= $iErrors ?></li>
            <li><?= sysTranslations::get('redirectTotal') ?> <?= sysTranslations::get('redirectWarnings') ?>: <?= $iWarnings ?></li>
            <li><?= sysTranslations::get('redirectTotal') ?> <?= sysTranslations::get('redirectSaved') ?>: <?= $iSaved ?></li>
        </ul>
        <?php

        foreach ($aLogs AS $iRow => $aErrors) {
            echo '<div class="row ' . ($iRow == 2 ? 'first' : '') . '">';
            foreach ($aErrors AS $sType => $aMsgs) {
                echo '<div class="type-' . $sType . '">';
                foreach ($aMsgs AS $sMsg) {
                    echo '<div class="msg">' . $sMsg . '</div>';
                }
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
    </div>
<?php } ?>
<form method="POST" enctype="multipart/form-data">
    <?= CSRFSynchronizerToken::field() ?>
    <input name="action" type="hidden" value="import"/>
    <table class="withForm">
        <tr>
            <td class="withLabel" style="width: 180px;"><label for="file"><?= sysTranslations::get('redirectFile') ?></label></td>
            <td>
                <input name="file" type="file"/>
            </td>
        </tr>
        <tr>
            <td><?= sysTranslations::get('redirestTestmode') ?></td>
            <td>
                <input checked class="alignCheckbox" id="testMode" name="testMode" type="checkbox" value="1"/> <label for="testMode"><?= sysTranslations::get('global_yes') ?></label>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="2">
                <input type="submit" value="Start" name="save"/>
            </td>
        </tr>
    </table>
</form>
<hr/>
<div>
    <h2><?= sysTranslations::get('redirectSupportedColumns') ?></h2>|
    <?php

    foreach ($aImportFields AS $aField) {
        $bRequiredColumn = in_array($aField['column'], $aRequiredColumns);

        if ($bRequiredColumn) {
            echo '<b><u>' . $aField['column'] . '</u></b> | ';
        } elseif ($aField['required']) {
            echo '<u>' . $aField['column'] . '</u> | ';
        } else {
            echo $aField['column'] . ' | ';
        }
    }
    ?>
</div>
<div style="margin-top: 20px;">
    - <b><u><?= sysTranslations::get('redirectMandatoryColumns') ?></u></b> <?= sysTranslations::get('redirectMandatoryColumnExplenation') ?>.<br/>
    - <u><?= sysTranslations::get('redirectMandatoryFilledColumns') ?></u> <?= sysTranslations::get('redirectMandatoryFilledColumnsExplenation') ?>.<br/>
    - <?= sysTranslations::get('redirectImportExplenation') ?>.<br/>
</div>
<?php
/** @var array $aCustomErrorBoxErrors */
/** @var string $sCustomErrorBoxTitle */
/** @var string $sCustomErrorBoxContent */
?>
<div class="errorBox" <?= empty($aCustomErrorBoxErrors) ? '' : 'style="display:block;"' ?>>
    <div class="title"><?= $sCustomErrorBoxTitle ?></div>
    <?= !empty($sCustomErrorBoxContent) ? '<p>' . $sCustomErrorBoxContent . '</p>' : '' ?>
    <ul>
        <?php

        if (!empty($aCustomErrorBoxErrors)) {
            foreach ($aCustomErrorBoxErrors as $sError) {
                echo '<li>' . $sError . '</li>';
            }
        }
        ?>
    </ul>
</div>

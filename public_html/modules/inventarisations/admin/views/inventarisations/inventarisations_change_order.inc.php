<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('global_change_order') ?></h1>
<p>
    <i><?= sysTranslations::get('inventarisation_drag') ?></i>
</p>
<div id="sortableContainer">
    <?php
    # list pages in unordered list

    if (count($aInventarisations) > 0) {
        echo '<ol id="itemSorter" class="sortable cursorMove">';
    }
    $iT = 0;
    foreach ($aInventarisations AS $oInventarisation) {
        echo '<li data-itemid="' . $oInventarisation->inventarisationId . '">';
        echo '<div>';
        echo $oInventarisation->title;
        echo '</div>';
        echo '</li>';
        $iT++;
    }
    if (count($aInventarisations) > 0) {
        echo '</ol>';
    }
    ?>
</div>
<form action="" method="POST" onsubmit="return setOrder();" id="changeOrderForm">
    <input type="hidden" name="action" value="saveOrder"/>
    <input type="hidden" name="order" id="order" value=""/>
    <?= CSRFSynchronizerToken::field() ?>
    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/> <input type="button" value="<?= sysTranslations::get('global_reset') ?>" onclick="window.location.reload(); return false;"/></a>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= Request::getControllerSegment() ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# add nested sortable javascript initiation code
$sSortableJavascript = <<<EOT
<script>
    $('ol.sortable').sortable({
        axis: 'y',
        forcePlaceholderSize: true,
        helper: 'clone',
        items: 'li',
        cancel: '.not-sortable',
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tolerance: 'pointer',
        stop: function(){
            makeZebra('#itemSorter');
        }
        }).disableSelection();

    function setOrder(){
        var order = '';
        $('#itemSorter li').each(function(index, element){
            order += (order == '' ? '' : '|')+$(element).data('itemid');
        });
        $("#order").val(order);
        return true;
    }

    makeZebra('#itemSorter');
</script>
EOT;
$oPageLayout->addJavascript($sSortableJavascript);

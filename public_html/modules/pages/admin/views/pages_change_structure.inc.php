<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('pages_change_structure') ?></h1>
<p>
    <i><?= sysTranslations::get('pages_drag_title') ?></i>
</p>
<div id="nestedSortableContainer">
    <?php
    # list pages in unordered list

    function makeListTree($aPages, $iLevel, $iMaxLevels)
    {
        if (count($aPages) > 0 && $iLevel == 0) {
            echo '<ol class="nestedSortable cursorMove level' . $iLevel . '" id="root">';
        } elseif (count($aPages) > 0) {
            echo '<ol class="level' . $iLevel . '">';
        }

        $iT    = 0;
        $oPage = new Page();
        foreach ($aPages AS $oPage) {

            $sLiClass = '';
            $sLocked  = '';

            # main pages are locked by default
            if ($iLevel == 0) {
                //$sLiClass = 'locked';
                //$sLocked = ' (Vergrendeld)';
            }

            // lock parent if set in database
            $sData   = '';
            $oParent = $oPage->getParent();
            if ($oParent) {
                $sParentPageId = 'page_' . $oParent->pageId;
            } else {
                $sParentPageId = 'root';
            }
            // set current parent
            $sData .= ' data-parent="' . $sParentPageId . '"'; //prevent root from nesting

            // set lock parent
            if ($oPage->lockParent()) {
                $sData .= ' data-lockparent="1"'; // prevent from changing subs from parent
            }

            // set disable subs (disable new nesting)
            if (!$oPage->getMayHaveSub()) {
                $sData .= ' data-subsdisabled="1"';
            }

            echo '<li class="' . $sLiClass . '" id="page_' . $oPage->pageId . '" ' . $sData . '>';
            echo '<div class="no-action-icons ' . ($iT % 2 == 0 ? 'even' : 'odd') . '">';
            echo _e($oPage->getShortTitle()) . '<span class="brackedComment"> ' . $sLocked . '</span>';
            echo '</div>';

            makeListTree($oPage->getSubPages('all'), $iLevel + 1, $iMaxLevels); //call function recursive
            echo '</li>';
            $iT++;
        }
        if (count($aPages) > 0) {
            echo '</ol>';
        }
    }

    # start recursive displaying pages
    makeListTree($aAllLevel1Pages, 0, $iMaxLevels);
    ?>
</div>
<form action="" method="POST" onsubmit="return setPageStructure();" id="pageStructureForm">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="savePageStructure"/>
    <input type="hidden" name="pageStructure" id="pageStructure" value=""/>
    <input type="submit" value="<?= sysTranslations::get('global_save') ?>"/> <input type="button" value="<?= sysTranslations::get('global_reset') ?>" onclick="window.location.reload(); return false;"/>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# add nested sortable javascript source code
$oPageLayout->addJavascript('<script src="' . getAdminPath('plugins/jquery-ui-nestedSortable.min.js') . '"></script>');

# add nested sortable javascript initiation code
$sNestedSortableJavascript = <<<EOT
<script>
    $('ol.nestedSortable').nestedSortable({
        disableNesting: 'no-nesting',
        errorClass: 'ui-sortable-error',
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'clone',
        listType: 'ol',
        items: 'li:not(.locked)',
        cancel: '.not-sortable',
        maxLevels: $iMaxLevels,
        opacity: .6,
        placeholder: 'placeholder',
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div',
        rootClass: 'root-item',
        rootID: 'root',
        isAllowed: function(item, parent){
                // check if parent is locked
                if($(item).data('lockparent') && $(item).data('parent') !== $(parent).prop('id') && parent !== null){
                    return false;
                }

                // check if nesting is disabled
                if($(parent).data('subsdisabled') && $(item).data('parent') != $(parent).prop('id')){
                    return false;
                }

                return true;
            }
        }).disableSelection();

    function setPageStructure(){
        serialized = $('ol.nestedSortable').nestedSortable('serialize');
        $("#pageStructure").val(serialized);
        return true;
    }
</script>
EOT;
$oPageLayout->addJavascript($sNestedSortableJavascript);
?>
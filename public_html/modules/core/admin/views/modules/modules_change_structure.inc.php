<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('global_back_to_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<h1><?= sysTranslations::get('modules_change_structure') ?></h1>
<p>
    <i><?= sysTranslations::get('modules_drag_title') ?></i>
</p>
<div id="nestedSortableContainer">
    <?php
    # list modules in unordered list

    function makeListTree($aModules, $iLevel, $iMaxLevels)
    {
        if (count($aModules) > 0 && $iLevel == 0) {
            echo '<ol class="nestedSortable cursorMove level' . $iLevel . '">';
        } elseif (count($aModules) > 0) {
            echo '<ol class="level' . $iLevel . '">';
        }

        $iT      = 0;
        $oModule = new Module();
        foreach ($aModules AS $oModule) {
            echo '<li id="module_' . $oModule->moduleId . '">';
            echo '<div class="no-action-icons ' . ($iT % 2 == 0 ? 'even' : 'odd') . '">';
            echo sysTranslations::get($oModule->linkName) . '<span class="brackedComment"></span>';
            echo '</div>';

            makeListTree($oModule->getChildren('all'), $iLevel + 1, $iMaxLevels); //call function recursive
            echo '</li>';
            $iT++;
        }
        if (count($aModules) > 0) {
            echo '</ol>';
        }
    }

    # start recursive displaying modules
    makeListTree($aAllLevel1Modules, 0, $iMaxLevels);
    ?>
</div>
<form action="" method="POST" onsubmit="return setModuleStructure();" id="moduleStructureForm" style="margin-top: 10px;">
    <input type="hidden" name="action" value="saveModuleStructure"/>
    <input type="hidden" name="moduleStructure" id="moduleStructure" value=""/>
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

    function setModuleStructure(){
        serialized = $('ol.nestedSortable').nestedSortable('serialize');
        $("#moduleStructure").val(serialized);
        return true;
    }
</script>
EOT;
$oPageLayout->addJavascript($sNestedSortableJavascript);
?>
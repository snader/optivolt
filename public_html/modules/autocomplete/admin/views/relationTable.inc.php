<table class="sorted-delayed full-width">
    <thead>
    <tr>
        <th class="{sorter:false} nonSorted">titel</th>
        <th class="{sorter:false} nonSorted" style="width: 30px;"></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($aMapping)) {
        foreach ($aMapping AS $oObject) {
            echo '<tr>';
            echo '<td>' . _e($oObject->label) . '</td>';
            echo '<td class="text-align-center" style="width:30px">';
            echo '<a class="action_icon delete_icon unlinkItem" title="' . sysTranslations::get('pages_unlink') . '" data-linked-id="' . $oObject->value . '" href="javascript:void(0)"></a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6"><i>' . sysTranslations::get('autocomplete_no_connected_items') . '</i></td></tr>';
    }
    ?>
    </tbody>
</table>

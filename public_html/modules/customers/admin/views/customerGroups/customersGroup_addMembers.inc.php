<div id="topOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('customergroup_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<form action="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>/<?= http_get('param1') ?>/<?= http_get('param2') ?>" method="POST">
    <?= CSRFSynchronizerToken::field() ?>
    <input type="hidden" name="action" value="addMembersToCustomerGroup"/>
    <table class="sorted">
        <thead>
        <tr class="topRow">
            <td colspan="8">
                <h2><?= sysTranslations::get('customer_clients_not_on_this_list_part1') . ' "' . _e($oCustomerGroup->title) . '" ' . sysTranslations::get('customer_clients_not_on_this_list_part2') ?></h2>
                <p class="clear"><?= sysTranslations::get('customergroup_add_custom_customers') ?></p>
                <button id="select_all_customers">Selecteer alle onderstaande klanten</button>
            </td>
        </tr>
        <tr>
            <th></th>
            <th><?= sysTranslations::get('global_first_name') ?></th>
            <th><?= sysTranslations::get('global_insertion') ?></th>
            <th><?= sysTranslations::get('global_surname') ?></th>
            <th><?= sysTranslations::get('global_email') ?></th>
            <th><?= sysTranslations::get('global_mobile_number') ?></th>
            <th><?= sysTranslations::get('customer_customer_email') ?></th>
            <th><?= sysTranslations::get('customer_customer_sms') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        # has unlinked customers
        if (!empty($aCustomersNotInThisGroup)) {
            # loop through instances
            foreach ($aCustomersNotInThisGroup AS $oCustomer) {
                echo '<tr>';
                echo '<td><input type="checkbox" value="' . $oCustomer->customerId . '" name="aCustomerIds[]"></td>';
                echo '<td>' . _e($oCustomer->firstName) . '</td>';
                echo '<td>' . _e($oCustomer->insertion) . '</td>';
                echo '<td>' . _e($oCustomer->lastName) . '</td>';
                echo '<td>' . _e($oCustomer->email) . '</td>';
                echo '<td>' . _e($oCustomer->mobilePhone) . '</td>';
                echo '<td>' . ($oCustomer->contactByEmail == 1 ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
                echo '<td>' . ($oCustomer->contactBySms == 1 ? sysTranslations::get('global_yes') : sysTranslations::get('global_no')) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            echo '<table>';
            echo '<tr>';
            echo '<td colspan="8">';
            echo '<br><input type="submit" value="' . sysTranslations::get('customer_add_to_group_button') . '" name="save" />';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
        } # no instances
        else {
            echo '<tr><td colspan="8"><i>' . sysTranslations::get('customers_no_clients_to_add') . '</i></td></tr>';
            echo '</tbody></table>';
        }
        ?>
        </tbody>
    </table>
</form>
<div id="bottomOptions">
    <a class="backBtn" href="<?= ADMIN_FOLDER ?>/<?= http_get('controller') ?>"><?= sysTranslations::get('customergroup_back_overview') ?></a><span class="backBtnInfo"> (<?= sysTranslations::get('global_without_saving') ?>)</span>
</div>
<?php

# create necessary javascript
$sBottomJavascript = <<<EOT
<script type="text/javascript">
    // make row clickable
    $("tr").click(function(event) {
        if (event.target.type !== 'checkbox') {
          $(':checkbox', this).trigger('click');
        }
    });
        
    // select all customers
    $('#select_all_customers').toggle(function(){
        $('input:checkbox').prop('checked','checked');
    },function(){
        $('input:checkbox').removeAttr('checked');
    })
</script>
EOT;
$oPageLayout->addJavascript($sBottomJavascript);
?>
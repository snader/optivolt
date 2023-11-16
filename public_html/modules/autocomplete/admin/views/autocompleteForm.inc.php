<?php
/** @var AutocompleteManager $oAutocompleteManager */

?>
    <div class="contentColumn">
        <fieldset>
            <legend><?= $oAutocompleteManager->title ?></legend>
            <input type="text" class="default" id="autocomplete-<?= $oAutocompleteManager->getInstanceId() ?>" data-disable-default-on-after-select="true"/>
            <br/>
            <br/>
            <div class="table<?= $oAutocompleteManager->getInstanceId() ?>"></div>
        </fieldset>
    </div>

<?php

$oPageLayout->addJavascript(
    "
<script>
    function saveRelation" . $oAutocompleteManager->getInstanceId() . "(event, ui){
        $.ajax({
            method: 'post',
            url: '" . $oAutocompleteManager->getAddUrl() . "' + event.item.value,
            data: { instanceId: '" . $oAutocompleteManager->getInstanceId() . "', " . CSRFSynchronizerToken::FIELD . ": '" . CSRFSynchronizerToken::get() . "' },
            success: function (result) {
                var jsend = JSend.parse(result);
                if (jsend.isSuccess() && jsend.hasData() && jsend.getData().html)
                alertify.success('" . sysTranslations::get('autocomplete_add_success') . "');
                $('.table" . $oAutocompleteManager->getInstanceId() . "').html(jsend.getData().html);
                $('.table" . $oAutocompleteManager->getInstanceId() . " table.sorted-delayed').addClass('sorted').removeClass('sorted-delayed').tablesorter();
            }
        });
    }
    
    function getDataTable" . $oAutocompleteManager->getInstanceId() . "(){
        $.ajax({
            method: 'post',
            url: '" . $oAutocompleteManager->getDataTableUrl() . "',
            data: { filter: '" . $oAutocompleteManager->getDataFilter() . "', instanceId: '" . $oAutocompleteManager->getInstanceId() . "'},
            success: function (result) {
                var jsend = JSend.parse(result);
                if (jsend.isSuccess() && jsend.hasData() && jsend.getData().html)
                $('.table" . $oAutocompleteManager->getInstanceId() . "').html(jsend.getData().html);
                $('.table" . $oAutocompleteManager->getInstanceId() . " table.sorted-delayed').addClass('sorted').removeClass('sorted-delayed').tablesorter();
            }
        });
    }
    
    function deleteDataRelation" . $oAutocompleteManager->getInstanceId() . "(linkedItemId){
        $.ajax({
            method: 'post',
            url: '" . $oAutocompleteManager->getRemoveUrl() . "' + linkedItemId,
            data: { instanceId: '" . $oAutocompleteManager->getInstanceId() . "', " . CSRFSynchronizerToken::FIELD . ": '" . CSRFSynchronizerToken::get() . "'  },
            success: function (result) {
                var jsend = JSend.parse(result);
                if (jsend.isSuccess() && jsend.hasData() && jsend.getData().html)
                $('.table" . $oAutocompleteManager->getInstanceId() . "').html(jsend.getData().html);
                $('.table" . $oAutocompleteManager->getInstanceId() . " table.sorted-delayed').addClass('sorted').removeClass('sorted-delayed').tablesorter();
            }
        });
    }
    
    $(document).on('click', '.table" . $oAutocompleteManager->getInstanceId() . " .unlinkItem', function(){
        var deleteEvent = $(this);
        if (confirmChoice('" . sysTranslations::get('autocomplete_this_link') . "')) {
            deleteDataRelation" . $oAutocompleteManager->getInstanceId() . "($(deleteEvent).data('linked-id'));
            alertify.success('" . sysTranslations::get('autocomplete_delete_success') . "');
        } 
    });
   
    
    getDataTable" . $oAutocompleteManager->getInstanceId() . "();
    
    setDefaultAutocomplete(
        '#autocomplete-" . $oAutocompleteManager->getInstanceId() . "',
        '" . $oAutocompleteManager->getGetUrl() . "',
        '" . $oAutocompleteManager->getFilter() . "',
        null,
        saveRelation" . $oAutocompleteManager->getInstanceId() . "
    );
    
</script>
"
);

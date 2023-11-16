<?php

class ExternalSync extends Model
{

    public $externalSyncId;
    public $item;
    public $itemId;
    public $externalSyncProviderId;
    public $lastSynced;
    public $synced = 0;
    public $lastError;
    public $externalId;
    public $created;
    public $createdby;
    public $modified;
    public $modifiedBy;

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->item)) {
            $this->setPropInvalid('item');
        }
        if (!is_numeric($this->itemId)) {
            $this->setPropInvalid('itemId');
        }
        if (!is_numeric($this->externalSyncProviderId)) {
            $this->setPropInvalid('externalSyncProviderId');
        }
    }

    /**
     * check if item is fully synced and return errors
     * @return array
     */
    public function isSynced()
    {
        $aErrors = [];
        if (!$this->synced) {
            $aErrors[] = sprintf(sysTranslations::get('global_external_syncs_item_not_synced_since'), ($this->lastSynced ? Date::strToDate($this->lastSynced)
                ->format('%d %b %Y %H:%M:%S') : sysTranslations::get('global_never')));
        }
        if ($this->lastError) {
            $aErrors[] = sprintf(sysTranslations::get('global_external_syncs_error'), $this->lastError);
        }

        return $aErrors;
    }
}

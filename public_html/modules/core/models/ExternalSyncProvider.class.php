<?php

class ExternalSyncProvider extends Model
{

    public $externalSyncProviderId;
    public $item; // item name f.e. Relation, Contact, BankAccount
    public $name; // name of the third party
    public $connector; // name of the Provider class to handle this sync
    public $created;
    public $createdby;
    public $modified;
    public $modifiedBy;

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (empty($this->item)) {
            $this->setPropInvalid('item');
        }
        if (empty($this->connector)) {
            $this->setPropInvalid('connector');
        }
    }
}

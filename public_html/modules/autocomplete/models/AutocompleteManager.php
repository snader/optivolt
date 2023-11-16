<?php

class AutocompleteManager extends Model
{
    use Creatable;

    public $title;
    public $template        = 'autocompleteForm';
    public $module          = 'autocomplete';
    public $endpoint        = ADMIN_FOLDER . '/ajax';
    public $dataTableAction = 'data-table';
    public $getAction       = 'get';
    public $addAction       = 'add';
    public $removeAction    = 'remove';
    public $masterModel;
    public $masterId;
    public $slaveModel;

    public $dataFilter = [];

    protected $masterIdColumn;
    protected $instanceId;

    /**
     * AutocompleteManager constructor.
     *
     * @param array $aData
     * @param bool  $bStripTags
     */
    public function __construct($aData = [], $bStripTags = true)
    {
        parent::__construct($aData, $bStripTags);

        if (!$this->masterIdColumn) {
            $this->masterIdColumn = getIdField($this->masterModel);
        }

        $this->instanceId = spl_object_id($this);
    }

    /**
     * @inheritdoc
     */
    public function validate(): void
    {
        foreach ([
            'masterModel',
            'masterId',
            'slaveModel',
        ] as $sField) {
            if (empty($this->$sField)) {
                $this->setPropInvalid($sField);
            }
        }
    }

    /**
     * Include the form
     *
     * @return string|null
     */
    public function includeTemplate(): ?string
    {
        global $oPageLayout;
        if (!$oPageLayout) {
            $oPageLayout = CoreController::getCurrent()
                ->getRenderEngine()
                ->getLayout(); // pageLayout is needed here for javascript adding
        }

        if ($this->isValid()) {
            $oAutocompleteManager = $this;
            ob_start();
            include getAdminView($this->template, $this->module);

            return ob_get_clean();
        }

        return null;
    }

    /**
     * Returns the unique ID of the instance
     *
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * Retrieve the url for the get action
     *
     * @return string
     */
    public function getGetUrl(): string
    {
        return $this->getUrl(
            $this->getAction,
            $this->slaveModel
        );
    }

    /**
     * Retrieve the url for the add action
     *
     * @return string
     */
    public function getAddUrl(): string
    {
        return $this->getUrl(
            $this->addAction,
            $this->masterModel,
            $this->masterId,
            $this->slaveModel,
            ''
        );
    }

    /**
     * Retrieve the url for the remove action
     *
     * @return string
     */
    public function getRemoveUrl(): string
    {
        return $this->getUrl(
            $this->removeAction,
            $this->masterModel,
            $this->masterId,
            $this->slaveModel,
            ''
        );
    }

    /**
     * Retrieve the url for the dataTable action
     *
     * @return string
     */
    public function getDataTableUrl(): string
    {
        return $this->getUrl(
            $this->dataTableAction,
            $this->masterModel,
            $this->masterId,
            $this->slaveModel
        );
    }

    /**
     * Retrieve the url based on the given values
     *
     * @param string[] ...$aArguments
     *
     * @return string
     */
    public function getUrl(...$aArguments): string
    {
        array_unshift($aArguments, $this->endpoint);

        return implode('/', $aArguments);
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return json_encode(
            [
                sprintf('NOT%1$s', $this->masterIdColumn) => $this->masterId,
                'languageId' => AdminLocales::language(),
            ]
        );
    }

    /**
     * @return string
     */
    public function getDataFilter(): string
    {
        return json_encode(
            array_merge(
                [
                    $this->masterIdColumn => $this->masterId,
                ],
                $this->dataFilter
            )
        );
    }
}
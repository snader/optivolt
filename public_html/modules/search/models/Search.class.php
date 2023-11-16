<?php

class Search extends Model
{

    const search_integer       = 'int';
    const search_string        = 'str';
    const search_date          = 'date';
    const search_decimal       = 'deci';
    const search_datefromcheck = 'datefromcheck';
    const search_datetocheck   = 'datetocheck';

    public $searchword;
    public $table; ## database table
    public $columns; ## DB column array containing scores for sorting | format: array('name'=>[10,'cpTRANS']) | explained: array('column name'=>[score,'table alias, default is s']
    public $class; ## Class to return
    public $aQueryConditions  = []; ## array with search conditions. format: array('online'=>array(Search::integer=>1)); "column-name=>array(variable-type=>value)"
    public $sManualConditions = null; ## Enter manual query parts, DO NOT start with WHERE/AND, the script will automaticly fix this.
    public $sJoins            = null;
    public $sFrom             = '';
    public $sGroupBy          = null;

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->searchword)) {
            $this->setPropInvalid('searchword');
        }
        if (empty($this->class)) {
            $this->setPropInvalid('class');
        }
        if (empty($this->columns)) {
            $this->setPropInvalid('columns');
        }
        if (empty($this->table)) {
            $this->setPropInvalid('table');
        }
    }

    /**
     * Execture search
     */
    public function searchObjects()
    {
        return SearchManager::searchQuery($this->searchword, $this->table, $this->columns, $this->aQueryConditions, $this->class, $this->sManualConditions, $this->sJoins, $this->sGroupBy, $this->sFrom);
    }

}

?>
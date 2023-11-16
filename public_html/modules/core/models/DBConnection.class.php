<?php

/* define db variabelen */
define("QRY_ARRAY", 1); //return array with arrays
define("QRY_ASSOC", 2); //return unqiue array with associative arrays
define("QRY_OBJECT", 3); //return unique object
define("QRY_UNIQUE_OBJECT", 4); //return unique object
define("QRY_UNIQUE_ARRAY", 5); //return unique array
define("QRY_NORESULT", 6); //return nothing

/*
 * DBConnection extends mysql standard PHP class mysqli
 */

class DBConnection extends mysqli
{

    /**
     * constructor
     *
     * @param string $sHost
     * @param string $sUser
     * @param string $sPass
     * @param string $sDatabase
     */
    function __construct($sHost = DB_HOST, $sUser = DB_USER, $sPass = DB_PASS, $sDatabase = DB_DATABASE)
    {
        @parent::__construct($sHost, $sUser, $sPass, $sDatabase);

        /* error in connection, send email to error address */
        if ($this->connect_errno) {
            Debug::logError($this->connect_errno, $this->connect_error, __FILE__, __LINE__, null, Debug::LOG_IN_EMAIL);
            echo $sHost . $sUser . $sPass . $sDatabase;
            die('Er is iets mis met de datavoorziening. Probeer later nog eens of neem contact met ons op.');
        } else {
            $this->set_charset('utf8mb4');
        }
    }

    /**
     * execute a query with and return specified result
     *
     * @param string  $sQuery
     * @param string  $sReturnformat     (QRY_OBJECT, QRY_ARRAY, QRY_ASSOC, QRY_UNIQUE, QRY_NORESULT)
     * @param boolean $sClassName        (class name for object returning)
     * @param boolean $sSkipErrorMessage (true for all errors, false for no errors, array for some errors)
     * @param boolean $bSkipErrorLogging (true for all errors, false for no errors, array for some errors)
     *
     * @return mixed 1 array, 1 array with arrays, 1 array with objects, 1 object, mysqlresult, nothing
     */
    function query($sQuery, $sReturnformat = null, $sClassName = "stdClass", $sSkipErrorMessage = false, $bSkipErrorLogging = false)
    {

        $iQSMT   = microtime(true);
        $oResult = parent::query($sQuery);
        $iQEMT   = microtime(true);

        if (DEBUG && $this->error) {
            _d($this->error);
            _d($sQuery);
            _d("Query time: " . ($iQEMT - $iQSMT) . " sec");
        } elseif (DEBUG && DEBUG_QUERIES) {
            _d($sQuery);
            _d("Query time: " . ($iQEMT - $iQSMT) . " sec");
        }

        if ($this->error) {
            # check for skipping errors (all or selection by array)
            if (((is_array($bSkipErrorLogging) && !in_array($this->errno, $bSkipErrorLogging)) || $bSkipErrorLogging === false) && !DEBUG) {

                /* log error in database and email */
                // because we are logging in the database, $this->error and $this->errno will be reset)
                Debug::logError($iErrno = $this->errno, $sError = $this->error, __FILE__, __LINE__, $sQuery, Debug::LOG_IN_DATABASE);
                Debug::logError($iErrno, $sError, __FILE__, __LINE__, $sQuery, Debug::LOG_IN_EMAIL);
            }

            # check for displaying error message
            if ((is_array($sSkipErrorMessage) && !in_array($this->errno, $sSkipErrorMessage)) || $sSkipErrorMessage === false) {
                if (isDeveloper()) {
                    _d($sQuery);
                    echo "<pre>";
                    debug_print_backtrace();
                    echo "</pre>";
                }
                die("Error DQF"); // database query faillure
            }

            return null;
        }

        switch ($sReturnformat) {
            # geeft object terug als standaard class
            case QRY_OBJECT:
                $aArr = [];
                while ($oRow = $oResult->fetch_object($sClassName)) {
                    $aArr[] = $oRow;
                }

                return $aArr;
                break;

            # returns associated array $key => $value
            case QRY_ASSOC:
                $aArr = [];
                while ($aRow = $oResult->fetch_assoc()) {
                    $aArr[] = $aRow;
                }

                return $aArr;
                break;

            # returns an array with result like $key => $value AND $index => $value
            case QRY_ARRAY:
                $aArr = [];
                while ($aRow = $oResult->fetch_array()) {
                    $aArr[] = $aRow;
                }

                return $aArr;
                break;

            # returns 1 unique object
            case QRY_UNIQUE_OBJECT:
                if ($oResult->num_rows == 0) {
                    return null;
                }
                if ($oResult->num_rows == 1) {
                    return $oResult->fetch_object($sClassName);
                }
                Debug::logError('', 'Query returned more than 1 result for unique object', __FILE__, __LINE__, $sQuery);
                Debug::logError('', 'Query returned more than 1 result for unique object', __FILE__, __LINE__, $sQuery, Debug::LOG_IN_EMAIL);
                die("Query gaf meer dan 1 resultaat terug");
                break;

            # returns 1 unique array
            case QRY_UNIQUE_ARRAY:
                if ($oResult->num_rows == 0) {
                    return null;
                }
                if ($oResult->num_rows == 1) {
                    return $oResult->fetch_assoc();
                }
                Debug::logError('', 'Query returned more than 1 result for unique array', __FILE__, __LINE__, $sQuery);
                Debug::logError('', 'Query returned more than 1 result for unique array', __FILE__, __LINE__, $sQuery, Debug::LOG_IN_EMAIL);
                die("Query gaf meer dan 1 resultaat terug");
                break;

            # returns null
            case QRY_NORESULT:
                return null;
                break;
        }

        return $oResult;
    }

    public function __destruct()
    {
        @$this->close();
    }

    /**
     * check if table exists
     *
     * @param string $sTable
     *
     * @return boolean
     */
    public function tableExists($sTable)
    {
        $this->query('SHOW TABLES LIKE ' . db_str($sTable) . ';', QRY_NORESULT);

        return $this->affected_rows > 0;
    }

    /**
     * check if column exists
     *
     * @param string $sTable
     * @param string $sColumn
     *
     * @return boolean
     */
    public function columnExists($sTable, $sColumn)
    {
        $sDatabase = $this->query('SELECT DATABASE() AS `name`;', QRY_UNIQUE_OBJECT)->name;

        $sQuery = '
            SELECT
                COUNT(*) AS `count`
            FROM
                `information_schema`.`COLUMNS`
            WHERE
                `TABLE_SCHEMA` = ' . db_str($sDatabase) . '
            AND
                `TABLE_NAME` = ' . db_str($sTable) . '
            AND
                `COLUMN_NAME` = ' . db_str($sColumn) . '
            ;';

        return $this->query($sQuery, QRY_UNIQUE_OBJECT)->count > 0;
    }

    /**
     * count records
     */
    public static function count($sTable)
    {
        $sQuery = '
            SELECT 
                COUNT(*) AS `count` 
            FROM 
                `' . $sTable . '`;';
        $oDb    = DBConnections::get();

        return (int)$oDb->query($sQuery, QRY_UNIQUE_OBJECT)->count;

    }

    /**
     * add column
     *
     * @param string  $sTable
     * @param string  $sColumn
     * @param string  $sType
     * @param string  $sLength
     * @param string  $sAfter
     * @param string  $sDefault
     * @param boolean $bNull
     * @param string  $sIndex
     * @param string  $sAttributes
     *
     * @return boolean
     */
    public function addColumn($sTable, $sColumn, $sType, $sLength = null, $sAfter = null, $sDefault = 'NULL', $bNull = true, $sIndex = null, $sAttributes = null)
    {

        $sQuery = '
            ALTER TABLE
                `' . $sTable . '`
            ADD
                `' . $sColumn . '` ' . $sType
            . ($sLength ? '(' . $sLength . ')' : '')
            . ($sAttributes ? ' ' . $sAttributes : '')
            . ($bNull ? ' NULL' : ' NOT NULL')
            . (!empty($sDefault) ? ' DEFAULT ' . $sDefault : ($bNull ? ' DEFAULT NULL' : ''))
            . ($sAfter ? ' AFTER `' . $sAfter . '`' : '')
            . ($sIndex ? ', ADD ' . $sIndex . '(`' . $sColumn . '`)' : '')
            . '
            ;';
        $this->query($sQuery, QRY_NORESULT);

        return $this->affected_rows > 0;
    }

    /**
     * check if column has index
     *
     * @param string $sTable
     * @param string $sColumn
     *
     * @return boolean
     */
    public function hasIndex($sTable, $sColumn)
    {
        $sQuery = '
            SHOW INDEX FROM
                `' . $sTable . '`
            WHERE
                `Column_name` = ' . db_str($sColumn) . '
            ;';
        $this->query($sQuery, QRY_NORESULT);

        return $this->affected_rows > 0;
    }

    /**
     * add index
     *
     * @param string  $sTable
     * @param string  $sColumn
     * @param boolean $bUnique
     *
     * @return boolean
     */
    public function addIndex($sTable, $sColumn, $bUnique = false)
    {
        $sQuery = '
            ALTER TABLE
                `' . $sTable . '`
            ADD ' . ($bUnique ? 'UNIQUE' : 'INDEX') . '(`' . $sColumn . '`)
            ;';
        $this->query($sQuery, QRY_NORESULT);

        return $this->affected_rows > 0;
    }

    /**
     * check if constraint exists
     *
     * @param string $sTable
     * @param string $sColumn
     * @param string $sReferenceTable
     * @param string $sReferenceColumn
     *
     * @return boolean
     */
    public function constraintExists($sTable, $sColumn, $sReferenceTable, $sReferenceColumn)
    {
        $sDatabase = $this->query('SELECT DATABASE() AS `name`;', QRY_UNIQUE_OBJECT)->name;

        $sQuery = '
            SELECT
                COUNT(*) AS `count`
            FROM
                `information_schema`.`KEY_COLUMN_USAGE`
            WHERE
                `TABLE_SCHEMA` = ' . db_str($sDatabase) . '
            AND
                `TABLE_NAME` = ' . db_str($sTable) . '
            AND
                `COLUMN_NAME` = ' . db_str($sColumn) . '
            AND
                `REFERENCED_TABLE_NAME` = ' . db_str($sReferenceTable) . '
            AND
                `REFERENCED_COLUMN_NAME` = ' . db_str($sReferenceColumn) . '
            ;';

        return $this->query($sQuery, QRY_UNIQUE_OBJECT)->count > 0;
    }

    /**
     * add foreign key constraint
     *
     * @param string $sTable
     * @param string $sColumn
     * @param string $sReferenceTable
     * @param string $sReferenceColumn
     * @param string $sOnDelete
     * @param string $sOnUpdate
     *
     * @return boolean
     */
    public function addConstraint($sTable, $sColumn, $sReferenceTable, $sReferenceColumn, $sOnDelete, $sOnUpdate)
    {

        // check index, otherwise add one
        if (!$this->hasIndex($sTable, $sColumn)) {
            $this->addIndex($sTable, $sColumn);
        }

        $sConstraintName = strtolower($sTable) . '_' . strtolower($sColumn);

        // set max lengt of 64 by abbreviating table name and setting uniqueId as prefix
        if (mb_strlen($sConstraintName) > 64) {
            $sTableAbbr = '';
            $aWords     = explode('_', $sTable);
            foreach ($aWords AS $sWord) {
                $sTableAbbr .= $sWord[0];
            }

            $sConstraintName = uniqid() . '_' . substr(strtolower($sTableAbbr) . '_' . strtolower($sColumn), -50, 50);
        }

        $sQuery = '
            ALTER TABLE `' . $sTable . '`
                ADD CONSTRAINT `' . $sConstraintName . '` FOREIGN KEY (`' . $sColumn . '`) REFERENCES `' . $sReferenceTable . '` (`' . $sReferenceColumn . '`) ON DELETE ' . $sOnDelete . ' ON UPDATE ' . $sOnUpdate . '
            ;';
        $this->query($sQuery, QRY_NORESULT);

        return $this->affected_rows > 0;
    }

}

?>
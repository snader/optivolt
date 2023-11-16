<?php

class SearchManager
{

    /**
     * Build and return query results for a search
     *
     * @param string $sSearchword       search word
     * @param string $sTable            the database table to search
     * @param string $sColumns          comma string with columns to search in
     * @param array  $aQueryConditions  query conditions in array form
     * @param string $sClass            Class to return
     * @param string $sManualConditions Extra optional query part used for special cases. WHERE/AND is needed!
     * @param string $sJoins            string to make special joins needed for your search query, think about product stock checks etc.
     * @param string $sGroupBy          string group on certain fields
     *
     * @return array Object
     */
    public static function searchQuery($sSearchword, $sTable, $aColumns, $aQueryConditions, $sClass, $sManualConditions = null, $sJoins = null, $sGroupBy = null, $sFrom = '')
    {
        $aResults = [];

        ## Remove commas, dots and questionmarks
        $sSearchword = preg_replace("#([\!\?\,\\\/\#])#i", " ", $sSearchword);
        $sSearchword = trim($sSearchword);
        $sSearchword = removePunctuation($sSearchword);

        ## Set boolean to see if it contains more than 1 word
        $bSearchParts = (intval(str_word_count($sSearchword, 0)) > 1);

        $sWhere = '';

        ## Build query conditions
        if (count($aQueryConditions) > 0) {
            foreach ($aQueryConditions as $sColumn => $aQueryCondition) {
                $sType  = key($aQueryCondition);
                $sValue = $aQueryCondition[$sType];

                if ($sType == Search::search_integer) {
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_int($sValue);
                } elseif ($sType == Search::search_string) {
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_str($sValue);
                } elseif ($sType == Search::search_date) {
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_date($sValue);
                } elseif ($sType == Search::search_decimal) {
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_deci($sValue);
                } elseif ($sType == Search::search_datefromcheck) {
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`' . $sColumn . '` <= NOW() OR `s`.`' . $sColumn . '` IS NULL)';
                } elseif ($sType == Search::search_datetocheck) {
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`' . $sColumn . '` >= NOW() OR `s`.`' . $sColumn . '` IS NULL)';
                } else {
                    die('No variable type specified.');
                }

            }
        }

        if ($sManualConditions) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . $sManualConditions;
        }

        ###########################################################################################
        ## FIRST ATTEMPT -> full value search:
        ###########################################################################################

        ## Build query:
        $sSearchString = '(';
        foreach ($aColumns as $sColumn => $aColumnInfo) {
            $iScore = $aColumnInfo[0];
            if (isset($aColumnInfo[1])) { ## Custom alias
                $sAlias = $aColumnInfo[1];
            } else {
                $sAlias = 's';
            }

            $sSearchString .= ($sSearchString != '(' ? ' OR ' : '') . '`' . $sAlias . '`.`' . $sColumn . '` LIKE ' . db_str('%' . $sSearchword . '%');
        }
        $sSearchString .= ')';

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . $sSearchString;

        $sQuery = ' SELECT 
                            `s`.*' . $sFrom . '
                        FROM
                            `' . $sTable . '` AS `s`
                        ' . ($sJoins ? $sJoins : '') . '
                        ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                        ' . ($sGroupBy ? $sGroupBy : '') . '
                        ;';

        ## Execute query
        $oDb      = DBConnections::get();
        $aResults = $oDb->query($sQuery, QRY_OBJECT, $sClass);

        ###########################################################################################
        ## IF RESULT FOUND -> SORT OUT ORDER HERE BY GIVING A SCORE TO EACH OBJECT
        ###########################################################################################
        if (count($aResults) > 0) {
            foreach ($aResults as $oResult) {
                $iTotalScore = 0;

                foreach ($aColumns as $sColumn => $aColumnInfo) {
                    $iScore = $aColumnInfo[0];
                    if (isset($aColumnInfo[1])) { ## Custom alias
                        $sAlias = $aColumnInfo[1];
                    } else {
                        $sAlias = 's';
                    }

                    if (!isset($iScore) || empty($iScore)) {
                        $iScore = 0;
                    }

                    $iCount = preg_match_all('#' . $sSearchword . '#i', $oResult->$sColumn, $aMatches);
                    if ($iCount) {
                        $iTotalScore += $iCount * $iScore;
                    } else {
                        $sPattern = '#' . str_replace(' ', '|', $sSearchword) . '#i';
                        $iCount   = preg_match_all($sPattern, $oResult->$sColumn, $aMatches);
                        if ($iCount) {
                            $iTotalScore += $iCount * $iScore;
                        }
                    }
                }

                $oResult->bMultipleWordSearch = false;
                $oResult->searchClass         = $sClass;
                $oResult->sSortValue          = $iTotalScore;
            }
        }

        ###########################################################################################
        ## No results? split search string and execute again.
        ###########################################################################################
        if (count($aResults) < 1 && $bSearchParts) {
            $sWhere = '';

            ## Build query conditions
            if (count($aQueryConditions) > 0) {
                foreach ($aQueryConditions as $sColumn => $aQueryCondition) {
                    $sType  = key($aQueryCondition);
                    $sValue = $aQueryCondition[$sType];

                    if ($sType == Search::search_integer) {
                        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_int($sValue);
                    } elseif ($sType == Search::search_string) {
                        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_str($sValue);
                    } elseif ($sType == Search::search_date) {
                        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_date($sValue);
                    } elseif ($sType == Search::search_decimal) {
                        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`s`.`' . $sColumn . '` = ' . db_deci($sValue);
                    } elseif ($sType == Search::search_datefromcheck) {
                        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`' . $sColumn . '` <= NOW() OR `s`.`' . $sColumn . '` IS NULL)';
                    } elseif ($sType == Search::search_datetocheck) {
                        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`s`.`' . $sColumn . '` >= NOW() OR `s`.`' . $sColumn . '` IS NULL)';
                    } else {
                        die('No variable type specified.');
                    }

                }
            }

            if ($sManualConditions) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . $sManualConditions;
            }

            ## Build query:
            $sSearchString = '(';
            foreach ($aColumns as $sColumn => $aColumnInfo) {
                $iScore = $aColumnInfo[0];
                if (isset($aColumnInfo[1])) { ## Custom alias
                    $sAlias = $aColumnInfo[1];
                } else {
                    $sAlias = 's';
                }

                foreach (str_word_count($sSearchword, 1) as $sWord) {
                    $sSearchString .= ($sSearchString != '(' ? ' OR ' : '') . '`' . $sAlias . '`.`' . $sColumn . '` LIKE ' . db_str('%' . $sWord . '%');
                }
            }
            $sSearchString .= ')';

            $sWhere .= ($sWhere != '' ? ' AND ' : '') . $sSearchString;

            $sQuery = ' SELECT 
                                `s`.*' . $sFrom . '
                            FROM
                                `' . $sTable . '` AS `s`
                            ' . ($sJoins ? $sJoins : '') . '
                            ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                            ' . ($sGroupBy ? $sGroupBy : '') . '
                            ;';

            ## Execute query
            $oDb      = DBConnections::get();
            $aResults = $oDb->query($sQuery, QRY_OBJECT, $sClass);

            ###########################################################################################
            ## IF RESULT FOUND -> SORT OUT ORDER HERE BY GIVING A SCORE TO EACH OBJECT
            ###########################################################################################
            if (count($aResults) > 0) {
                foreach ($aResults as $oResult) {
                    $iTotalScore = 0;

                    foreach ($aColumns as $sColumn => $aColumnInfo) {
                        $iScore = $aColumnInfo[0];
                        if (isset($aColumnInfo[1])) { ## Custom alias
                            $sAlias = $aColumnInfo[1];
                        } else {
                            $sAlias = 's';
                        }

                        if (!isset($iScore) || empty($iScore)) {
                            $iScore = 0;
                        }

                        foreach (str_word_count($sSearchword, 1) as $sWord) {
                            $iCount = preg_match_all('#' . $sWord . '#i', $oResult->$sColumn, $aMatches);
                            if ($iCount) {
                                $iTotalScore += $iCount * $iScore;
                            } else {
                                $sPattern = '#' . str_replace(' ', '|', $sWord) . '#i';
                                $iCount   = preg_match_all($sPattern, $oResult->$sColumn, $aMatches);
                                if ($iCount) {
                                    $iTotalScore += $iCount * $iScore;
                                }
                            }
                        }
                    }

                    $oResult->bMultipleWordSearch = true;
                    $oResult->searchClass         = $sClass;
                    $oResult->sSortValue          = $iTotalScore;
                }
            }

        }

        return $aResults;

    }

}

?>
<?php

class CustomerGroupManager
{

    /**
     * get a CustomerGroup by id
     *
     * @param int $iCustomerGroupId
     *
     * @return CustomerGroup
     */
    public static function getCustomerGroupById($iCustomerGroupId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customer_groups`
                    WHERE
                        `customerGroupId` = ' . db_int($iCustomerGroupId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CustomerGroup");
    }

    /**
     * get a CustomerGroup by name
     *
     * @param int $sCustomerGroupName
     *
     * @return CustomerGroup
     */
    public static function getCustomerGroupByName($sCustomerGroupName)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customer_groups`
                    WHERE
                        `name` = ' . db_str($sCustomerGroupName) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CustomerGroup");
    }

    /**
     * get CustomerGroups by customerId
     *
     * @param int $iCustomerId
     *
     * @return CustomerGroup
     */
    public static function getCustomerGroupsByCustomerId($iCustomerId)
    {
        $sQuery = ' SELECT
                        `cg`.*
                    FROM
                        `customer_groups` AS `cg`
                    JOIN
                        `customer_group_relations` AS `cgr`
                    ON
                        `cg`.`customerGroupId` = `cgr`.`customerGroupId`
                    WHERE
                        `cgr`.`customerId` = ' . db_int($iCustomerId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CustomerGroup");
    }

    /**
     * get CustomerGroups by communicationId
     *
     * @param int $iCommunicationId
     *
     * @return CustomerGroup
     */
    public static function getCustomerGroupsByCommunicationItemId($iCommunicationId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `communication_item_customer_group_relation` as `cicgr`
                    JOIN
                        `customer_groups` as `cg`
                    USING
                        (`customerGroupId`)
                    WHERE
                        `cicgr`.`communicationItemId` = ' . db_int($iCommunicationId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CustomerGroup");
    }

    /**
     * get all CustomerGroup objects
     *
     * @return array CustomerGroup
     */
    public static function getAllCustomerGroups()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `customer_groups`
                    ORDER BY
                        `created` DESC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CustomerGroup");
    }

    /**
     * save a CustomerGroup
     *
     * @param Customer $oCustomerGroup
     */
    public static function saveCustomerGroup(CustomerGroup $oCustomerGroup)
    {
        $sQuery = ' INSERT INTO `customer_groups`(
                        `customerGroupId`,
                        `title`,
                        `name`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oCustomerGroup->customerGroupId) . ',
                        ' . db_str($oCustomerGroup->title) . ',
                        ' . db_str($oCustomerGroup->name) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `title`=VALUES(`title`),
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oCustomerGroup->customerGroupId === null) {
            $oCustomerGroup->customerGroupId = $oDb->insert_id;
        }
    }

    /**
     * delete a CustomerGroup
     *
     * @param CustomerGroup $oCustomerGroup
     *
     * @return bool true
     */
    public static function deleteCustomerGroup(CustomerGroup $oCustomerGroup)
    {
        $sQuery = ' DELETE FROM
                        `customer_groups`
                    WHERE
                        `customerGroupId` = ' . db_int($oCustomerGroup->customerGroupId) . '
                    LIMIT
                        1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * delete a Customer out of a CustomerGroup
     *
     * @param CustomerGroup $oCustomerGroup
     *
     * @return bool true
     */
    public static function deleteCustomerFromCustomerGroup($iCustomerGroupId, $iCustomerId)
    {
        $sQuery = ' DELETE FROM
                        `customer_group_relations`
                    WHERE
                        `customerId` = ' . db_int($iCustomerId) . '
                    AND
                        `customerGroupId` = ' . db_int($iCustomerGroupId) . '
                    LIMIT
                        1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>
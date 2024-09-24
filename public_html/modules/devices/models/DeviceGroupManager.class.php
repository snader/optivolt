<?php

class DeviceGroupManager
{

    /**
     * get a DeviceGroup by id
     *
     * @param int $iDeviceGroupId
     *
     * @return DeviceGroup
     */
    public static function getDeviceGroupById($iDeviceGroupId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `device_groups`
                    WHERE
                        `deviceGroupId` = ' . db_int($iDeviceGroupId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "DeviceGroup");
    }

    /**
     * get a DeviceGroup by name
     *
     * @param int $sDeviceGroupName
     *
     * @return DeviceGroup
     */
    public static function getDeviceGroupByName($sDeviceGroupName)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `device_groups`
                    WHERE
                        `name` = ' . db_str($sDeviceGroupName) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "DeviceGroup");
    }

    /**
     * get DeviceGroups by deviceId
     *
     * @param int $iDeviceId
     *
     * @return DeviceGroup
     */
    public static function getDeviceGroupsByDeviceId($iDeviceId)
    {
        $sQuery = ' SELECT
                        `cg`.*
                    FROM
                        `device_groups` AS `cg`
                    JOIN
                        `device_group_relations` AS `cgr`
                    ON
                        `cg`.`deviceGroupId` = `cgr`.`deviceGroupId`
                    WHERE
                        `cgr`.`deviceId` = ' . db_int($iDeviceId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "DeviceGroup");
    }

   

    /**
     * get all DeviceGroup objects
     *
     * @return array DeviceGroup
     */
    public static function getAllDeviceGroups()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `device_groups`
                    ORDER BY
                        `title` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "DeviceGroup");
    }

    /**
     * save a DeviceGroup
     *
     * @param Device $oDeviceGroup
     */
    public static function saveDeviceGroup(DeviceGroup $oDeviceGroup)
    {
        $sQuery = ' INSERT INTO `device_groups`(
                        `deviceGroupId`,
                        `title`,
                        `name`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oDeviceGroup->deviceGroupId) . ',
                        ' . db_str($oDeviceGroup->title) . ',
                        ' . db_str($oDeviceGroup->name) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `title`=VALUES(`title`),
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oDeviceGroup->deviceGroupId === null) {
            $oDeviceGroup->deviceGroupId = $oDb->insert_id;
        }
    }

    /**
     * delete a DeviceGroup
     *
     * @param DeviceGroup $oDeviceGroup
     *
     * @return bool true
     */
    public static function deleteDeviceGroup(DeviceGroup $oDeviceGroup)
    {
        $sQuery = ' DELETE FROM
                        `device_groups`
                    WHERE
                        `deviceGroupId` = ' . db_int($oDeviceGroup->deviceGroupId) . '
                    LIMIT
                        1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * delete a Device out of a DeviceGroup
     *
     * @param DeviceGroup $oDeviceGroup
     *
     * @return bool true
     */
    public static function deleteDeviceFromDeviceGroup($iDeviceGroupId, $iDeviceId)
    {
        $sQuery = ' DELETE FROM
                        `device_group_relations`
                    WHERE
                        `deviceId` = ' . db_int($iDeviceId) . '
                    AND
                        `deviceGroupId` = ' . db_int($iDeviceGroupId) . '
                    LIMIT
                        1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>
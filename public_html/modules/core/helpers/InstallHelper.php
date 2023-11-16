<?php

class InstallHelper
{
    /**
     * Constants constraint types
     *
     */
    const SET_NULL    = 'SET NULL';
    const RESTRICT    = 'RESTRICT';
    const CASCADE     = 'CASCADE';
    const NO_ACTIOn   = 'NO ACTION';
    const SET_DEFAULT = 'SET DEFAULT';

    /**
     * Database connection
     *
     * @var DBConnection
     */
    protected static $db;

    /**
     * Create a pivot table for images
     *
     * @param string $sMasterModel
     * @param string $sMasterIdentifier
     * @param bool   $bInstall
     *
     * @return array
     */
    public static function imagePivot($sMasterModel, $sMasterIdentifier, $bInstall = false)
    {
        return static::pivot($sMasterModel, $sMasterIdentifier, 'images', 'imageId', $bInstall, null);
    }

    /**
     * Create a pivot table for files
     *
     * @param string $sMasterModel
     * @param string $sMasterIdentifier
     * @param bool   $bInstall
     *
     * @return array
     */
    public static function filePivot($sMasterModel, $sMasterIdentifier, $bInstall = false)
    {
        return static::pivot($sMasterModel, $sMasterIdentifier, 'files', 'mediaId', $bInstall, null);
    }

    /**
     * Create a pivot table for links
     *
     * @param string $sMasterModel
     * @param string $sMasterIdentifier
     * @param bool   $bInstall
     *
     * @return array
     */
    public static function linkPivot($sMasterModel, $sMasterIdentifier, $bInstall = false)
    {
        return static::pivot($sMasterModel, $sMasterIdentifier, 'media', 'mediaId', $bInstall, sprintf('%1$s_links', $sMasterModel), static::CASCADE, static::CASCADE);
    }

    /**
     * Create a pivot table for video links
     *
     * @param string $sMasterModel
     * @param string $sMasterIdentifier
     * @param bool   $bInstall
     *
     * @return array
     */
    public static function videoLinkPivot($sMasterModel, $sMasterIdentifier, $bInstall = false)
    {
        return static::pivot($sMasterModel, $sMasterIdentifier, 'media', 'mediaId', $bInstall, sprintf('%1$s_video_links', $sMasterModel), static::CASCADE, static::CASCADE);
    }

    /**
     * Create a pivot table
     *
     * @param string $sMasterModel
     * @param string $sMasterIdentifier
     * @param string $sSlaveModel
     * @param string $sSlaveIdentifier
     * @param bool   $bInstall
     * @param string $sTable
     * @param string $sMasterUpdate
     * @param string $sMasterDelete
     * @param string $sSlaveUpdate
     * @param string $sSlaveDelete
     *
     * @return array
     */
    public static function pivot(
        $sMasterModel,
        $sMasterIdentifier,
        $sSlaveModel,
        $sSlaveIdentifier,
        $bInstall = false,
        $sTable = null,
        $sMasterUpdate = self::CASCADE,
        $sMasterDelete = self::RESTRICT,
        $sSlaveUpdate = self::CASCADE,
        $sSlaveDelete = self::CASCADE
    ) {
        $aLog = [];
        if (is_null($sTable)) {
            $sTable = sprintf('%1$s_%2$s', $sMasterModel, $sSlaveModel);
        }

        if (!static::db()
            ->tableExists($sTable)) {
            array_push($aLog, sprintf('Missing table `%1$s`', $sTable));

            if ($bInstall) {
                $sQuery = sprintf(
                    '
                        CREATE TABLE `%1$s` (
                          `%2$s` int(11) NOT NULL,
                          `%3$s` int(11) NOT NULL,
                          PRIMARY KEY (`%2$s`, `%3$s`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
                    ',
                    $sTable,
                    $sMasterIdentifier,
                    $sSlaveIdentifier
                );

                static::db()
                    ->query($sQuery, QRY_NORESULT);
            }
        }

        if (static::db()
            ->tableExists($sTable)) {
            array_push(
                $aLog,
                static::constraint($sTable, $sMasterModel, $sMasterIdentifier, $sMasterDelete, $sMasterUpdate, $bInstall) .
                static::constraint($sTable, $sSlaveModel, $sSlaveIdentifier, $sSlaveDelete, $sSlaveUpdate, $bInstall)
            );
        }

        $aLog = array_filter($aLog);

        return $aLog;
    }

    /**
     * Create constraints for a pivot table
     *
     * @param string $sTable
     * @param string $sModel
     * @param string $sIdentifier
     * @param string $sOnDelete
     * @param string $sOnUpdate
     * @param bool   $bInstall
     *
     * @return string
     */
    protected static function constraint($sTable, $sModel, $sIdentifier, $sOnDelete = self::CASCADE, $sOnUpdate = self::CASCADE, $bInstall = false)
    {
        $sLog = '';

        if (!static::db()
            ->constraintExists($sTable, $sIdentifier, $sModel, $sIdentifier)) {
            $sLog = sprintf(
                'Missing fk constraint `%1$s`.`%2$s` => `%3$s`.`%2$s`',
                $sTable,
                $sIdentifier,
                $sModel
            );
            if ($bInstall) {
                static::db()
                    ->addConstraint($sTable, $sIdentifier, $sModel, $sIdentifier, $sOnDelete, $sOnUpdate);
            }
        }

        return $sLog;
    }

    /**
     * Retrieve the database connection
     *
     * @return \DBConnection
     */
    protected static function db()
    {
        if (!static::$db) {
            static::$db = DBConnections::get();
        }

        return static::$db;
    }
}
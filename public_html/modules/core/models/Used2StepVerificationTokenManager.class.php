<?php

class Used2StepVerificationTokenManager
{

    /**
     * save a Used2StepVerificationToken
     *
     * @param Used2StepVerificationToken $oUsed2StepVerificationToken
     *
     * @return int
     */
    private static function saveUsed2StepVerificationToken(Used2StepVerificationToken $oUsed2StepVerificationToken)
    {

        if (empty($oUsed2StepVerificationToken->used2StepVerificationTokenId)) {
            $sQuery = ' INSERT INTO `used_2_step_verification_tokens`(
                            `timeslice`,
                            `verificationToken`,
                            `userId`,
                            `identifier`,
                            `created`
                        )
                        VALUES (
                            ' . db_int($oUsed2StepVerificationToken->timeslice) . ',
                            ' . db_str($oUsed2StepVerificationToken->verificationToken) . ',
                            ' . db_int($oUsed2StepVerificationToken->userId) . ',
                            ' . db_str($oUsed2StepVerificationToken->ip) . ',
                            NOW()
                        );';
        }

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if (!$oUsed2StepVerificationToken->used2StepVerificationTokenId) {
            $oUsed2StepVerificationToken->used2StepVerificationTokenId = $oDb->insert_id;
        }

        return $oDb->affected_rows;
    }

    /**
     * get used user token for user based on token and timeslice
     *
     * @param string $sToken
     * @param int    $iTimeslice
     * @param string $sSalt
     * @param int    $iUserId
     *
     * @return type
     */
    public static function getUsedTokensByTokenOrTimeslice($sToken, $iTimeslice, $sSalt, $iUserId)
    {

        $sHashedToken = Used2StepVerificationToken::hashToken($sToken, $sSalt, $iUserId);

        // token is checked for double use
        // timeslice is checked for using older codes than current time of user
        $sQuery = ' SELECT
                        `u2svt`.*
                    FROM
                        `used_2_step_verification_tokens` AS `u2svt`
                    WHERE
                        (
                            `u2svt`.`verificationToken` = ' . db_str($sHashedToken) . '
                        OR
                            `u2svt`.`timeslice` >= ' . db_int($iTimeslice) . '
                        )
                    AND
                        `u2svt`.`created` >= DATE(NOW()) - INTERVAL 1 DAY
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "Used2StepVerificationToken");
    }

    /**
     * secure token so it can't be used for a certain time
     *
     * @global User  $oCurrentUser
     *
     * @param string $sToken
     * @param int    $iTimeslice
     * @param string $sSalt
     * @param int iUserId
     * @param int    $iUserId
     */
    public static function secureTokenUser($sToken, $iTimeslice, $sSalt, $iUserId)
    {
        $oUsed2StepVerificationToken                    = new Used2StepVerificationToken();
        $oUsed2StepVerificationToken->verificationToken = Used2StepVerificationToken::hashToken($sToken, $sSalt, $iUserId);
        $oUsed2StepVerificationToken->timeslice         = $iTimeslice;
        $oUsed2StepVerificationToken->userId            = $iUserId;
        $oUsed2StepVerificationToken->ip                = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : -1;
        self::saveUsed2StepVerificationToken($oUsed2StepVerificationToken);
    }

}

?>
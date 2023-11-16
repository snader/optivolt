<?php

class Used2StepVerificationToken extends Model
{

    public $used2StepVerificationTokenId = null;
    public $timeslice; // timeslice that matched the token
    public $verificationToken; // hashed version of the last used token
    public $userId; // used that used the token
    public $ip; // ip that used the token
    public $created;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->timeslice)) {
            $this->setPropInvalid('timeslice');
        }
        if (empty($this->verificationToken)) {
            $this->setPropInvalid('verificationToken');
        }
        if (empty($this->userId)) {
            $this->setPropInvalid('userId');
        }
        if (empty($this->ip)) {
            $this->setPropInvalid('ip');
        }
    }

    /**
     * hash token to make secure
     *
     * @global User  $oCurrentUser
     *
     * @param string $sToken
     * @param string $sSalt
     * @param int    $iUserId
     *
     * @return string hashed token
     */
    public static function hashToken($sToken, $sSalt, $iUserId)
    {
        return hashPasswordForDb($sToken . $iUserId . $sSalt);
    }

}

?>
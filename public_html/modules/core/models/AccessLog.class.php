<?php

class AccessLog extends Model
{

    public $accessLogId = null;
    public $ip;
    public $blocked;
    public $reason;
    public $loginFails  = 0;
    public $lastLoginFail;
    public $extraInfo;
    public $userAgent;
    public $serverInfo;
    public $created;
    public $modified;

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->ip)) {
            $this->setPropInvalid('ip');
        }
    }

    /**
     * check if login form should be enabled
     *
     * @return boolean
     */
    public function loginEnabled()
    {
        if (isDeveloper()) {
            return true;
        }

        if ($this->blocked) {
            return false;
        }

        // to many fails, disable login
        if ($this->loginFails != 0 && $this->loginFails % AccessLogManager::max_login_attempts_account_lock == 0 && Date::strToDate('now')
                ->addMinutes(-1 * AccessLogManager::account_locked_time)
                ->lowerThan(new Date($this->lastLoginFail))) {
            return false;
        }

        return true;
    }

    /**
     * get login fails to show
     *
     * @return int
     */
    public function getLoginAttemptsLeft()
    {
        return AccessLogManager::max_login_attempts_account_lock - ($this->loginFails % AccessLogManager::max_login_attempts_account_lock);
    }

    /**
     * unserialize serverInfo and return array
     *
     * @return array
     */
    public function getServerInfo()
    {
        return unserialize($this->serverInfo);
    }

}

?>
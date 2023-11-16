<?php

class Security
{
    /**
     * Encryption method
     *
     * @var string
     */
    protected static $method = 'AES-256-CBC';

    /**
     * Output in base64
     *
     * @var bool
     */
    protected static $base64 = true;

    /**
     * Encrypt a message
     *
     * @param string $sPlain
     *
     * @return array
     * @throws \Exception
     */
    public static function encrypt($sPlain)
    {
        $aKey = static::key();

        $sSecret = hash('sha256', $aKey['secret']);
        $sPublic = substr(hash('sha256', $aKey['public']), 0, 16);

        $sCrypt = openssl_encrypt($sPlain, static::$method, $sSecret, OPENSSL_RAW_DATA, $sPublic);

        $sDate = date('Y-m-d H:i:s');

        // build the package
        $aPackage = [
            'message' => $sCrypt,
            'key'     => $aKey['public'],
            'date'    => $sDate,
            'hmac'    => hash_hmac('sha256', $sCrypt . $sPublic . $aKey['public'] . $sDate, $aKey['secret'], true),
        ];

        if (static::$base64) {
            $aPackage = array_map('base64_encode', $aPackage);
        }

        return $aPackage;
    }

    /**
     * Decrypt a message
     *
     * @param array $aPackage [message, key, date, hmac]
     * @param bool  $bNoTimeout
     *
     * @return string
     * @throws \Exception
     */
    public static function decrypt(array $aPackage, $bNoTimeout = false)
    {
        if (static::$base64) {
            $aPackage = array_map('base64_decode', $aPackage);
        }

        // get the packaged values
        $sCrypt = $aPackage['message'];
        $aKey   = static::key($aPackage['key']);
        $sDate  = $aPackage['date'];

        // prepare request time comparison
        $iTimestamp = strtotime($sDate);
        $iNow       = time();

        // there's a 5 minute window where the request is valid
        if (!$bNoTimeout && $iTimestamp < $iNow - 150 || $iTimestamp > $iNow + 150) {
            throw new Exception('Request timeout.');
        }

        $sSecret = hash('sha256', $aKey['secret']);
        $sPublic = substr(hash('sha256', $aKey['public']), 0, 16);

        $sHmac = hash_hmac('sha256', $sCrypt . $sPublic . $aKey['public'] . $sDate, $aKey['secret'], true);

        // check hmacs
        if ($sHmac != $aPackage['hmac']) {
            throw new Exception('Illegal HMAC.');
        }

        // set up the decryption process
        $sPlain = openssl_decrypt($sCrypt, static::$method, $sSecret, OPENSSL_RAW_DATA, $sPublic);

        // remove any null bytes (padding used in encryption)
        $sPlain = trim($sPlain, "\0");

        // return the decrypted message
        return $sPlain;
    }

    /**
     * Get either a random or a specific key
     *
     * @param null $sIndex
     *
     * @return array
     * @throws \Exception
     */
    protected static function key($sIndex = null)
    {
        $aPublic = $aPrivate = [];

        require DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'public.php';
        require DOCUMENT_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'private.php';

        if (!$aPublic || !$aPrivate) {
            throw new Exception('Implementation error');
        }

        $aKeys = array_combine($aPublic, $aPrivate);

        // if no index was given, we'll select a random key
        if (is_null($sIndex)) {
            $sIndex = array_rand($aKeys);
        }

        // if the index does not exist, we have an issue
        if (!array_key_exists($sIndex, $aKeys)) {
            throw new Exception('Illegal key.');
        }

        // return the key
        return [
            'public' => $sIndex,
            'secret' => $aKeys[$sIndex],
        ];
    }
}

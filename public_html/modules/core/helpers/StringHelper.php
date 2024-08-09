<?php

class StringHelper
{
    /**
     * replacement regex
     *
     * @var string
     */
    static $replace = '/[-_\/ +]/';

    /**
     * abolishment regex
     *
     * @var string
     */
    static $abolish = '/[^A-Za-z0-9-_\/ ]/';

    /**
     * Transform a string to PascalCase
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toPascalCase($sString)
    {
        $sString = static::getTranslatable($sString);
        $aString = explode('-', $sString);

        $sPascal = '';
        foreach ($aString as $sSegment) {
            $sPascal .= ucfirst(strtolower($sSegment ?? ''));
        }

        return $sPascal;
    }

    /**
     * Transform a string to camelCase
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toCamelCase($sString)
    {
        $sString = static::getTranslatable($sString);
        $aString = explode('-', $sString);

        $sCamel = strtolower(array_shift($aString) ?? '');
        foreach ($aString as $sSegment) {
            $sCamel .= ucfirst(strtolower($sSegment ?? ''));
        }

        return $sCamel;
    }

    /**
     * Transform a string to kebab-case
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toKebabCase($sString)
    {
        $sString = static::getTranslatable($sString);

        return strtolower($sString ?? '');
    }

    /**
     * Transform a string to snake_case
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toSnakeCase($sString)
    {
        $sString = static::getTranslatable($sString, '/__+?/', '_');

        return strtolower($sString ?? '');
    }

    /**
     * Transform a string to SCREAMING_SNAKE_CASE
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toScreamingSnakeCase($sString)
    {
        return strtoupper(static::toSnakeCase($sString));
    }

    /**
     * Transform a string to dot.case
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toDotCase($sString)
    {
        $sString = static::getTranslatable($sString, '/\.\.+?/', '.');

        return strtolower($sString ?? '');
    }

    /**
     * Transform a string to slashed
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toSlashed($sString)
    {
        $sString = static::getTranslatable($sString, '/\/\/+?/', '/');

        return strtolower($sString ?? '');
    }

    /**
     * Transform a string to spaced
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toSpaced($sString)
    {
        $sString = static::getTranslatable($sString, '/\s\s+?/', ' ');

        return ucfirst(strtolower($sString));
    }

    /**
     * Transform a string to its acronym
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toAcronym($sString)
    {
        $sString = ucwords(strtolower($sString));
        $sString = static::getTranslatable($sString, '/\s\s+?/', ' ');

        return preg_replace('/[^A-Z]/', '', $sString);
    }

    /**
     * Retrieve a slug generated from the given string
     *
     * @param string $sString
     *
     * @return string
     */
    public static function toSlug($sString)
    {
        $aTable = [
            'Š' => 'S',
            'š' => 's',
            'Đ' => 'Dj',
            'đ' => 'dj',
            'Ž' => 'Z',
            'ž' => 'z',
            'Č' => 'C',
            'č' => 'c',
            'Ć' => 'C',
            'ć' => 'c',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'Ae',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'Oe',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'Ue',
            'Ý' => 'Y',
            'Þ' => 'B',
            'ß' => 'ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'ae',
            'å' => 'a',
            'æ' => 'ae',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'oe',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'ue',
            'ý' => 'y',
            'þ' => 'b',
            'ÿ' => 'y',
            'Ŕ' => 'R',
            'ŕ' => 'r',
            'Ā' => 'A',
            'ā' => 'a',
            'Ē' => 'E',
            'ē' => 'e',
            'Ī' => 'I',
            'ī' => 'i',
            'Ō' => 'O',
            'ō' => 'o',
            'Ū' => 'U',
            'ū' => 'u',
            'œ' => 'oe',
            'ĳ' => 'ij',
            'ą' => 'a',
            'ę' => 'e',
            'ė' => 'e',
            'į' => 'i',
            'ų' => 'u',
            'Ą' => 'A',
            'Ę' => 'E',
            'Ė' => 'E',
            'Į' => 'I',
            'Ų' => 'U',
            "ľ" => "l",
            "Ľ" => "L",
            "ť" => "t",
            "Ť" => "T",
            "ů" => "u",
            "Ů" => "U",
            'ł' => 'l',
            'Ł' => 'L',
            'ń' => 'n',
            'Ń' => 'N',
            'ś' => 's',
            'Ś' => 'S',
            'ź' => 'z',
            'Ź' => 'Z',
            'ż' => 'z',
            'Ż' => 'Z',

            'ж' => 'zh',
            'ч' => 'ch',
            'щ' => 'sht',
            'ш' => 'sh',
            'ю' => 'yu',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ъ' => 'y',
            'ь' => 'x',
            'я' => 'q',
            'Ж' => 'Zh',
            'Ч' => 'Ch',
            'Щ' => 'Sht',
            'Ш' => 'Sh',
            'Ю' => 'Yu',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'J',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'c',
            'Ъ' => 'Y',
            'Ь' => 'X',
            'Я' => 'Q',
        ];

        $sString = strtr($sString, $aTable);

        return static::toKebabCase($sString);
    }

    /**
     * @param $sString
     *
     * @return string
     */
    public static function getCapitals($sString){
        return preg_replace('/[^A-Z]*/','',$sString);
    }

    /**
     * .
     *
     * @param string $sString
     * @param string $sDouble
     * @param string $sSingle
     *
     * @return null|string|string[]
     */
    protected static function getTranslatable($sString, $sDouble = '/--+?/', $sSingle = '-')
    {
        $sString = preg_replace(static::$abolish, '', $sString);
        $sString = preg_replace(static::$replace, $sSingle, $sString);
        $sString = trim(preg_replace('/([A-Z])/', $sSingle . '$1', $sString), $sSingle);
        $sString = preg_replace($sDouble, $sSingle, $sString);

        return $sString;
    }
}
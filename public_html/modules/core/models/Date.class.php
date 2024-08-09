<?php

/* Models and managers used by the CropSettings model */

class Date
{

    const FORMAT_DB = "yyyy-mm-dd hh:mm:ss";
    const FORMAT_NL = "dd-mm-yyyy hh:mm:ss";
    const FORMAT_DB_F = "%Y-%m-%d %H:%M:%S";
    const DAY = 86400;
    const HOUR = 3600;
    const MINUTE = 60;
    const DATE_TYPE = IntlDateFormatter::LONG;
    const TIME_TYPE = IntlDateFormatter::NONE;

    /** @var DateTime $dateTime */
    public $dateTime;

    private static $formatter;

    function __construct($mDate = null, $sFormat = null)
    {
        if (!$time = strtotime($mDate ?: 'NOW')) {
            $time = $mDate;
        }

        if (is_int($mDate)){
            $time = $mDate;
        }

        $this->dateTime = new DateTime('NOW');
        $this->dateTime->setTimestamp($time);
    }


    /**
     * check if this date is greater than given Date
     *
     * @param Date $oD
     *
     * @return boolean
     */
    public function greaterThan(Date $oD)
    {
        return $this->dateTime->getTimestamp() > $oD->dateTime->getTimestamp();
    }

    /**
     * Date is smaller than given
     *
     * @param Date $oD
     *
     * @return boolean
     */
    public function lowerThan(Date $oD)
    {
        return $this->dateTime->getTimestamp() < $oD->dateTime->getTimestamp();
    }

    /**
     * Date is lower than of equal to given
     *
     * @param Date $oD
     *
     * @return boolean
     */
    public function lowerEqualTo(Date $oD)
    {
        return $this->dateTime->getTimestamp() <= $oD->dateTime->getTimestamp();
    }

    /**
     * Date equals given
     *
     * @param Date $oD
     *
     * @return boolean
     */
    public function equalTo(Date $oD)
    {
        return $this->dateTime->getTimestamp() == $oD->dateTime->getTimestamp();
    }

    /**
     * add x days
     *
     * @param int $iD
     *
     * @return Date
     */
    public function addDays($iD)
    {
        $this->dateTime->modify(sprintf('%1$s DAYS', $iD));
        return $this;
    }

    /**
     * add x months
     *
     * @param int $iM
     *
     * @return Date
     */
    public function addMonths($iM)
    {
        $this->dateTime->modify(sprintf('%1$s MONTHS', $iM));
        return $this;
    }

    /**
     * add x years
     *
     * @param int $iY
     *
     * @return Date
     */
    public function addYears($iY)
    {
        $this->dateTime->modify(sprintf('%1$s YEARS', $iY));
        return $this;

    }

    /**
     * add x seconds
     *
     * @param int $iS
     *
     * @return Date
     */
    public function addSeconds($iS)
    {
        $this->dateTime->modify(sprintf('%1$s SECONDS', $iS));
        return $this;
    }

    /**
     * add x minutes
     *
     * @param int $iM
     *
     * @return Date
     */
    public function addMinutes($iM)
    {
        $this->dateTime->modify(sprintf('%1$s MINUTES', $iM));
        return $this;

    }

    /**
     * add x hours
     *
     * @param int $iH
     *
     * @return Date
     */
    public function addHours($iH)
    {
        $this->dateTime->modify(sprintf('%1$s HOURS', $iH));
        return $this;
    }

    /**
     * return days difference compared to given
     *
     * @param Date $oDate
     *
     * @return int
     */
    public function daysDiff(Date $oDate)
    {
        return $this->dateTime->diff($oDate->dateTime)->days;
    }

    /**
     * return years difference compared to given
     *
     * @param Date $oDate
     *
     * @return int
     */
    public function yearsDiff(Date $oDate)
    {
        return $this->dateTime->diff($oDate->dateTime)->y;

    }

    /**
     * return hours difference compared to given
     *
     * @param Date $oDate
     *
     * @return int
     */
    public function hoursDiff(Date $oDate)
    {
        return $this->dateTime->diff($oDate->dateTime)->h;
    }

    /**
     * return minutes difference compared to given
     *
     * @param Date $oDate
     *
     * @return int
     */
    public function minutesDiff(Date $oDate)
    {
        return $this->dateTime->diff($oDate->dateTime)->i;
    }

    /**
     * set Date to first day of the month for this Date
     *
     * @return Date
     */
    public function setFirstDayOfThisMonth()
    {
        $this->dateTime->modify('first day of this month');
        return $this;
    }

    /**
     * set Date to last day of this month
     *
     * @return Date
     */
    public function setLastDayOfThisMonth()
    {
        $this->dateTime->modify('last day of this month');
        return $this;

    }

    /**
     * set Date to the given day of the week for this Date
     *
     * @param int $iDayOfWeek
     *
     * @return Date
     */
    public function setDayOfWeek($iDayOfWeek)
    {
        $mapper    = [
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
            'fri',
            'sat',
            'sun',
        ];
        $this->dateTime->modify($mapper[$iDayOfWeek] . ' this week');

        return $this;
    }

    /**
     * set Date to first day of the year
     *
     * @return Date
     */
    public function setFirstDayOfYear()
    {
        $this->dateTime->modify('first day of this year');
        return $this;
    }

    /**
     * set Date to first day of the year
     *
     * @return Date
     */
    public function setLastDayOfYear()
    {
        $this->dateTime->modify('last day of this year');
        return $this;
    }

    /**
     * set Date to start of the day
     *
     * @return Date
     */
    public function setStartOfDay()
    {
        $this->dateTime->modify('today midnight');

        return $this;
    }

    /**
     * set Date to end of day
     *
     * @return Date
     */
    public function setEndOfDay()
    {
        $this->dateTime->modify('tomorrow midnight')->modify('-1 SECOND');
        return $this;
    }

    /**
     * set day
     *
     * @param int $iDay
     *
     * @return Date
     */
    public function setDay($iDay)
    {
        $this->dateTime->modify(sprintf('%1$s day of this month', $iDay));
        return $this;
    }

    /**
     * set month
     *
     * @param int $iMonth
     *
     * @return Date
     */
    public function setMonth($iMonth)
    {
        $this->dateTime->modify(sprintf('%1$s month', $iMonth));
        return $this;
    }

    /**
     * set Year
     *
     * @param int $iYear
     *
     * @return Date
     */
    public function setYear($iYear)
    {
        $this->dateTime->modify(sprintf('%1$s year', $iYear));
        return $this;
    }

    /**
     * check if the given date is the same as this one
     *
     * @param Date $oD
     *
     * @return boolean
     */
    public function sameDateAs(Date $oD)
    {
        $this->dateTime->getTimestamp() == $oD->dateTime->getTimestamp();
        return $this;
    }

    /**
     * return zodiac constellation
     *
     * @return string
     */
    public function getZodiacConstellation()
    {
        $aSigns = [
            [20, 'Capricorn'], // 22 December - 20 January
            [20, 'Aquarius'], // 21 January - 20 February
            [20, 'Pisces'], // 21 February - 20 March
            [20, 'Aries'], // 21 March - 20 April
            [20, 'Taurus'], // 21 April - 20 May
            [20, 'Gemini'], // 21 May - 20 June
            [22, 'Cancer'], // 21 June - 22 July
            [22, 'Leo'], // 23 July - 22 August
            [22, 'Virgo'], // 23 August - 22 September
            [22, 'Libra'], // 23 September - 22 October
            [22, 'Scorpio'], // 23 October - 22 November
            [21, 'Sagittarius'], // 23 November - 21 December
            [20, 'Capricorn'] // 22 December - 20 January
        ];

        return $this->dateTime->format('d') <= $aSigns[$this->iMonth - 1][0] ? $aSigns[$this->iMonth - 1][1] : $aSigns[$this->iMonth][1];
    }

    /*
     * get time by values and update Object
     *
     * @param int $iD Day
     * @param int $iM Month
     * @param int $iY Year
     * @param int $iH   Hour
     * @param int $iMn  Minute
     * @param int $iS Second
     */

    private function set($iD, $iM, $iY, $iH = 0, $iMn = 0, $iS = 0)
    {
        $this->dateTime = new DateTime(mktime($iH, $iMn, $iS, $iM, $iD, $iY));
        return $this;
    }

    /**
     * return formatted Date
     * For more info on how to use the formats, check out the link below
     * https://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters
     *
     * @param string $sFromat format to use
     *
     * @return string
     */
    public function format($sFormat)
    {
        static::getFormatter()->setPattern(static::convertFormat($this, $sFormat));
        return static::getFormatter()->format($this->dateTime);
    }

    /**
     * return formatted Date
     *
     * @param string $sFromat format to use
     *
     * @return string
     */
    public function legacyFormat($sFormat)
    {
        return $this->dateTime->format($sFormat);
    }

    /**
     * return a Dateobject based on a string
     *
     * @param string $sVal
     *
     * @return Date object
     */
    public static function strToDate($sVal)
    {
        return new Date($sVal);
    }

    /**
     * Get and cache IntlDateFormatter
     *
     * @return IntlDateFormatter
     */
    public static function getFormatter($sLocale = 'nl_NL', $dateType = self::DATE_TYPE, $timeType = self::TIME_TYPE)
    {
        if (!self::$formatter) {
            self::$formatter = new IntlDateFormatter($sLocale, $dateType, $timeType, 'Europe/Amsterdam');
        }

        return self::$formatter;
    }

    public function __get(string $name)
    {
        if ($name == 'iTime'){

            return $this->dateTime->format('u');

        }
    }

    /**
     * Quick strftime replacement
     *
     * @param $format
     * @param $time
     * @return string
     */
    public static function stringFromTime($format, $time = 'NOW')
    {
        return static::strToDate($time)->format($format);
    }

    /**
     * Convert strftime() format to IntlDateFormatter format
     * https://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters
     *
     * @param Date $date
     * @param $format
     * @return string
     */
    private static function convertFormat(Date $date, $format){
        return preg_replace_callback('/%[a-zA-Z]/', function ($letter) use ($date, $format) {
            return match ($letter[0]) {
                //Day formats
                '%a' => 'eee',
                '%A' => 'eeee',
                '%d' => 'dd',
                '%e' => 'd',
                '%j' => 'D',
                '%u' => 'ee',
                '%w' => 'e',

                //Week formats
                '%U' => 'w',
                '%V' => 'ww',
                '%W' => 'ww',

                //Month formats
                '%b' => 'MMM',
                '%B' => 'MMMM',
                '%h' => 'MMM',
                '%m' => 'MM',

                //Year formats
                '%C' => 'yyyy',
                '%g' => 'yy',
                '%G' => 'yyyy',
                '%y' => 'yy',
                '%Y' => 'yyyy',

                //Time formats
                '%H' => 'HH',
                '%k' => 'H',
                '%I' => 'hh',
                '%l' => 'h',
                '%M' => 'mm',
                '%p' => 'aa',
                '%P' => 'aa',
                '%r' => 'hh:mm:ss aa',
                '%R' => 'HH:mm',
                '%S' => 'ss',
                '%T' => 'HH:mm:ss',
                '%X' => 'HH:mm:ss',
                '%z' => 'ZZ',
                '%Z' => 'zz',

                //Time and Date Stamps formats
                '%c' => 'E dd LLL yyyy HH:mm:ss',
                '%D' => 'dd/LL/yy',
                '%F' => 'yyy-ll-dd',
                '%s' => $date->legacyFormat('U'),
                '%x' => 'dd/LL/yy',

                //Miscellaneous formats
                '%n' => '\n',
                '%t' => '\t',
                '%%' => '%',
            };
        }, $format);
    }

    public function __clone(): void
    {
        $this->dateTime = clone $this->dateTime;
    }

}
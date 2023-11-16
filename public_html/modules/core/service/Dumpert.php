<?php

class Dumpert
{
    /**
     * Constant maximum depth when outputting objects
     */
    const DEPTH = 7;

    /**
     * Constant space padding when outputting objects and arrays
     */
    const PADDING = 4;

    /**
     * Output string dump of the given values and terminate script execution
     *
     * @param array ...$aArguments
     */
    public static function end(...$aArguments)
    {
        static::dump(...$aArguments);

        exit;
    }

    /**
     * This does not concern you *waves hand like a jedi*
     *
     * @return string
     */
    private static function egg()
    {
        return rand(1, 20) > 15 ? '🐞' : '🐛';
    }

    /**
     * Output arguments
     *
     * @param array ...$aArguments
     */
    public static function output(...$aArguments)
    {
        $sPrefix = '';
        $sSuffix = '---';
        if (!(PHP_SAPI == 'cli')) {
            $sPrefix = "<pre>";
            $sSuffix = "</pre>";
        }

        $sOutput = $sPrefix . PHP_EOL . static::egg() . ' ';
        foreach ($aArguments as $sValue) {
            $sOutput .= $sValue . PHP_EOL;
        }

        echo $sOutput . $sSuffix . PHP_EOL;
    }

    /**
     * Output string dump of the given values
     *
     * @param array ...$aArguments
     */
    public static function dump(...$aArguments)
    {
        echo static::get(...$aArguments);
    }

    /**
     * Retrieve string dump of the given values
     *
     * @param array ...$aArguments
     *
     * @return string
     */
    public static function get(...$aArguments)
    {
        $sPrefix = '';
        $sSuffix = '---';
        if (!(PHP_SAPI == 'cli')) {
            $sPrefix = "<pre>";
            $sSuffix = "</pre>";
        }

        $sDump = '';
        foreach ($aArguments as $mValue) {
            $sDump .= static::flatten($mValue);
        }

        $aTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $aLine  = array_pop($aTrace);

        // support for the alias function _d()
        // support for the end() method
        if ($aLine['function'] != '_d' && (($aLine['class'] ?? null) != static::class && $aLine['function'] != 'end')) {
            $aLine = array_pop($aTrace);
        }

        return sprintf(
            '%1$s%5$s%2$s%5$s%3$s%5$s%4$s%5$s',
            $sPrefix,
            sprintf('%3$s Dump requested at [%1$s:%2$s]', $aLine['file'] ?? 0, $aLine['line'] ?? '', static::egg()),
            $sDump,
            $sSuffix,
            PHP_EOL
        );
    }

    /**
     * Retrieve the string dump for the given value
     *
     * @param mixed $mValue
     * @param int   $iIteration
     * @param bool  $bPadding
     *
     * @return string
     */
    public static function flatten($mValue, $iIteration = 0, $bPadding = true)
    {
        $sPadding = $bPadding ? str_repeat(' ', ($iIteration) * static::PADDING) : '';
        switch (true) {
            case is_bool($mValue):
                $s = $sPadding . 'boolean: ' . ($mValue ? 'true' : 'false');
                break;
            case is_array($mValue):
                $s = $sPadding . 'array: [' . PHP_EOL;
                if ($iIteration < static::DEPTH) {
                    foreach ($mValue as $mKey => $mElement) {
                        $s .= $sPadding . str_repeat(' ', ($iIteration + 1) * static::PADDING) . $mKey . ' => ' . static::flatten($mElement, $iIteration + 1, false);
                    }
                }
                $s .= $sPadding . str_repeat(' ', ($iIteration) * static::PADDING) . ']';
                break;
            case is_object($mValue):
                $s = $sPadding . 'object: ' . get_class($mValue) . PHP_EOL;
                if ($iIteration < static::DEPTH) {
                    $mIterate = is_callable([$mValue, '__debugInfo']) ? $mValue->__debugInfo() : $mValue;
                    foreach ($mIterate as $sKey => $mItem) {
                        $s .= $sPadding . str_repeat(' ', ($iIteration + 1) * static::PADDING) . $sKey . ' => ' . static::flatten($mItem, $iIteration + 1, false);
                    }
                }
                break;
            case is_string($mValue):
                $s = $sPadding . 'string: ' . (!(PHP_SAPI == 'cli') ? htmlentities($mValue) : $mValue);
                break;
            case is_int($mValue):
                $s = $sPadding . 'integer: ' . $mValue;
                break;
            case is_float($mValue):
                $s = $sPadding . 'float: ' . $mValue;
                break;
            default:
                $s = $sPadding . gettype($mValue);
                break;
        }

        return $s . PHP_EOL;
    }

    public static function backTrace($iOptions = 0, $iLimit = 0)
    {
        if (!(PHP_SAPI == 'cli')) {
            echo "<pre>";
        }
        $aTrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);

        // retrieve origin of stack trace request
        $aOrigin = array_shift($aTrace);
        printf(
            'Back trace requested at [%1$s:%2$s]%3$s%3$s',
            $aOrigin['file'],
            $aOrigin['line'],
            PHP_EOL
        );
        if (!(PHP_SAPI == 'cli')) {
            echo "</pre>";
        }

        debug_print_backtrace($iOptions, $iLimit);
    }

    /**
     * Output the current stack trace
     *
     */
    public static function stackTrace()
    {
        if (!(PHP_SAPI == 'cli')) {
            echo "<pre>";
        }
        $aTrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);

        // retrieve origin of stack trace request
        $aOrigin = array_shift($aTrace);
        printf(
            'Stack trace requested at [%1$s:%2$s]%3$s',
            $aOrigin['file'],
            $aOrigin['line'],
            PHP_EOL
        );
        // build trace
        foreach ($aTrace as $i => $aLine) {
            printf(
                '#%1$s %2$s called at [%3$s:%4$s]%5$s',
                $i,
                isset($aLine['class']) ? ($aLine['class'] . $aLine['type'] . $aLine['function']) : $aLine['function'],
                $aLine['file'],
                $aLine['line'],
                PHP_EOL
            );
        }
        if (!(PHP_SAPI == 'cli')) {
            echo "</pre>";
        }
    }
}

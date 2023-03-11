<?php

declare(strict_types=1);

namespace gugglegum\NumberParser;

/**
 * Number Parser
 *
 * Convert strings containing decimal numbers into numbers (int or float). But it does this by checking if the number
 * in the original string is correct and throwing an exception if something is wrong.
 *
 * Regular expressions used here:
 *
 * $digits = '(?:[1-9]\d*|0)';
 * $int = "(-)?{$digits}";                      // Used in parseInt()
 * $float = "(-)?(?:{$digits}?\.)?{$digits}";   // Used in parseFloat()
 * $expo = "{$float}(?:[eE][+-]?{$digits})?";   // Used in parseFloatExponent()
 */
class NumberParser
{
    /**
     * Parse a string containing an integer
     *
     * @param string $str
     * @return int
     */
    public static function parseInt(string $str): int
    {
        $result = (bool) preg_match('/^(-)?(?:[1-9]\d*|0)$/', $str, $m);
        if ($result) {
            $int = (int) $str;
            if (isset($m[1]) && $m[1] == '-' && $int === 0) { // negative zero ("-0")
                $result = false;
            }
        }
        if (!$result) {
            throw new \InvalidArgumentException("Invalid value \"{$str}\" - expected integer number");
        }
        return $int;
    }

    /**
     * Parse a string containing a float (without exponent)
     *
     * @param string $str
     * @return float
     */
    public static function parseFloat(string $str): float
    {
        $result = (bool) preg_match('/^(-)?(?:(?:[1-9]\d*|0)?\.)?(?:[1-9]\d*|0)$/', $str, $m);
        if ($result) {
            $float = (float) $str;
            if (isset($m[1]) && $m[1] == '-' && $float === 0.0) { // negative zero ("-0.0")
                $result = false;
            }
        }
        if (!$result) {
            throw new \InvalidArgumentException("Invalid value \"{$str}\" - expected float number");
        }
        return $float;
    }

    /**
     * Parse a string containing a float (with or without exponent)
     *
     * @param string $str
     * @return float
     */
    public static function parseFloatExponent(string $str): float
    {
        $result = preg_match('/^(-)?(?:(?:[1-9]\d*|0)?\.)?(?:[1-9]\d*|0)(?:[eE][+-]?(?:[1-9]\d*|0))?$/', $str, $m);
        if ($result) {
            $float = (float) $str;
            if (isset($m[1]) && $m[1] == '-' && $float === 0.0) { // negative zero (-0.0, -0e0, etc.)
                $result = false;
            }
        }
        if (!$result) {
            throw new \InvalidArgumentException("Invalid value \"{$str}\" - expected float number");
        }
        return $float;
    }
}

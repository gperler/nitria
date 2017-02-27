<?php

declare(strict_types = 1);

namespace Nitria;

class StringUtil
{

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function endsWith(string $haystack, string $needle) : bool
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return string
     */
    public static function getEndAfterLast(string $haystack, string $needle) : string
    {
        $lastOccurence = strrchr($haystack, $needle);
        if ($lastOccurence === false) {
            return $haystack;
        }
        return ltrim(strrchr($haystack, $needle), $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return string
     */
    public static function getStartBeforeLast(string $haystack, string $needle) : string
    {
        $end = self::getEndAfterLast($haystack, $needle);
        $length = -1 * (strlen($end) + strlen($needle));
        return substr($haystack, 0, $length);
    }
}
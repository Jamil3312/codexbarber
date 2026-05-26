<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Parse a HEX color and return an RGB string separated by spaces (e.g. "234 179 8")
     * compatible with Tailwind CSS opacity values syntax `rgb(var(--color) / <alpha-value>)`
     */
    public static function hexToRgbString($hex)
    {
        if (!$hex) {
            return "234 179 8"; // Default fallback: Tailwind yellow-500 (#eab308)
        }

        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) != 6) {
            return "234 179 8";
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "$r $g $b";
    }

    /**
     * Generate darker/lighter variants for CSS Variables mapping.
     */
    public static function adjustBrightness($hex, $steps)
    {
        if (!$hex) return self::hexToRgbString('#eab308');

        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) != 6) {
            return "234 179 8";
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        return "$r $g $b";
    }
}

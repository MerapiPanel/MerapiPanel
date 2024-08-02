<?php

namespace MerapiPanel\Views\Extension;

use MerapiPanel\Utility\AES;
use MerapiPanel\Views\Abstract\Extension;

class Text extends Extension
{

    function fl_preg_replace($subject, $pattern, $replacement)
    {
        return preg_replace($pattern, $replacement, $subject);
    }


    public function fl_truncate($text, $length, $ellipsis = '...')
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        // Truncate the text to the specified length and append ellipsis
        return rtrim(mb_substr($text, 0, $length)) . $ellipsis;
    }


    function fl_ucfirst($string)
    {
        return ucfirst($string);
    }

    function fl_number($number, $fill = 3)
    {
        $number = intval($number);
        $numbers = str_split("$number");

        if (count($numbers) >= $fill) {
            return implode("", $numbers);
        }
        $arr = [
            ...array_fill(0, $fill - count($numbers), 0),
            ...$numbers
        ];
        return implode("", $arr);
    }
}

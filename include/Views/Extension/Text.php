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


    function fl_encode($string)
    {
        return bin2hex($string);
    }
    function fl_decode($string)
    {

        return hex2bin($string);
    }

    function fn_encode($string)
    {
        return bin2hex($string);
    }
    function fn_decode($string)
    {

        return hex2bin($string);
    }


    function fl_urlencode($string)
    {
        return urlencode("$string");
    }
    function fl_urldecode($string)
    {
        return urldecode("$string");
    }

    function fl_aes_encrypt($string)
    {
        return AES::encrypt($string);
    }
    function fl_aes_decrypt($string)
    {
        return AES::decrypt($string);
    }

    function fn_base64_encode($string)
    {

        return urlencode(base64_encode(trim($string)));
    }


    function fn_base64_decode($string)
    {

        return base64_decode(urldecode(trim($string)));
    }

}

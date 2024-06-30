<?php

namespace MerapiPanel\Views\Extension;

use InvalidArgumentException;
use MerapiPanel\Utility\AES;
use MerapiPanel\Views\Abstract\Extension;

class Encode extends Extension
{

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

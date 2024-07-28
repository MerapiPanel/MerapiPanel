<?php

namespace MerapiPanel\Views\Extension;

use MerapiPanel\Utility\AES;
use MerapiPanel\Views\Abstract\Extension;

class Bundle extends Extension
{

    public function fl_url(mixed $path, array $pattern = [])
    {

        $parse = parse_url($path ?? "");
        if (!isset($parse['scheme'])) {
            $parse['scheme'] = $_SERVER['REQUEST_SCHEME'];
        }
        if (!isset($parse['host'])) {
            $parse['host'] = $_SERVER['HTTP_HOST'];
        }

        $result_path = $parse['path'];
        preg_match_all('/\:([a-z0-9]+)/i', $result_path, $matches);


        if (isset($matches[1])) {
            foreach ($matches[1] as $value) {
                $result_path = preg_replace('/\:' . $value . '/', (isset($pattern[$value]) && !empty($pattern[$value]) ? $pattern[$value] : ""), $result_path);
            }
        }
        return $parse['scheme'] . "://" . $parse['host'] . "/" . ltrim($result_path, "/");
    }


    public function fn_time()
    {
        return time();
    }


    public function fn_microtime($as_float = true)
    {
        return microtime($as_float);
    }





    function fl_access_path(string $path)
    {

        if (!isset($_ENV["__MP_ACCESS__"], $_ENV["__MP_" . strtoupper($_ENV["__MP_ACCESS__"]) . "__"])) {
            return $path;
        }
        $access = $_ENV["__MP_" . strtoupper($_ENV["__MP_ACCESS__"]) . "__"];
        $prefix = $access["prefix"];

        return rtrim($prefix, "/") . "/" . ltrim($path, "/");
    }



    function fl_preg_replace($subject, $pattern, $replacement)
    {
        return preg_replace($pattern, $replacement, $subject);
    }


    /**
     * Get block content from the context
     * @option needs_context true
     */
    public function fn_block_content(array $context, string $blockName)
    {
        // Get block content from the context
        $blockContent = $context['_view']->renderBlock($blockName);

        return $blockContent;
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


    function fn_error_log($message)
    {

        error_log(is_string($message) ? $message : print_r($message, 1));
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

    function fn_is_string($value)
    {
        return is_string($value);
    }

    function fn_is_numberic($value)
    {
        return is_numeric($value);
    }

    function fn_is_array($value)
    {
        return is_array($value);
    }
}

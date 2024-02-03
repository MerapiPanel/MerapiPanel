<?php

namespace MerapiPanel\Core\Token;

use MerapiPanel\Core\AES;

class Token
{



    
    public static function generate()
    {

        $jwt = [
            "ip"     => self::getClientIP(),
            "origin" => self::getCurrentUrl(),
            "time"   => time(),
        ];
        $token = AES::encrypt(json_encode($jwt));

        return $token;
    }





    public static function validate($token)
    {

        $jwt = json_decode(AES::decrypt($token), true);
        if (!$jwt) {
            error_log("Invalid token: " . json_encode($jwt));
            return false;
        }
        if (!isset($jwt['ip']) || !isset($jwt['origin'])) {

            error_log("Invalid ip or origin: " . json_encode($jwt));
            return false;
        }
        if ($jwt['ip'] !== self::getClientIP() || $jwt['origin'] !== $_SERVER['HTTP_REFERER']) {
            error_log("Unmatch ip or origin: " . json_encode($jwt));
            return false;
        }

        if (!isset($jwt['time']) || $jwt['time'] + 3600 < time()) {
            error_log("Expired token: " . json_encode($jwt));
            return false;
        }

        error_log("Valid token: " . json_encode($jwt));
        return true;
    }





    public static function uniqidReal($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }





    static function getClientIP()
    {

        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] !== '') {
            // Use X-Forwarded-For header if present
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            // Otherwise, use REMOTE_ADDR
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // Remove any potential multiple IP addresses (e.g., proxy chaining)
        $ip = explode(',', $ip);
        $ip = trim($ip[0]);

        return $ip;
    }





    static function getCurrentUrl()
    {
        $protocol = 'http';
        // Check if SSL is present
        if (
            isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
            || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
        ) {
            $protocol = 'https';
        }
        // Construct URL
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}

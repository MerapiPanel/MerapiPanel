<?php

namespace MerapiPanel\Core;

class AES
{


    // Encryption function
    static function encrypt($data)
    {
        $key = self::getKey();
        if (!$key) {
            return false;
        }
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }



    // Decryption function
    static function decrypt($data)
    {
        $key = self::getKey();
        if (!$key) {
            return false;
        }
        list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    }



    private static function getKey(): string|false
    {
        if (isset($GLOBALS['config']) && isset($GLOBALS['config']['aes-key'])) {
            return $GLOBALS['config']['aes-key'];
        }
        return false;
    }
}

<?php

namespace MerapiPanel\Core;

class AES
{
    private static function getKey(): string|false
    {
        return $_ENV['AES_KEY'] ?? false;
    }

    static function encrypt(string $data): string|false
    {
        $key = self::getKey();
        if (!$key) {
            return false;
        }
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    static function decrypt(string $data): string|false
    {
        $key = self::getKey();
        if (!$key) {
            return false;
        }
        [$encryptedData, $iv] = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    }
}
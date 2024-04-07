<?php

namespace MerapiPanel\Utility;

class AES
{
    private static function getKey(): string|false
    {
        return $_ENV['__MP_AES_KEY__'] ?? false;
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
        // Store current error reporting level
        // $originalErrorReporting = error_reporting();

        // error_reporting(0);

        $key = self::getKey();
        if (!$key) {
            // Restore original error reporting level before returning
           // error_reporting($originalErrorReporting);
            return false;
        }
        try {
            
            [$encryptedData, $iv] = @explode('::', base64_decode($data), 2);
            if(!$encryptedData || !$iv) {
                // Restore original error reporting level before returning
              //  error_reporting($originalErrorReporting);
                return false;
            }
            $decryptedData = @openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);

            // Restore original error reporting level before returning
           // error_reporting($originalErrorReporting);

            return $decryptedData;
        } catch (\Throwable $e) {
            // Restore original error reporting level before returning
           // error_reporting($originalErrorReporting);
            return false;
        } 
    }

}
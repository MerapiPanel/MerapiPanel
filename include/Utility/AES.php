<?php

namespace MerapiPanel\Utility;

class AES
{
    private const AES_METHOD = 'aes-256-cbc';
    private const RSA_METHOD = OPENSSL_ALGO_SHA256; // You may adjust the RSA encryption method

    public static function encrypt(string $data): ?string
    {
        try {
            // Generate a random AES key and IV
            $aesKey = openssl_random_pseudo_bytes(32); // 256-bit key
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::AES_METHOD));

            // Encrypt the data using AES
            $encryptedData = openssl_encrypt($data, self::AES_METHOD, $aesKey, OPENSSL_RAW_DATA, $iv);

            // Encrypt the AES key using RSA
            $publicKey = openssl_pkey_get_public(file_get_contents($_ENV['__MP_PUBLIC_KEY__']));
            if (!$publicKey) {
                return null; // Failed to load public key
            }
            openssl_public_encrypt($aesKey, $encryptedKey, $publicKey);

            // Combine the encrypted AES key and IV with the encrypted data
            return base64_encode($encryptedKey . '::' . $iv . '::' . $encryptedData);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function decrypt(string $encryptedData): ?string
    {
        try {
            // Extract the encrypted AES key, IV, and data
            [$encryptedKey, $iv, $encryptedData] = explode('::', base64_decode($encryptedData), 3);

            // Decrypt the AES key using RSA
            $privateKey = openssl_pkey_get_private(file_get_contents($_ENV['__MP_PRIVATE_KEY__']));
            if (!$privateKey) {
                return null; // Failed to load private key
            }
            openssl_private_decrypt($encryptedKey, $aesKey, $privateKey);

            // Decrypt the data using the decrypted AES key and IV
            return openssl_decrypt($encryptedData, self::AES_METHOD, $aesKey, OPENSSL_RAW_DATA, $iv);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
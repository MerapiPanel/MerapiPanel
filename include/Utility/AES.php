<?php

namespace MerapiPanel\Utility;

class AES
{

    static function encrypt(string $data): mixed
    {

        // Load public key from file
        $publicKey = openssl_pkey_get_public(file_get_contents($_ENV['__MP_PUBLIC_KEY__']));
        // Encrypt data with public key
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return base64_encode($encrypted);

    }

    static function decrypt(string $data): mixed
    {
        
        // Load private key from file
        $privateKey = openssl_pkey_get_private(file_get_contents($_ENV['__MP_PRIVATE_KEY__']));
        // Decrypted data
        openssl_private_decrypt(base64_decode($data), $decrypted, $privateKey);
        return $decrypted;
    }

}
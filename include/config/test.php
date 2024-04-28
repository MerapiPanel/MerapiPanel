<?php

// Load public key from file
$publicKey = openssl_pkey_get_public(file_get_contents(__DIR__ . '/public_key.pem'));

// Data to be encrypted
$data = "Hello, world!";

// Encrypt data with public key
openssl_public_encrypt($data, $encrypted, $publicKey);

// Encrypted data
echo "Encrypted data: " . base64_encode($encrypted) . "\n";



// Load private key from file
$privateKey = openssl_pkey_get_private(file_get_contents(__DIR__ . '/private_key.pem'));

// Decrypted data
openssl_private_decrypt($encrypted, $decrypted, $privateKey);

// Decrypted data
echo "Decrypted data: " . $decrypted . "\n";


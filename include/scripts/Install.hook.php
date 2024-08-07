<?php

function befor_install($target_dir)
{

    $private_key = rtrim($target_dir, "\\/") . "/include/config/private_key.pem";
    $envFile     = rtrim($target_dir, "\\/") . "/include/config/env.php";
}


function after_install($target_dir)
{
}



function generatePrivateKey($config_dir)
{

    // Configuration options for the private key
    $config = array(
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Generate a new private (and public) key pair
    $resource = openssl_pkey_new($config);
    // Extract the private key from the pair
    openssl_pkey_export($resource, $privateKey);

    // Save the private key to a file
    file_put_contents(rtrim($config_dir, '\\/') . '/private_key.pem', $privateKey);
}

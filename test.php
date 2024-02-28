<?php

function uniqReal($desiredLength = 16)
{
    // Generate random bytes based on availability of PHP functions
    if (function_exists("random_bytes")) {
        $randomBytes = random_bytes(ceil($desiredLength / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $randomBytes = openssl_random_pseudo_bytes(ceil($desiredLength / 2));
    } else {
        throw new Exception("No suitable random bytes generator available");
    }

    // Convert bytes to hex and trim to the desired length
    $hexString = substr(bin2hex($randomBytes), 0, $desiredLength);

    // Calculate position to insert hyphen
    $insertPosition = 0;
    foreach (range(1, 10) as $divisor) {
        $position = round($desiredLength / $divisor);
        if (6 <= $position && $position <= 8) {
            if (($desiredLength / $divisor) > $position) {
                $position -= 1;
            }
            $insertPosition = $position;
            break;
        }
    }

    // Insert hyphens at the calculated position if it's greater than 0
    if ($insertPosition > 0) {
        $pattern = "/(.{" . $insertPosition . "})/u";
        $hexString = preg_replace($pattern, "$1-", $hexString);
        // Remove any trailing hyphen
        $hexString = rtrim($hexString, "-");
    }

    return $hexString;
}


echo uniqReal(20);

<?php

namespace MerapiPanel\Utility;

class Util
{

    public static function uniq($lenght = 13)
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


    public static function getNamespaceFromFile($filePath)
    {
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new \Exception("Unable to read the file: $filePath");
        }

        // Regular expression for matching the namespace line
        $pattern = '/^namespace\s+([^;]+);/m';

        if (preg_match($pattern, $fileContent, $matches)) {
            return $matches[1]; // The first capturing group contains the namespace
        }

        return null; // Return null if no namespace is found
    }



    public static function getClassNameFromFile($filePath)
    {
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new \Exception("Unable to read the file: $filePath");
        }

        // Regular expressions for matching the namespace and class name
        $namespacePattern = '/^namespace\s+([^;]+);/m';
        $classPattern = '/^class\s+([a-zA-Z0-9_]+)/m';

        // Match namespace
        if (preg_match($namespacePattern, $fileContent, $namespaceMatches)) {
            $namespace = trim($namespaceMatches[1]);
        } else {
            $namespace = null;
        }

        // Match class name
        if (preg_match($classPattern, $fileContent, $classMatches)) {
            $className = trim($classMatches[1]);
        } else {
            $className = null;
        }

        // Combine namespace and class name
        if ($namespace !== null && $className !== null) {
            $fullyQualifiedName = $namespace . '\\' . $className;
        } else {
            $fullyQualifiedName = null;
        }

        return $fullyQualifiedName;
    }
}

<?php

require_once __DIR__ . "/src/functions.php";


if (!function_exists('il4mb\Mpanel\generateTree')) {

    echo "Function not exists!\nExit with unsuccessful";
}

$output = "tree.txt";
\il4mb\Mpanel\generateTree($output, __DIR__ . "/src");
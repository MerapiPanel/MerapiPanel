<?php

function befor_install($target_dir)
{

    $envFile     = rtrim($target_dir, "\\/") . "/include/config/env.php";
    file_put_contents(__DIR__ . "env.bak", file_get_contents($envFile));
}


function after_install($target_dir) {}

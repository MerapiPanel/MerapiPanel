<?php

return [
    "debug" => false,
    "cache" => 0,
    "globkey" => __DIR__ . "/globkey.pem",
    "timezone" => "Asia/Jakarta",
    "roles" => [
        "admin",
        "editor",
        "contributor",
        "manager",
        "moderator",
        "user"
    ],
    "admin" => [
        "prefix" => "/panel/admin",
        "middleware" => "Auth"
    ],
    "database" => [
        "host" => "localhost",
        "port" => 3306,
        "username" => "root",
        "password" => "",
        "database" => "merapi"
    ]
];

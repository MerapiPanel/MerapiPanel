<?php

return [
    "debug" => true,
    "cache" => false,
    "uuid" => "a3af08095b8a63cf50d35129d514ca2703c89d159963dc7a53e5766361bbc3c9",
    "private-key" => __DIR__ . "/private_key.pem",
    "public-key" => __DIR__ . "/public_key.pem",
    "timezone" => "Asia/Jakarta",
    "service" => [
        "Panel",
        "Setting",
        "Editor"
    ],
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
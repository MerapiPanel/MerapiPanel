<?php

return [
    "name" => "editor-hallo",
    "label" => "Dashboard Hallo",
    "description" => "Dashboard block",
    "version" => "1.0.0",
    "author" => "ilham b",
    "extend" => "text",
    "defaults" => [

        "components" => [
            "type" => "text",
            "components" => "Hallo World"
        ]

    ],
    "index" => "file:./dist/index.js",
];
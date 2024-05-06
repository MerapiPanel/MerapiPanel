<?php

$fetchURL = $_ENV['__MP_ADMIN__']['prefix'] . "/api/Contact/fetchAll";
$templateFetchURL = $_ENV['__MP_ADMIN__']['prefix'] . "/api/Contact/Template/fetchAll";

return [
    "name" => "contact-btn",
    "label" => "Contact Button",
    "version" => "1.0.0",
    "author" => "ilham b",
    "extend" => "text",
    "media" => '<i class="fa-solid fa-phone fa-3x"></i>',
    "defaults" => [
        "fetchURL" => $fetchURL,
        "templateFetchURL" => $templateFetchURL,
        "attributes" => [
            "class" => "btn btn-sm btn-primary rounded-5 px-4",
        ],
        "components" => "Hallo World",
        "traits" => [
            [
                'type' => 'checkbox',
                'name' => 'use_template',
                'label' => 'Use Template',
                'value' => false
            ],
            [
                "type" => "select",
                "name" => "contact-type",
                "label" => "Contact Type",
                "options" => [ // Array of options
                    ["id" => 'phone', "label" => 'Phone'],
                    ["id" => 'email', "label" => 'Email'],
                    ["id" => 'whatsapp', "label" => 'Whatsapp'],
                ],
                "value" => 'phone'
            ],
            [
                "type" => "select",
                "name" => "contact",
                "label" => "Contact",
                "options" => [ // Array of options
                    ["id" => '', "label" => '-- Select Contact --']
                ],
                "value" => ''
            ]
        ]
    ],
    "index" => "file:./dist/index.js",
];
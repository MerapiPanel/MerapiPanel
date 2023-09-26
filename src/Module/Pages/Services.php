<?php

namespace MerapiPanel\Module\Pages;


class Services
{



    private function validateTable($db) {

        $SQL = "CREATE TABLE IF NOT EXISTS `pages` (
            `id` int(11) PRIMARY KEY AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `meta_title` varchar(255) NOT NULL,
            `meta_description` varchar(255) NOT NULL,
            `meta_keywords` varchar(255) NOT NULL,

        )"
    }
}
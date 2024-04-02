<?php


namespace MerapiPanel\Module\Dashboard\Views;

use MerapiPanel\Box;

class Widget
{


    /**
     * @title Visit Analytics
     * @description Visit Analytics
     * @icon fa-solid fa-chart-line
     */
    public final function analytics()
    {

        return [
            "css" => [
                Box::module("FileManager")->service("assets")->url("!@dashboard/css/style.css")
            ],
            "js" => [
                "https://cdn.jsdelivr.net/npm/chart.js",
                Box::module("FileManager")->service("assets")->url("!@dashboard/js/analytics.js") . "?time=" . time(),
            ],
            "content" => '<h1>Cart Analytics</h1><div style="position: relative;"><canvas style="width: 100%; height: 100%;" id="myChart"></canvas></div>'
        ];
    }
}
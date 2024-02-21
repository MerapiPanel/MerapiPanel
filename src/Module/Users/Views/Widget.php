<?php


namespace MerapiPanel\Module\Users\Views;
use MerapiPanel\Box;

class Widget
{
    
    
    /**
     * @title User Activity
     * @description Users Analytics
     * @icon fa-solid fa-chart-line
     */
    public final function userActivity() {

        return [
            "css" => [
                
            ],
            "js"  => [
                Box::module("FileManager")->service("assets")->url("!@users/widget/js/activity.js") . "?time=" . time(),
            ],
            "data" => [],
            "content" => '<div>User Activity</div><div style="position: relative;"><canvas style="width: 100%; height: 100%;" id="myChart"></canvas></div>'
        ];
    }
}
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
    public final function userActivity()
    {

        return [
            "css" => [

            ],
            "js" => [
                Box::module("FileManager")->service("assets")->url("!@users/widget/js/activity.js") . "?time=" . time(),
            ],
            "data" => [],
            "content" => '<div>User Activity</div><div style="position: relative;"><canvas style="width: 100%; height: 100%;" id="myChart"></canvas></div>'
        ];
    }


    /**
     * @title Welcome
     * @description Welcome Message to the User
     * @icon fa-solid fa-user
     */
    public final function welcome()
    {

        date_default_timezone_set('Asia/Jakarta'); // Set the timezone to Jakarta

        $current_time = date('H'); // Get the current hour in 24-hour format

        $say = "";
        if ($current_time >= 5 && $current_time < 12) {
            $say = "Good morning!";
        } elseif ($current_time >= 12 && $current_time < 15) {
            $say = "Good afternoon!";
        } elseif ($current_time >= 15 && $current_time < 18) {
            $say = "Good evening!";
        } elseif ($current_time >= 18 || $current_time < 5) {
            $say = "Good night!";
        }



        $session = Box::module("auth")->getSession();
        $content = "<h1 style='text-align: center; padding: 20px; color: #444444'>Welcome <b>" . $session['username'] . "</b>, " . $say . "</h1><script>window.onload = () => { document.body.classList.add('loaded') }</script>";

        return [
            "css" => [

            ],
            "content" => $content,
        ];
    }
}
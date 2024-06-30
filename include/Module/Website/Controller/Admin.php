<?php

namespace MerapiPanel\Module\Website\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Controller;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;

class Admin extends __Controller
{

    function register()
    {

        if ($this->module->getRoles()->isAllowed(0)) {

            Box::module("Panel")->addMenu([
                "name" => "Website",
                "icon" => "fa-solid fa-globe",
                "link" => Router::GET("website", [$this, 'index']),
                "children" => [
                    [
                        "name" => "Customize",
                        "icon" => "fa-solid fa-paint-roller",
                        "link" => Router::GET("website/customize", [$this, 'customize'])
                    ]
                ]
            ]);
        }
    }


    function index()
    {
        return View::render('admin/index');
    }

    function customize()
    {
        return View::render("admin/customize", []);
    }
}

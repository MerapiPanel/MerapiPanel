<?php

namespace MerapiPanel\Module\Content\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    protected $box;

    public function setBox(Box $box)
    {

        $this->box = $box;
    }

    function register($router)
    {

        $panel = $this->box->Module_Panel();

        $panel->addMenu([
            "name" => "Content",
            "icon" => "fa-solid fa-newspaper",
            "link" => $panel->adminLink("contents")
        ]);

        $panel->addMenu([
            "parent" => "content",
            "name" => "List of content",
            "icon" => "fa-solid fa-bars-staggered",
            "link" => $panel->adminLink("contents/list")
        ]);
        $panel->addMenu([
            "name" => "Create new content",
            "parent" => "content",
            "icon" => "fa fa-plus",
            "link" => $panel->adminLink("contents/new")
        ]);


        $router->get("/contents", "index", self::class);
        $router->get("/contents/list", "list", self::class);
        $router->get("/contents/new", "new", self::class);


    }

    function index($view)
    {

        return $view->render("index.html.twig");
    }

    function new($view)
    {
        return $view->render("new.html.twig");
    }

    function list($view)
    {
        return $view->render("list.html.twig");
    }
}

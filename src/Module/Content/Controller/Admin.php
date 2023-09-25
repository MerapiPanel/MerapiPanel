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



        $index =  $router->get("/content", "index", self::class);
        $list = $router->get("/content/list", "list", self::class);
        $new =  $router->get("/content/create", "createNewContent", self::class);

        $panel->addMenu([
            "name" => "Content",
            "icon" => "fa-solid fa-newspaper",
            "link" => $index
        ]);

        $panel->addMenu([
            "parent" => "content",
            "name" => "List of content",
            "icon" => "fa-solid fa-bars-staggered",
            "link" => $list
        ]);
        $panel->addMenu([
            "name" => "Create new content",
            "parent" => "content",
            "icon" => "fa fa-plus",
            "link" => $new
        ]);
    }

    function index($view)
    {

        return $view->render("index.html.twig");
    }

    function createNewContent($view)
    {
        return $view->render("editor.html.twig");
    }

    function list($view)
    {
        return $view->render("list.html.twig");
    }
}

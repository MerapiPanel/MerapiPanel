<?php

namespace MerapiPanel\Module\Template\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Module\Template\Custom\Extension;

class Admin extends Module
{

    protected $box;


    function setBox(Box $box)
    {
        $this->box = $box;
    }


    public function register($router)
    {

        $router->get("/template/create", "createNewTemplate", self::class);
        $route = $router->get("/template", "index", self::class);
        $panel = $this->box->Module_Panel();
        $panel->addMenu([
            'order' => 3,
            "parent" => "",
            'name' => "Template",
            'icon' => 'fa-solid fa-brush',
            'link' => $route->getPath()
        ]);
    }


    public function index($view)
    {
        $this->box->Module_ViewEngine()->addExtension(new Extension());
        return $view->render("index.html.twig");
    }


    public function createNewTemplate($view)
    {

        return $view->render("editor.html.twig");
    }
}

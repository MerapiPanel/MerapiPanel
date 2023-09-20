<?php

namespace MerapiQu\Module\Pages\Controller;

use MerapiQu\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {

        $router->get('pages', 'index', self::class);

        $panel = $this->getBox()->Module_Panel();

        $panel->addNav([
            'order' => 1,
            'name' => 'Pages',
            'icon' => 'fa-solid fa-pager',
            'link' => $this->box->module_site()->adminLink('pages')
        ]);

        $panel->addNav([
            'order' => 1,
            'parent' => 'Pages',
            'name' => "List Page",
            "icon" => 'fa-solid fa-bars-staggered',
            'link' => "/pages"
        ]);

        $panel->addNav([
            'order' => 2,
            'parent' => 'Pages',
            'name' => "New Page",
            "icon" => "fa-solid fa-plus",
            'link' => "/pages"
        ]);

    }


    public function index($view)
    {

        return $view->render("index.html.twig");
    }
}

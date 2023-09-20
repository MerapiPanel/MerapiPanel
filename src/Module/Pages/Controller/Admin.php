<?php

namespace MerapiQu\Module\Pages\Controller;

use MerapiQu\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {

        $router->get('pages', 'index', self::class);
        $router->get('pages/all', 'all', self::class);
        $router->get('pages/new', 'new', self::class);

        $panel = $this->getBox()->Module_Panel();
        $site  = $this->box->module_site();
        $panel->addMenu([
            'order' => 1,
            'name' => 'Pages',
            'icon' => 'fa-solid fa-pager',
            'link' => $site->adminLink('pages')
        ]);

        $panel->addMenu([
            'order' => 1,
            'parent' => 'Pages',
            'name' => "List Page",
            "icon" => 'fa-solid fa-bars-staggered',
            'link' => $site->adminLink('pages/all')
        ]);

        $panel->addMenu([
            'order' => 2,
            'parent' => 'Pages',
            'name' => "New Page",
            "icon" => "fa-solid fa-plus",
            'link' => $site->adminLink('pages/new')
        ]);
    }


    public function index($view)
    {

        return $view->render("index.html.twig");
    }

    public function all($view)
    {

        return $view->render("all.html.twig");
    }

    public function new($view)
    {
        return $view->render("new.html.twig");
    }
}

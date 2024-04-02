<?php

namespace MerapiPanel\Module\Pages\Controller;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;

class Guest extends __Fragment
{

    protected $module;

    public function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        $this->module = $module;
    }

    public function register()
    {
        Router::GET("/page/{slug}/", "loadPage", self::class);
    }


    function loadPage(Request $request)
    {

        $slug = $request->slug();
        $page = $this->module->getPageBySlug($slug);

        if ($page) {

            return View::render("page.html.twig", [
                "page" => $page
            ]);
        }

        return View::render("404.html.twig");
    }
}
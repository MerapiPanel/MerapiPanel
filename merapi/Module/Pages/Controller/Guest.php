<?php

namespace MerapiPanel\Module\Pages\Controller;

use MerapiPanel\BoxModule;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;
use MerapiPanel\Module\Pages\Views\PageViewFunction;
use MerapiPanel\Utility\Http\Request;

class Guest extends Module {

    public function register($router)
    {

        View::AddExtension(new PageViewFunction());

        $router->get("/page/{slug}/", "loadPage", self::class);
    }


    function loadPage(Request $request) {

        $slug = $request->slug();
        $page = $this->service()->getPageBySlug($slug);

        if($page) {

            return View::render("page.html.twig", [
                "page" => $page
            ]);
        }

        return View::render("404.html.twig");
    }
}
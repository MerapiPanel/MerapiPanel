<?php

namespace MerapiPanel\Module\Pages\Controller;

use MerapiPanel\Core\Abstract\Module;

class Guest extends Module {

    public function register($router)
    {

        $router->get("/page/{slug}/", "loadPage", self::class);
    }


    function loadPage($view, $request) {

        $slug = $request->getParam("slug");
        $page = $this->service()->getPageBySlug($slug);

        if($page) {

            if($page['template_id']) {

                $template = $this->box->Module_Template()->getTemplate($page['template_id']);
                if($template) {

                    return $view->render("page.html.twig", [
                        "page" => $page,
                        "template" => $template
                    ]);
                }
            }

            return $view->render("page.html.twig", [
                "page" => $page
            ]);
        }

        return $view->render("404.html.twig");
    }
}
<?php

namespace MerapiPanel\Module\Pages\Controller;

use MerapiPanel\Core\Abstract\Module;

class Admin extends Module
{

    public function register($router)
    {

        $router->post('pages/endpoint', 'save', self::class);
        $router->post('pages/endpoint/assign', 'assignTemplate', self::class);

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



    public function save($view, $request)
    {

        $BODY = $request->getRequestBody();
        if (!isset($BODY['title']) || empty($BODY['title'])) {
            return [
                "code" => 400,
                "message" => "Title is required"
            ];
        }

        if (!isset($BODY['slug']) || empty($BODY['slug'])) {
            return [
                "code" => 400,
                "message" => "Slug is required"
            ];
        }


        $title = $BODY['title'];
        $slug = $BODY['slug'];

        $service = $this->service();
        $id = $service->createPage($title, $slug);

        if (!$id) {

            return [
                "code" => 400,
                "message" => "Error creating page"
            ];
        } else {
            return [
                "code" => 200,
                "message" => "Page created successfully",
                "data" => [
                    "params" => [
                        "title" => $title,
                        "slug" => $slug,
                        "id" => $id
                    ]
                ]
            ];
        }
    }


    function assignTemplate($view, $request)
    {

        $BODY = $request->getRequestBody();
        if (!isset($BODY['page_id']) || empty($BODY['page_id'])) {
            return [
                "code" => 400,
                "message" => "Id is required"
            ];
        }

        if (!isset($BODY['template_id']) || empty($BODY['template_id'])) {
            return [
                "code" => 400,
                "message" => "Template is required"
            ];
        }

        $service = $this->service();
        if($service->assignTemplate($BODY['page_id'], $BODY['template_id'])) {

            return [
                "code" => 200,
                "message" => "Template assigned successfully"
            ];
        } 

        return [
            "code" => 400,
            "message" => "Error assigning template"
        ];
    }
}

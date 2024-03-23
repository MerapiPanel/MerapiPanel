<?php

namespace MerapiPanel\Module\Pages\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Views\View;

class Admin extends Module
{

    public function register($router)
    {

        $router->post('pages/endpoint', 'save', self::class);
        $router->post('pages/endpoint/assign', 'assignTemplate', self::class);
        $pages = $router->get('pages', 'index', self::class);
        $router->get('pages/all', 'all', self::class);
        $router->get('pages/new', 'new', self::class);

        Box::module("panel", [
            "addMenu" => [
                [
                    'order' => 1,
                    'name'  => 'Pages',
                    'icon'  => 'fa-solid fa-pager',
                    'link'  => $pages
                ]
            ]
        ]);
    }


    public function index($req)
    {

        return View::render("index.html.twig");
    }

    public function all($req)
    {

        return View::render("all.html.twig");
    }

    public function new($req)
    {
        return View::render("new.html.twig");
    }



    public function save($request)
    {

        $title = $request->title();
        $slug = $request->slug();

        if (empty($this)) {
            return [
                "code" => 400,
                "message" => "Title is required"
            ];
        }

        if (empty($slug)) {
            return [
                "code" => 400,
                "message" => "Slug is required"
            ];
        }

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


    function assignTemplate($request)
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
        if ($service->assignTemplate($BODY['page_id'], $BODY['template_id'])) {

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

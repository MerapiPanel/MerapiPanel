<?php

namespace MerapiPanel\Module\Template\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Module\Template\Custom\Extension;
use MerapiPanel\Module\Template\Custom\TemplateFunction;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    protected $box;


    function setBox(Box $box)
    {
        $this->box = $box;
        $this->box->Module_ViewEngine()->addExtension(new TemplateFunction());
    }


    public function register($router)
    {

        $router->get("/template/view/{id}/", "viewTemplate", self::class);
        $router->get("/template/edit/{id}/", "editTemplate", self::class);
        $router->get("/template/create", "createNewTemplate", self::class);
        $router->post("/template/save", "saveTemplate", self::class);
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
        return $view->render("index.html.twig");
    }


    public function createNewTemplate($view)
    {

        return $view->render("editor.html.twig");
    }


    public function viewTemplate($view, Request $request)
    {

        $id = $request->getParam("id");
        $service = $this->service();

        return $view->render("view.html.twig", [
            "template" => $service->getTemplate($id)
        ]);
    }


    public function editTemplate($view, $request)
    {
        $id = $request->getParam("id");
        $service = $this->service();

        return $view->render("editor.html.twig", [
            "template" => $service->getTemplate($id)
        ]);
    }

    public function saveTemplate(Request $request)
    {

        $service = $this->service();
        $BODY    = $request->getRequestBody();

        if (!isset($BODY['name']) || empty($BODY['name'])) { {
                return [
                    "code" => 400,
                    "message" => "Name is required"
                ];
            }
        }

        $id = uniqid();
        $name = $BODY['name'];
        $descript = $BODY['description'];
        $htmldata = $BODY['htmldata'];
        $cssdata  = $BODY['cssdata'];


        if (isset($BODY['id'])) {
            return $this->updateTemplate([
                "id" => $BODY['id'],
                "name" => $name,
                "description" => $descript,
                "htmldata" => $htmldata,
                "cssdata" => $cssdata
            ]);
        }


        if ($service->saveTemplate($id, $name, $descript, $htmldata, $cssdata)) {

            return [
                "code" => 200,
                "message" => "Template created successfully",
                "data" => [
                    "name" => $name,
                    "description" => $descript,
                    "id" => $id,
                ]
            ];
        } else {
            return [
                "code" => 400,
                "message" => "Error saving template"
            ];
        }
    }


    function updateTemplate($opt = [
        "id" => "",
        "name" => "",
        "description" => "",
        "htmldata" => "",
        "cssdata" => ""
    ])
    {

        $service = $this->service();
        $update = $service->updateTemplate($opt['id'], $opt['name'], $opt['description'], $opt['htmldata'], $opt['cssdata']);

        if($update) {
            return [
                "code" => 200,
                "message" => "Template updated successfully",
                "data" => [
                    "name" => $opt['name'],
                    "description" => $opt['description'],
                    "id" => $opt['id'],
                ]
            ];
        } else {
            return [
                "code" => 400,
                "message" => "Error updating template"
            ];
        }
    }
}

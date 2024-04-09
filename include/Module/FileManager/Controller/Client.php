<?php
namespace MerapiPanel\Module\FileManager\Controller;

use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Router;

class Client extends __Fragment
{

    protected $module;

    function onCreate(Box\Module\Entity\Module $module) {
        $this->module = $module;
    }

    public function register()
    {
        $index = Router::GET("/filemanager", "index", self::class);

        Box::module("Panel")->addMenu([
            "order" => 2,
            "name" => "File Manager",
            "link" => $index->getPath(),
            "icon" => "fa-regular fa-folder"
        ]);
    }

    public function index($req)
    {
        if($req->open) {
            return $this->open($req);
        }
        return View::render("index.html.twig");
    }



    public function open($req)
    {
        return View::render("open.html.twig", [
            "path" => $req->path
        ]);
    }
}
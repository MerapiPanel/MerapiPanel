<?php

namespace MerapiPanel\Module\Articel\Controller;

use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    protected $box;

    public function setBox(Box $box)
    {

        $this->box = $box;
    }




    function register($router)
    {

        $this->getBox()->getEvent()->addListener("module:editor:loadcomponent", [$this, "initMeta"]);


        // Box::module("panel");

        $panel = $this->box->Module_Panel();

        $index = $router->get("/content", "index", self::class);
        $list  = $router->get("/content/list", "list", self::class);
        $new   = $router->get("/content/create", "createNewContent", self::class);
        $config = $router->get("/content/config", "config", self::class);

        $panel->addMenu([
            "name" => "Content",
            "icon" => "fa-solid fa-newspaper",
            "link" => $index
        ]);

        $panel->addMenu([
            "parent" => "content",
            "name" => "List of content",
            "icon" => "fa-solid fa-bars-staggered",
            "link" => $list
        ]);
        $panel->addMenu([
            "name" => "Create new content",
            "parent" => "content",
            "icon" => "fa fa-plus",
            "link" => $new
        ]);
        $panel->addMenu([
            "name" => "Content config",
            "parent" => "settings",
            "icon" => '<svg viewBox="0 0 536.41 506.22" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="m192.41 0c-39.8 0-72 32.2-72 72v137.27c.10467-.00019.20779-.006.3125-.006 9.5714 0 18.945.79233 28.055 2.3106 6.0729.99014 10.693 5.7431 12.014 11.75l7.6191 34.801v-186.12c0-13.3 10.7-24 24-24h272c13.3 0 24 10.7 24 24v304c0 13.3-10.7 24-24 24h-212.66l23.105 21.023c4.5547 4.0926 6.3369 10.497 4.2246 16.24-1.3497 3.6503-2.8417 7.2221-4.4414 10.736h189.77c39.8 0 72-32.2 72-72v-304c0-39.8-32.2-72-72-72zm-144 56c-13.3 0-24 10.7-24 24v191.83l18.949 6.0234c8.8453-6.799 18.615-12.477 29.045-16.768l.0059-.0273v-181.05c0-13.3-10.7-24-24-24zm176 24c-13.3 0-24 10.7-24 24v80c0 13.3 10.7 24 24 24h96c13.3 0 24-10.7 24-24v-80c0-13.3-10.7-24-24-24zm176 0c-13.3 0-24 10.7-24 24s10.7 24 24 24h32c13.3 0 24-10.7 24-24s-10.7-24-24-24zm0 80c-13.3 0-24 10.7-24 24s10.7 24 24 24h32c13.3 0 24-10.7 24-24s-10.7-24-24-24zm-176 80c-13.3 0-24 10.7-24 24 0 4.3988 1.2568 8.4572 3.3047 11.994l31.139-9.8945c5.8088-1.8483 12.279-.1984 16.174 4.4883 4.5546 5.5104 8.7506 11.327 12.594 17.412h168.79c13.3 0 24-10.7 24-24s-10.7-24-24-24zm54.887 80c1.6953 5.541-.0845 11.561-4.4394 15.475l-28.584 26.008c.28479 2.1489.47487 4.3318.6543 6.5176h185.48c13.3 0 24-10.7 24-24s-10.7-24-24-24z"/><path d="m240.46 333.62c1.5973 4.3426.24958 9.1844-3.1946 12.279l-21.613 19.667c.54906 4.143.84856 8.3857.84856 12.678s-.2995 8.5355-.84856 12.678l21.613 19.667c3.4442 3.0947 4.7919 7.9365 3.1946 12.279-2.1963 5.9399-4.8418 11.63-7.8866 17.121l-2.346 4.0431c-3.2944 5.4907-6.9881 10.682-11.031 15.574-2.945 3.5939-7.8367 4.7918-12.229 3.3942l-27.803-8.835c-6.6886 5.1413-14.076 9.434-21.963 12.678l-6.2394 28.502c-.9983 4.5423-4.4924 8.1362-9.0846 8.8849-6.8883 1.148-13.976 1.747-21.214 1.747-7.2377 0-14.326-.59899-21.214-1.747-4.5922-.74873-8.0862-4.3426-9.0846-8.8849l-6.2394-28.502c-7.8866-3.2445-15.274-7.5372-21.963-12.678l-27.753 8.8849c-4.3925 1.3976-9.2842.14974-12.229-3.3942-4.0431-4.8917-7.7368-10.083-11.031-15.574l-2.346-4.0431c-3.0448-5.4906-5.6903-11.181-7.8866-17.121-1.5973-4.3426-.24957-9.1844 3.1946-12.279l21.613-19.667c-.54906-4.1929-.84856-8.4357-.84856-12.728s.29949-8.5355.84856-12.678l-21.613-19.667c-3.4441-3.0947-4.7918-7.9365-3.1946-12.279 2.1963-5.9399 4.8418-11.63 7.8866-17.121l2.346-4.0431c3.2944-5.4906 6.9881-10.682 11.031-15.574 2.945-3.5939 7.8367-4.7919 12.229-3.3942l27.803 8.835c6.6886-5.1412 14.076-9.434 21.963-12.678l6.2394-28.502c.99829-4.5423 4.4924-8.1362 9.0846-8.8849 6.8883-1.198 13.976-1.797 21.214-1.797s14.326.599 21.214 1.747c4.5922.74872 8.0863 4.3426 9.0846 8.8849l6.2394 28.502c7.8866 3.2445 15.274 7.5372 21.963 12.678l27.803-8.835c4.3925-1.3976 9.2842-.14975 12.229 3.3942 4.0431 4.8917 7.7369 10.083 11.031 15.574l2.346 4.0431c3.0448 5.4907 5.6903 11.181 7.8866 17.121zm-119.75 84.556a39.932 39.932 0 100-79.864 39.932 39.932 0 100 79.864z"/></svg>',
            "link" => $config
        ]);

    }


    function config(Request $req)
    {

        return View::render("config.html.twig");
    }

    function initMeta($reflection)
    {

        $reflection[] = [
            "id"      => "content_listCurrent",
            "label"   => "Content list",
            "content" => "<ul><li>Content 1</li><li>Content 2</li></ul>",
            "media"   => "<i style='padding: 1.2rem; font-size: 2rem;' class='fa-solid fa-bars-staggered'></i>",
            "traits"  => [
                [
                    "type"  => "number",
                    "label" => "Max length",
                ]
            ],
        ];

        return $reflection;
    }

    function index($view)
    {
        return View::render("index.html.twig");
    }

    function createNewContent($view)
    {
        return View::render("editor.html.twig");
    }

    function list($view)
    {
        return View::render("list.html.twig");
    }
}

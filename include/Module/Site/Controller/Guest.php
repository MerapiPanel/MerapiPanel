<?php

namespace MerapiPanel\Module\Site\Controller;

use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Views\View;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Router;

class Guest extends __Fragment
{

    function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {

    }


    public function register()
    {

        Router::GET("/", "index", self::class);
        Router::GET("/", "index", self::class);
        Router::GET("/{lang[id,en,cn,jp,es,ru]}", "index", self::class);
        Router::GET("/about", "about", self::class);
        Router::GET("/{lang[id,en,cn,jp,es,ru]}/about", "about", self::class);
        Router::GET("/contact-us", "contactUs", self::class);
        Router::GET("/{lang[id,en,cn,jp,es,ru]}/contact-us", "contactUs", self::class);
    }


    public function index($request)
    {
        $lang = $request->lang();
        // throw new \Exception("Hello World");

       

        return View::render("index.html.twig", [
            "lang" => $lang
        ], $lang);
    }


    public function about($request)
    {
        $lang = $request->lang();

        return View::render("about.html.twig", [
            "lang" => $lang
        ], $lang);
    }


    function contactUs($request)
    {

        $referer = $request->http("referer");
        if (empty($referer)) {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        $link = "https://wa.me/6287742222966?text=" . rawurlencode("Hi, I'm interested in your business. I'm from " . $referer);
        header("Location: " . $link);

        $response = new Response("Redirecting to " . $link);
        $response->setHeader("Location", $link);

        return $response;
    }
}

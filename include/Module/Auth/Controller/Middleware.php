<?php
namespace MerapiPanel\Module\Auth\Controller;

use Closure;
use MerapiPanel\Box\Module\__Middleware;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Views\View;

class Middleware extends __Middleware
{

    function handle(Request $request, Closure $next)
    {
        
        if($this->module->isAdmin()) {
            return $next($request);
        }


        
        if($request->http("x-requested-with") == "XMLHttpRequest") {
            return new Response([
                "code" => 401,
                "message" => "Unauthorized"
            ], 401);
        }


        $config = $this->module->getConfig();
        $arrayConfig = $config->__toArray();
        unset($arrayConfig["cookie_name"], $arrayConfig['google_auth.client_secret']);

        return new Response(View::render("login.html.twig", [
            "login_endpoint" => "/auth/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "login_api_endpoint" => "/auth/api/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "config" => $arrayConfig
        ]), 401);
    }
}
<?php
namespace MerapiPanel\Module\Auth\Controller;

use Closure;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\__Middleware;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Views\View;
use PDO;

class Middleware extends __Middleware
{

    function handle(Request $request, Closure $next)
    {
        $config      = $this->module->getConfig();
        $cookie_name = $config->get("cookie_name");

        if($session = $this->module->getSession()) {
            
            if(strtotime($session['expire']) > time()) {
                return $next($request);
            }
        }


        //setcookie($cookie_name, "", time() - 3600, "/");
        $arrayConfig = $config->__toArray();
        unset($arrayConfig["cookie_name"], $arrayConfig['google_auth.client_secret']);

        return new Response(View::render("login.html.twig", [
            "login_endpoint" => "/auth/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "login_api_endpoint" => "/auth/api/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "config" => $arrayConfig
        ]), 401);
    }
}
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

        $config = $this->module->getConfig();
        $cookieName  = $config->get('cookie_name');
        $s_token     = $_COOKIE[$cookieName] ?? null;

        if ($s_token && $token = AES::decrypt($s_token)) {
            $query = DB::table("session_token")->select("*")->where("token")->equals($token)->execute();
            
            if ($query->rowCount() > 0) {

                Box::module()->__on("*", function ($a, &$b) {
                   
                });

                $session = $query->fetch(PDO::FETCH_ASSOC);
                if (strtotime($session["expires"]) > time()) {
                    return $next($request);
                }
            }
        }

        return new Response(View::render("login.html.twig", [
            "login_endpoint" => "/auth/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "login_api_endpoint" => "/auth/api/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "setting" => $config->__toArray()
        ]), 401);
    }
}
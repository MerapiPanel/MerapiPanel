<?php
namespace MerapiPanel\Module\Auth\Controller;

use Closure;
use MerapiPanel\Box\Module\__Middleware;
use MerapiPanel\Database\DB;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Views\View;
use PDO;

class Middleware extends __Middleware
{

    function handle(Request $request, Closure $next)
    {

        $setting = $this->module->getSetting();
        $cookieName = $setting->cookie_name;

        $s_token = $_COOKIE[$cookieName] ?? null;

        if ($s_token) {
            $query = DB::table("session_token")->select("*")->where("token")->equals($s_token)->execute();
            if ($query->rowCount() > 0) {

                $session = $query->fetch(PDO::FETCH_ASSOC);
                if (strtotime($session["expires"]) > time()) {
                    return $next($request);
                }
            }
            unset($_COOKIE[$cookieName]);
        }

        return new Response(View::render("login.html.twig", [
            "login_endpoint" => "/auth/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "login_api_endpoint" => "/auth/api/" . ltrim($_ENV["__MP_ADMIN__"]['prefix'], "/"),
            "setting" => $setting
        ]), 401);
    }
}
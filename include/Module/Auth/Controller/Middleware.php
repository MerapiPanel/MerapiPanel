<?php

namespace MerapiPanel\Module\Auth\Controller;

use Closure;
use MerapiPanel\Box\Module\__Middleware;
use MerapiPanel\Utility\AES;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;

class Middleware extends __Middleware
{

    function handle(Request $request, Closure $next)
    {

        if ($this->module->isAdmin()) {
            return $next($request);
        }


        if ($request->http("x-requested-with") == "XMLHttpRequest") {
            return new Response([
                "code" => 401,
                "message" => "Unauthorized"
            ], 401);
        }
        if (isset($_ENV['__MP_ADMIN__']['prefix'])) {
            return new Response("", 401, ['Location' => rtrim($_ENV['__MP_ADMIN__']['prefix'], '/') . "/login"]);
        }
        return new Response([
            "code" => 401,
            "message" => "Unauthorized"
        ], 401);
    }
}

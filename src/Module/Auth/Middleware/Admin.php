<?php

namespace MerapiPanel\Module\Auth\Middleware;

use MerapiPanel\Utility\Http\Request;
use Closure;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Middleware\Middleware;

class Admin implements Middleware
{

    public function handle(Request $request, Closure $next): Response
    {

        $authCookieData = $_COOKIE[''];
        // return Response::with("Session Expired")->redirect("/");

        // return new Response([
        //     "status" => 203,
        //     "message" => "Session Expired"
        // ], 203);

        return $next($request);
    }
}

<?php

namespace MerapiPanel\Module\Auth\Middleware;

use MerapiPanel\Utility\Http\Request;
use Closure;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\AES;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Middleware\Middleware;

class Admin extends Module implements Middleware
{

    public function handle(Request $request, Closure $next): Response
    {

        $setting =  $this->service()->getSetting();

        error_log("settings: ".json_encode($setting));

        $authCookieData = $_COOKIE[$setting["cookie"]['cookie_key']];

        if (!$authCookieData) {
            error_log("Auth cookie not found");
            return Response::with("Unauthorized")->redirect($setting['login_path']);
        }

        $data = json_decode(AES::decrypt($authCookieData), true);
        if (!$data || !isset($data["username"])) {

            error_log("Auth data not valid");
            return Response::with("Unauthorized")->redirect($setting['login_path']);
        }

        return $next($request);
    }
}

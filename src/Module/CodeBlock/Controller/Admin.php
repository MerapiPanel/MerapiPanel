<?php

namespace MerapiPanel\Module\CodeBlock\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Request;

class Admin extends Module
{

    function register($router)
    {

        $router->post('/codeblock/static-compile', 'sCompile', self::class);
    }



    function sCompile(Request $request)
    {

        $PARAMS = $request->getRequestBody();
        if (!isset($PARAMS['code-block'])) {

            return [
                "code" => 400,
                "message" => "Bad Request"
            ];
        }


        return [
            "code" => 200,
            "message" => "OK",
            "data" => []
        ];
    }
}

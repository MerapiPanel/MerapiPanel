<?php

namespace MerapiPanel\Module\CodeBlock\Controller;

use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Utility\Http\Request;

class Guest extends Module
{

    function register($router)
    {

        $url = $this->getBox()->Module_Panel()->adminLink("codeblock/static-compile");
        $router->post($url, 'sCompile', self::class);
    }



    function sCompile(Request $request)
    {

        $PARAMS = $request->getRequestBody();
        if (!isset($PARAMS['code'])) {

            return [
                "code" => 400,
                "message" => "Bad Request"
            ];
        }

        $code = ltrim($PARAMS['code'], "<?php");
        $code = rtrim($code, "?>");

        $output = null;
        ob_start();
        "use stretch";
        eval($code);
        $output = ob_get_contents();
        ob_end_clean();

        return [
            "code" => 200,
            "message" => "OK",
            "data" => [
                "code" => $code,
                "output" => $output
            ]
        ];
    }
}

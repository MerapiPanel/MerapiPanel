<?php
namespace MerapiPanel\Module\Api\Controller {
    
    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\__Controller;
    use MerapiPanel\Utility\Http\Request;

    class __Default extends __Controller
    {

        function register(){ }

        function apiCall(Request $request)
        {
            $moduleName = $request->module_name();
            $methodName = $request->method_name();

            try {

                ob_start();
                $module = Box::module($moduleName);
                $output = $module->$methodName();
        
                if (gettype($output) === "object" && method_exists($output, "__toArray")) {
                    $output = $output->__toArray();
                } else if (gettype($output) === "object" && method_exists($output, "__toJSON")) {
                    $output = $output->__toJSON();
                } else if (gettype($output) === "object" && method_exists($output, "__toString")) {
                    $output = $output->__toString();
                }
                ob_clean();


                return [
                    "code" => 200,
                    "message" => "Success",
                    "data" => $output
                ];

            } catch (\Throwable $e) {
                return [
                    "code" => 500,
                    "message" => $e->getMessage()
                ];
            }
        }
    }
}
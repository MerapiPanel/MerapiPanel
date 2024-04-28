<?php
namespace MerapiPanel\Module\Api\Controller {

    use MerapiPanel\Box;
    use MerapiPanel\Utility\Http\Request;
    use MerapiPanel\Utility\Router;

    class Admin extends __Default
    {

        function register()
        {
            Router::GET("/api/{module_name}/{method_name}", "apiCall", self::class);
            Router::GET("/api/{module_name}/{service_name}/{method_name}", "apiCallService", self::class);

            Router::POST("/api/{module_name}/{method_name}", "post_apiCall", self::class);
            Router::POST("/api/{module_name}/{service_name}/{method_name}", "post_apiCallService", self::class);

        }

        function post_apiCall(Request $request)
        {
            $moduleName = $request->module_name();
            $methodName = $request->method_name();

            try {

                ob_start();
                $module = Box::module($moduleName);
                if($module->Api) {
                    $module = $module->Api;
                }
                $params = $module->method_params($methodName);
                if (!empty($params)) {
                    foreach ($params as $key => $value) {
                        $paramName = $value->name;
                        if (!$value->isOptional()) {
                            $params[$key] = $request->$paramName();
                        } else {
                            if ($request->$paramName()) {
                                $params[$key] = $request->$paramName();
                            } else {
                                $params[$key] = $value->getDefaultValue();
                            }
                        }

                    }
                    $output = $module->$methodName(...array_values($params));
                } else {
                    $output = $module->$methodName();
                }

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

        function post_apiCallService(Request $request)
        {
            $moduleName = $request->module_name();
            $methodName = $request->method_name();
            $serviceName = $request->service_name();

            try {

                ob_start();
                $module = Box::module($moduleName)->$serviceName;
                $params = $module->method_params($methodName);
                if (!empty($params)) {
                    foreach ($params as $key => $value) {
                        $paramName = $value->name;
                        $params[$key] = $request->$paramName();
                    }
                    $output = $module->$methodName(...array_values($params));
                } else {
                    $output = $module->$methodName();
                }

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

            } catch (\Exception $e) {
                return [
                    "code" => 500,
                    "message" => $e->getMessage()
                ];
            }

        }

    }
}
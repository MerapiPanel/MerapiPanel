<?php
namespace MerapiPanel\Module\Ajax\Controller {

    use MerapiPanel\Box;
    use MerapiPanel\Box\Module\__Controller;
    use MerapiPanel\Box\Module\Entity\Proxy;
    use MerapiPanel\Utility\Http\Request;
    use MerapiPanel\Utility\Router;

    class Admin extends __Controller
    {

        function register()
        {
            Router::GET("/api/{module_name}/{method_name}", [$this, "apiCall"]);
            Router::GET("/api/{module_name}/{service_name}/{method_name}", [$this, "apiCallService"]);

            Router::POST("/api/{module_name}/{method_name}", [$this, "post_apiCall"]);
            Router::POST("/api/{module_name}/{service_name}/{method_name}", [$this, "post_apiCallService"]);

        }

        function apiCall(Request $request)
        {
            $moduleName = $request->module_name();
            $methodName = $request->method_name();

            try {

                ob_start();
                $module = Box::module($moduleName);
                if ($module->Ajax instanceof Proxy) {
                    $module = $module->Ajax;
                }

                $params = $module->__method_params($methodName);
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
        
        function post_apiCall(Request $request)
        {
            $moduleName = $request->module_name();
            $methodName = $request->method_name();

            try {

                ob_start();
                $module = Box::module($moduleName);
                if ($module->Ajax) {
                    $module = $module->Ajax;
                }
                $params = $module->__method_params($methodName);
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


        function apiCallService(Request $request)
        {

            $moduleName = $request->module_name();
            $methodName = $request->method_name();
            $serviceName = $request->service_name();

            try {

                ob_start();
                $module = Box::module($moduleName)->$serviceName;
                if (!$module) {
                    throw new \Exception("Service {$serviceName} not found");
                }
                $params = $module->__method_params($methodName);
                if (!empty($params)) {
                    foreach ($params as $key => $value) {
                        $paramName = $value->name;
                        $params[$key] = $request->$paramName;
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


        function post_apiCallService(Request $request)
        {
            $moduleName = $request->module_name();
            $methodName = $request->method_name();
            $serviceName = $request->service_name();

            try {

                ob_start();
                $module = Box::module($moduleName)->$serviceName;
                if (!$module) {
                    throw new \Exception("Service {$serviceName} not found");
                }
                $params = $module->__method_params($methodName);
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
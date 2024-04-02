<?php

namespace MerapiPanel\Utility;

use Exception;
use MerapiPanel\Exception\HTTP_CODE;
use MerapiPanel\Exception\HttpException;
use MerapiPanel\Box;
use MerapiPanel\Core\Abstract\Module;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Core\Views\View;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Route;

class Router
{

    protected $routeStack = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];
    protected Box $box;
    protected $access = [];
    private static Router $instance;

    private function __construct()
    {
    }

    public static function getInstance(): Router
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    private static function resolveCallback($method, $caller)
    {

        $file = $caller["file"];
        if (is_string($method)) {
            $moduleName = Module::getModuleName($file);
            $className = "\\MerapiPanel\\Module\\{$moduleName}\\Controller\\" . basename($file, ".php");
            if (class_exists($className)) {
                $callback = $className . "@" . $method;
            } else {
                $className = Util::getClassNameFromFile($file);
                if ($className && class_exists($className)) {
                    $callback = $className . "@" . $method;
                }
            }
            return $callback;
        }

        return null;
    }


    /**
     * Retrieves a route object for a GET request.
     *
     * @param string $path The path for the route.
     * @param string|callable $method The callback function for the route.
     * @param string $controller The callback function for the route.
     * @param bool $isAdmin Determines if the route is for an admin.
     * @return Route The route object.
     */
    public static function GET(string $path, string|callable $method, string $controller = null): Route
    {

        $instance = Router::getInstance();
        $callback = $method;

        if (!is_callable($method)) {


            $caller = debug_backtrace()[0];
            $callback = self::resolveCallback($method, $caller);
            if (!$callback) {
                throw new \InvalidArgumentException("Callback is invalid");
            }


            $access = $_ENV['__MP_ACCESS__'];
            $accessName = strtolower(basename($caller['file'] ?? "", ".php"));
            if (isset($access[$accessName])) {
                $path = $access[$accessName]["prefix"] . "/" . trim($path, "/");
            }
        }

        $route = new Route(Route::GET, $path, $callback);
        return $instance->addRoute(Route::GET, $route);
    }




    /**
     * Creates a new POST route and adds it to the route collection.
     *
     * @param string $path The path of the route.
     * @param string|callable $method The callback function for the route.
     * @param string $controller The controller function to be executed when the route is matched.
     * @param bool $isAdmin Whether the route is for admin only.
     * @return Route The created route.
     */
    public static function POST(string $path, string|callable $method, string $controller = null): Route
    {

        $instance = Router::getInstance();
        $callback = $method;


        if (!is_callable($method)) {


            $caller = debug_backtrace()[0];
            $callback = self::resolveCallback($method, $caller);
            if (!$callback) {
                throw new \InvalidArgumentException("Callback is invalid");
            }


            $access = $_ENV['__MP_ACCESS__'];
            $accessName = strtolower(basename($caller['file'] ?? "", ".php"));
            if (isset($access[$accessName])) {
                $path = $access[$accessName]["prefix"] . "/" . trim($path, "/");
            }
        }

        $route = new Route(Route::POST, $path, $callback);
        return $instance->addRoute(Route::POST, $route);
    }




    /**
     * Adds a PUT route to the router.
     *
     * @param string $path The path for the route.
     * @param string|callable $method The callback function for the route.
     * @param string $controller The controller function to be executed when the route is matched.
     * @param bool $isAdmin (optional) Whether the route is for admin use only. Defaults to false.
     * @return Route The added route.
     */
    public static function PUT(string $path, string|callable $method, string $controller = null): Route
    {

        $instance = Router::getInstance();
        $callback = $method;


        if (!is_callable($method)) {


            $caller = debug_backtrace()[0];
            $callback = self::resolveCallback($method, $caller);
            if (!$callback) {
                throw new \InvalidArgumentException("Callback is invalid");
            }


            $access = $_ENV['__MP_ACCESS__'];
            $accessName = strtolower(basename($caller['file'] ?? "", ".php"));
            if (isset($access[$accessName])) {
                $path = $access[$accessName]["prefix"] . "/" . trim($path, "/");
            }
        }

        $route = new Route(Route::PUT, $path, $callback);
        return $instance->addRoute(Route::PUT, $route);
    }



    /**
     * Deletes a route.
     *
     * @param string $path The path of the route.
     * @param string|callable $method The callback function for the route.
     * @param string $controller The controller function to handle the route.
     * @param bool $isAdmin (optional) Indicates if the route is for an admin. Defaults to false.
     * @return Route The created route.
     */
    public static function DELETE(string $path, string|callable $method, string $controller = null): Route
    {

        $instance = Router::getInstance();
        $callback = $method;


        if (!is_callable($method)) {


            $caller = debug_backtrace()[0];
            $callback = self::resolveCallback($method, $caller);
            if (!$callback) {
                throw new \InvalidArgumentException("Callback is invalid");
            }


            $access = $_ENV['__MP_ACCESS__'];
            $accessName = strtolower(basename($caller['file'] ?? "", ".php"));
            if (isset($access[$accessName])) {
                $path = $access[$accessName]["prefix"] . "/" . trim($path, "/");
            }
        }


        $route = new Route(Route::DELETE, $path, $callback);
        return $instance->addRoute(Route::DELETE, $route);
    }



    function resetRoute()
    {
        $this->routeStack = [
            "GET" => [],
            "POST" => [],
            "PUT" => [],
            "DELETE" => []
        ];
    }

    /**
     * Adds a route to the route stack.
     *
     * @param string $method The HTTP method of the route.
     * @param Route $route The route object to add.
     * @return Route The added route.
     */
    protected function addRoute($method, Route $route): Route
    {


        $this->routeStack[$method][] = $route;
        return $route;
    }




    /**
     * Dispatches the HTTP request to the appropriate route handler.
     *
     * @param Request $request the HTTP request object
     * @throws HttpException if the HTTP method is not supported or if the route is not found
     * @return Response the HTTP response object
     */
    public static function dispatch(Request $request): Response
    {


        $instance = Router::getInstance();
        $method = $request->getMethod();
        $path = $request->getPath();

        foreach ($instance->access as $key => $value) {

            if (strpos($path, $value["prefix"]) === 0) {
                $handler = $value["handler"];
                if (!Util::callAccessHandler($handler)) {
                    throw new Exception("Access Denied", 403);
                }
                $_ENV['__MP_ACCESS_NAME__'] = $key;
                break;
            }
        }


        $stack = $instance->routeStack[$method];


        // Use usort with a custom function to sort the routes in descending order
        // the logest route should be matched first
        usort($stack, function ($a, $b) {
            // Compare the lengths of the strings
            $lengthA = strlen($a);
            $lengthB = strlen($b);

            // If lengths are equal, return 0 (no change)
            if ($lengthA == $lengthB) {
                return 0;
            }

            // Return negative if $b is longer than $a, positive if $a is longer than $b
            // This is for descending order; reverse the operands for ascending order
            return ($lengthA < $lengthB) ? 1 : -1;
        });



        /**
         * @var Route $route
         * find route from route stack
         */
        foreach ($stack as $route) {
            if ($instance->matchRoute($route->getPath(), $path)) {
                $routeParams = $instance->extractRouteParams($route->getPath(), $path);
                $request->setParams($routeParams);
                return $instance->handle($request, $route);
            }
        }



        throw new HttpException("Route not found " . $request->getPath(), HTTP_CODE::NOT_FOUND);
    }




    /**
     * Matches a route against a given path and saves the route parameters.
     *
     * @param string $route The route pattern to match against.
     * @param string $path The path to match against the route pattern.
     * @return bool Returns true if the path matches the route pattern, false otherwise.
     */
    protected function matchRoute($route, $path)
    {

        if (strlen($path) > 1 && substr($path, -1) !== "/") {
            $path .= "/";
        }
        if (strlen($route) > 1 && substr($route, -1) !== "/") {
            $route .= "/";
        }


        $pattern = preg_replace('/\//', '\/', $route);

        preg_match("/\{(.*)\[(.*)\]\}/", $route, $matches);
        if (isset($matches[1], $matches[2])) {
            // parameter with expacted value
            $parameter = $matches[1];
            $expacted = $matches[2];
            $pattern = preg_replace("/\{$parameter\[$expacted\]\}/", "(" . implode("|", explode(",", $expacted)) . ")", $pattern);
        }
        $pattern = '/^' . preg_replace('/\{(.+?)\}/', '(.+?)', $pattern) . '$/';

        // Use preg_match to check if the path matches the pattern
        preg_match($pattern, $path, $matches);

        return !empty($matches);
    }




    /**
     * Extracts the route parameters from the given route and path.
     *
     * @param string $route The route to extract parameters from
     * @param string $path The path to match against the route
     * @return array The matched route parameters
     */
    protected function extractRouteParams($route, $path)
    {

        if (strlen($path) > 1 && substr($path, -1) !== "/")
            $path .= "/";
        if (strlen($route) > 1 && substr($route, -1) !== "/")
            $route .= "/";

        $pattern = preg_replace('/\//', '\/', $route);
        $pattern = '/^' . preg_replace('/\{(.*?)\}/', '(.*?)', $pattern) . '$/';

        // Use preg_match to check if the path matches the pattern
        preg_match($pattern, $path, $matches);
        // Remove the first element, as it contains the full match
        array_shift($matches);

        // Get the parameter names from the route
        preg_match_all('/\{(.*?)\}/im', $route, $paramNames);

        $paramNames = $paramNames[1];
        $paramNames = array_map(function ($name) {
            return preg_replace('/\[.*\]+/', '', $name);
        }, $paramNames);


        // Combine the parameter names with the matched values
        $routeParams = array_combine($paramNames, $matches);

        // Return the matched route parameters
        return $routeParams;
    }


    /**
     * Handles the request and returns the response.
     *
     * @param Request $request The request object.
     * @param Route $route The route object.
     * @return Response|null The response object or null if no response is returned.
     */
    protected function handle(Request $request, Route $route): Response
    {

        $callback = $route->getController();
        $middlewareStack = $route->getMiddlewareStack();

        $response = $middlewareStack->handle(
            $request,
            function (Request $request) use ($callback) {
                return $this->callCallback($request, $callback);
            }
        );

        if ($response instanceof Response) {

            return $response;
        }

        // Jika tidak ada middleware atau middleware telah selesai dieksekusi,
        // jalankan callback untuk mendapatkan responsnya.
        return $this->callCallback($request, $callback);
    }





    /**
     * Calls the provided callback function with the given request object.
     *
     * @param Request $request The request object.
     * @param mixed $callback The callback function to be called.
     * @throws Exception If the callback is a string and the controller or method is not found.
     * @return Response The response returned by the callback function or a new Response object.
     */
    protected function callCallback(Request $request, $callback): Response
    {

        // echo "call callback ". $callback;

        $response = new Response();
        if (is_callable($callback)) {

            $output = call_user_func($callback, ...[$request, &$response]);
            if ($output instanceof Response) {
                return $output;
            }

            if (is_array($output) || is_object($output) && isset($output['code'])) {
                $response->setStatusCode($output['code']);
            }
            $response->setContent($output);

            return $response;

        } else if (is_string($callback) && strpos($callback, '@') !== false) {

            list($controllerClassName, $method) = explode('@', $callback);
            preg_match('/\\\Module\\\(.+?)\\\Controller\\\(.+)/i', $controllerClassName, $matches);
            if (isset($matches[1], $matches[2])) {

                $module = Box::module(ucfirst($matches[1]));
                // error_log(print_r(Box::module(), true));
                // error_log(print_r([$matches[1], $matches[2]], true));
                if (!$module) {
                    throw new Exception("Module {{$matches[1]}} not found ");
                }
                $cName = $matches[2];
                $controller = $module->Controller->$cName;
                if (!$controller) {
                    throw new Exception("Controller {{$controller}} not found ");
                }

            } else if (class_exists($controllerClassName)) {
                $reflector = new \ReflectionClass($controllerClassName);
                $controller = $reflector->newInstanceWithoutConstructor();
            }

            if (!$controller) {
                throw new Exception("Controller not found ");
            }

            // execute controller method
            ob_start();
            $output = $controller->$method(...[$request, &$response]);
            ob_end_flush();


            if ($output instanceof Response) {
                error_clear_last(); // clear last error
                $output->send();
                return $output;
            }


            if ($request->getMethod() === Route::GET) {

                if (is_string($output)) {

                    error_clear_last(); // clear last error
                    $response->setContent($output);
                    return $response;
                }
                // event method get return an array
                elseif (is_array($output)) {
                    error_clear_last(); // clear last error
                    return $this->handleApiResponse($output);
                }

                throw new Exception("Unxpected response type, Controller: $controller Method: $method should return View, string or array but " . gettype($output) . " returned", 400);

            }

            // event method not get return an object or array or string
            return $this->handleApiResponse($output);


        } else {

            return new Response('Internal server error', 500);
        }
    }



    public function handleApiResponse($result = [])
    {

        $response = new Response();
        $response->setHeader('Content-Type', 'application/json');

        $content = ["code" => 304, "message" => "No change", "data" => null];

        if (is_array($result)) {

            if (isset($result["code"])) {
                $content["code"] = $result["code"];
            }
            if (isset($result["message"])) {
                $content["message"] = $result["message"];
            }
            if (isset($result["data"])) {
                $content["data"] = $result["data"];
            }
        } elseif (is_string($result)) {

            $json = json_decode($result, true);

            if (json_last_error() === JSON_ERROR_NONE) {

                if (isset($json["code"])) {
                    $content["code"] = $json["code"];
                }
                if (isset($json["message"])) {
                    $content["message"] = $json["message"];
                }
                if (isset($json["data"])) {
                    $content["data"] = $json["data"];
                }
            } else {

                $content["code"] = 200;
                $content["message"] = $result;
                $content["data"] = [];
            }
        }

        if (isset($content['data']) && $content['data'] == null) {
            // unset($content['data']);
        }

        $response->setContent($content);
        $response->setStatusCode($content['code']);
        return $response;
    }
}

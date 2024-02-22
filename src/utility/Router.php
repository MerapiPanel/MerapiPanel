<?php

namespace MerapiPanel\Utility;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Core\Cog\Config;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Core\View\View;
use MerapiPanel\Utility\Middleware\Component;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Exceptions\Error;
use MerapiPanel\Utility\Route;

class Router extends Component
{

    protected $routeStack = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];
    protected Box $box;
    protected $adminPrefix = '/panel/admin';
    protected Config $config;
    protected $controller = [
        "class" => "",
        "method" => ""
    ];


    public function setBox(Box $box)
    {

        parent::__construct();
        $this->box = $box;
        $this->config = $this->box->getConfig();

        if (isset($this->config['admin'])) {

            $this->adminPrefix = $this->config['admin'];
        }
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
    public function get(string $path, string|callable $method, string $controller = null): Route
    {

        $callback = false;
        if (is_string($method) && $controller !== null) {
            $callback = $controller . "@$method";
        } else if (is_callable($method)) {
            $callback = $method;
        }
        if ($callback === false) {
            throw new \InvalidArgumentException("callback is invalid");
        }

        $sectionName = strtolower(basename($this->getCaller() ?? "", ".php"));
        if ($sectionName === "admin") {
            $path = rtrim($this->adminPrefix, "/") . "/" . trim($path, "/");
        }

        $route = new Route(Route::GET, $path, $callback);
        return $this->addRoute(Route::GET, $route);
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
    public function post(string $path, string|callable $method, string $controller = null): Route
    {

        $callback = false;

        if (is_string($method) && $controller !== null) {
            $callback = $controller . "@$method";
        } else if (is_callable($method)) {
            $callback = $method;
        }
        if ($callback === false) {
            throw new \InvalidArgumentException("callback is invalid");
        }


        $sectionName = strtolower(basename($this->getCaller() ?? "", ".php"));
        if ($sectionName === "admin") {
            $path = rtrim($this->adminPrefix, "/") . "/" . trim($path, "/");
        }

        $route = new Route(Route::POST, $path, $callback);
        return $this->addRoute(Route::POST, $route);
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
    public function put(string $path, string|callable $method, string $controller = null): Route
    {

        $callback = false;
        if (is_string($method) && $controller !== null) {
            $callback = $controller . "@$method";
        } else if (is_callable($method)) {
            $callback = $method;
        }
        if ($callback === false) {
            throw new \InvalidArgumentException("callback is invalid");
        }

        $sectionName = strtolower(basename($this->getCaller() ?? "", ".php"));
        if ($sectionName === "admin") {
            $path = rtrim($this->adminPrefix, "/") . "/" . trim($path, "/");
        }

        $route = new Route(Route::PUT, $path, $callback);
        return $this->addRoute(Route::PUT, $route);
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
    public function delete(string $path, string|callable $method, string $controller = null): Route
    {

        $callback = false;
        if (is_string($method) && $controller !== null) {
            $callback = $controller . "@$method";
        } else if (is_callable($method)) {
            $callback = $method;
        }
        if ($callback === false) {
            throw new \InvalidArgumentException("callback is invalid");
        }

        $sectionName = strtolower(basename($this->getCaller() ?? "", ".php"));
        if ($sectionName === "admin") {
            $path = rtrim($this->adminPrefix, "/") . "/" . trim($path, "/");
        }

        $route = new Route(Route::DELETE, $path, $callback);
        return $this->addRoute(Route::DELETE, $route);
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



    private function getCaller()
    {

        $file = false;

        foreach (debug_backtrace() as $call) {
            // only in module
            if (isset($call['file']) && in_array("module", array_map("strtolower", explode((PHP_OS == "WINNT" ? "\\" : "/"), $call['file'])))) {
                $file = $call['file'];
                break;
            }
        }

        // error_log(self::class . " File: " . $file);
        return $file;
    }




    /**
     * Dispatches the HTTP request to the appropriate route handler.
     *
     * @param Request $request the HTTP request object
     * @throws Error if the HTTP method is not supported or if the route is not found
     * @return Response the HTTP response object
     */
    public function dispatch(Request $request): Response
    {

        $method = $request->getMethod();

        $path = $request->getPath();

        // if (!isset($this->routeStack[$method])) {
        //     throw new Exception("Unsupported HTTP method: $method", 405);
        // } else if (strtoupper($method) !== "GET" && $path !== "/") {

        //     $token = urldecode($request->mToken());

        //     if (empty($token)) {

        //         throw new Exception("Request token is empty", 400);
        //     } elseif (!Token::validate($token)) {

        //         throw new Exception("Request token is invalid", 400);
        //     }
        // }

        //  echo "dispatch $method $path";

        /**
         * @var Route $route
         * find route from route stack
         */
        foreach ($this->routeStack[$method] as $route) {

            //  print_r([$route->getPath(), $path]);

            if ($this->matchRoute($route->getPath(), $path)) {

                $routeParams = $this->extractRouteParams($route->getPath(), $path);

                $request->setParams($routeParams);

                return $this->handle($request, $route);
            }
        }



        throw new Exception("Route not found $path", 404);
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

        if (strlen($path) > 1 && substr($path, -1) !== "/")
            $path .= "/";
        if (strlen($route) > 1 && substr($route, -1) !== "/")
            $route .= "/";

        $pattern = preg_replace('/\//', '\/', $route);
        $pattern = '/^' . preg_replace('/\{(.+?)\}/', '(.+?)', $pattern) . '$/';

        // Use preg_match to check if the path matches the pattern
        preg_match($pattern, $path, $matches);

        // Save the matches as route parameters
        foreach ($matches as $key => $value) {

            if (is_string($key)) {

                $this->routeStack[$key] = $value;
            }
        }

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
        preg_match_all('/\{([a-zA-Z0-9_\/.]+)\}/', $route, $paramNames);
        $paramNames = $paramNames[1];

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

        // print_r($this->routeStack);

        // Jika tidak ada middleware atau middleware telah selesai dieksekusi,
        // jalankan callback untuk mendapatkan responsnya.
        return $this->callCallback($request, $callback);
    }





    /**
     * Calls the provided callback function with the given request object.
     *
     * @param Request $request The request object.
     * @param mixed $callback The callback function to be called.
     * @throws Error If the callback is a string and the controller or method is not found.
     * @return Response The response returned by the callback function or a new Response object.
     */
    protected function callCallback(Request $request, $callback): Response
    {

        // echo "call callback ". $callback;

        $response = new Response();
        if (is_callable($callback)) {

            $response->setContent(call_user_func($callback, ...[$request, &$response]));
            return $response;

        } else if (is_string($callback) && strpos($callback, '@') !== false) {

            list($controller, $method) = explode('@', $callback);

            if (!class_exists($controller)) {
                $controllerClass = 'Mp\\Controllers\\' . $controller;

                if (!class_exists($controllerClass)) {

                    $response->setStatusCode(404);
                    throw new Exception("Controller not found: $controllerClass", 404);
                }
            } else {
                $controllerClass = $controller;
            }

            $this->controller['class'] = $controllerClass;
            $this->controller['method'] = $method;

            /// echo $controllerClass;

            /**
             * @var $controllerInstance
             * - init controller from class address
             */
            $controllerInstance = $this->box->$controllerClass();

            // execute controller method
            ob_start();
            $output = $controllerInstance->$method(...[$request, &$response]);
            ob_end_clean();


            if ($output instanceof Response) {
                return $output;
            }



            if ($request->getMethod() === Route::GET) {

                if ($output instanceof View) {

                    $response->setContent("$output");
                    return $response;
                }
                // event method get return a string 
                elseif (is_string($output)) {

                    $response->setContent($output);
                    return $response;
                }
                // event method get return an array
                elseif (is_array($output)) {
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

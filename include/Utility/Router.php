<?php

namespace MerapiPanel\Utility;

use Exception;
use MerapiPanel\App;
use MerapiPanel\Exception\HTTP_CODE;
use MerapiPanel\Exception\HttpException;
use MerapiPanel\Box;
use MerapiPanel\Box\Module\Entity\Proxy;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Middlewares;
use MerapiPanel\Utility\Route;

class Router
{

    protected Route|null $route = null;
    protected $routeStack = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

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


    private static function resolvePath($path, $caller)
    {

        $file = $caller["file"];
        $basename = strtoupper(basename($file, ".php"));
        $middleware = null;
        if (isset($_ENV['__MP_' . $basename . '__'])) {
            $config = $_ENV['__MP_' . $basename . '__'];
            if (isset($config['prefix'])) {
                $prefix = $config['prefix'];
                $path = ltrim($prefix, '/') . "/" . ltrim($path, "/");
            }

            if (isset($config['middleware'])) {
                $middleware = $config['middleware'];
            }
        }

        if (isset($path[0]) && $path[0] != "/") {
            $path = "/" . $path;
        }


        return [$path, $middleware];
    }



    /**
     * Retrieves a route object for a GET request.
     *
     * @param string $path The path for the route.
     * @param string|callable|array $method The callback function for the route.
     * @return Route The route object.
     */
    public static function GET(string $path, string|callable|array $method): Route
    {

        $instance = Router::getInstance();
        $middleware = null;
        $caller = debug_backtrace()[0];

        if (is_array($method) && count($method) === 2) {
            [$controller, $method] = $method;
            $callback = [$controller, $method];
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if (is_string($method) && str_contains($method, "@")) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if ($method instanceof \Closure) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else {
            throw new \InvalidArgumentException("Invalid route definition");
        }

        $route = new Route(Route::GET, $path, $callback, $middleware);
        return $instance->addRoute(Route::GET, $route);
    }




    /**
     * Creates a new POST route and adds it to the route collection.
     *
     * @param string $path The path of the route.
     * @param string|callable|array $method The callback function for the route.
     * @return Route The created route.
     */
    public static function POST(string $path, string|callable|array $method): Route
    {

        $instance = Router::getInstance();
        $middleware = null;
        $caller = debug_backtrace()[0];

        if (is_array($method) && count($method) === 2) {
            [$controller, $method] = $method;
            $callback = [$controller, $method];
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if (is_string($method) && str_contains($method, "@")) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if ($method instanceof \Closure) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else {
            throw new \InvalidArgumentException("Invalid route definition");
        }


        $route = new Route(Route::POST, $path, $callback, $middleware);
        return $instance->addRoute(Route::POST, $route);
    }




    /**
     * Adds a PUT route to the router.
     *
     * @param string $path The path for the route.
     * @param string|callable $method The callback function for the route.
     * @return Route The added route.
     */
    public static function PUT(string $path, string|callable $method): Route
    {

        $instance = Router::getInstance();
        $middleware = null;
        $caller = debug_backtrace()[0];

        if (is_array($method) && count($method) === 2) {
            [$controller, $method] = $method;
            $callback = [$controller, $method];
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if (is_string($method) && str_contains($method, "@")) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if ($method instanceof \Closure) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else {
            throw new \InvalidArgumentException("Invalid route definition");
        }

        $route = new Route(Route::PUT, $path, $callback, $middleware);
        return $instance->addRoute(Route::PUT, $route);
    }



    /**
     * Deletes a route.
     *
     * @param string $path The path of the route.
     * @param string|callable $method The callback function for the route.
     * @return Route The created route.
     */
    public static function DELETE(string $path, string|callable|array $method): Route
    {

        $instance = Router::getInstance();
        $middleware = null;
        $caller = debug_backtrace()[0];

        if (is_array($method) && count($method) === 2) {
            [$controller, $method] = $method;
            $callback = [$controller, $method];
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if (is_string($method) && str_contains($method, "@")) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else if ($method instanceof \Closure) {
            $callback = $method;
            [$path, $middleware] = self::resolvePath($path, $caller);
        } else {
            throw new \InvalidArgumentException("Invalid route definition");
        }

        $route = new Route(Route::DELETE, $path, $callback, $middleware);
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

    public function getRouteStack()
    {
        return $this->routeStack;
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
                $instance->route = $route;
                $routeParams = $instance->extractRouteParams($instance->route->getPath(), $path);
                $request->setParams($routeParams);
                return $instance->handle($request);
            }
        }

        if (App::$isApi) {
            throw new Exception("Invalid path", 404);
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

        $path = preg_replace('/\?.*/', '', $path);
        if ($route == $path) {
            return true;
        }
        $params = $this->extractRouteParams($route, $path);
        if (!empty($params)) {
            preg_match('/\{(\w+)\[(.*)\]\}/', $route, $matchesExpectedValue);
            if (isset($matchesExpectedValue[1], $matchesExpectedValue[2])) {
                $expected_value = explode(',', $matchesExpectedValue[2]);
                if (!isset($params[$matchesExpectedValue[1]])) {
                    return false;
                }
                if (!in_array($params[$matchesExpectedValue[1]], $expected_value)) {
                    return false;
                }
            }

            $route = preg_replace('/\{(\w+)\[(.*)\]\}/', '{$1}', $route);
            $expacted_path = str_replace(array_map(function ($key) {
                return '{' . $key . '}';
            }, array_keys($params)), array_values($params), $route);
            $expacted = preg_replace('/\/$/', '', $expacted_path) . '/';
            $path = preg_replace('/\/$/', '', $path) . '/';

            return $expacted == $path;
        }
        return false;
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
        $path = preg_replace('/\?.*/', '', $path);
        $pattern = preg_replace('/\//', '\/', $route);
        $pattern = preg_replace('/\{(\w+|\w+\[.*\])\}$/', '(.*)', $pattern);
        $pattern = '/^' . preg_replace('/\{(.*?)\}/', '(.*?)', $pattern) . '/';

        // Use preg_match to check if the path matches the pattern
        preg_match($pattern, preg_replace('/\/$/', '', $path) . "/", $matches);
        // Remove the first element, as it contains the full match
        array_shift($matches);
        if (!empty($matches) && !empty($matches[count($matches) - 1])) {
            $matches[count($matches) - 1] = preg_replace('/\/$/', '', $matches[count($matches) - 1]);
        }
        // Get the parameter names from the route
        preg_match_all('/\{(.*?)\}/im', $route, $paramNames);
        $paramNames = $paramNames[1];
        $paramNames = array_map(function ($name) {
            return preg_replace('/\[.*\]+/', '', $name);
        }, $paramNames);
        // Combine the parameter names with the matched values
        $routeParams = array_combine($paramNames, empty($matches) ? array_fill(0, count($paramNames), null) : $matches);
        // Return the matched route parameters
        return array_filter($routeParams);
    }




    public function getRoute(): Route|null
    {
        return $this->route;
    }




    /**
     * Handles the request and returns the response.
     *
     * @param Request $request The request object.
     * @param Route $route The route object.
     * @return Response|null The response object or null if no response is returned.
     */
    protected function handle(Request $request): Response
    {

        $route = $this->getRoute();
        $callback = $route->getController();
        // Get the middlewares for this route.
        $middlewares = new Middlewares($route->getMiddlewares() ?? []);

        $response = $middlewares->handle(
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
        $response = new Response();

        if (is_callable($callback)) {
            return $this->handleCallableCallback($callback, $request, $response);
        } elseif (is_string($callback) && strpos($callback, '@') !== false) {
            return $this->handleStringCallback($callback, $request, $response);
        } else {
            return $this->handleInvalidCallback();
        }
    }




    private function handleCallableCallback($callback, Request $request, Response $response): Response
    {
        if (is_array($callback) && count($callback) == 2 && $callback[0] instanceof Proxy) {
            [$instance, $method] = $callback;
            $output = $this->outputFromService($instance, $method);
        } else if (is_callable($callback)) {
            $output = call_user_func($callback, ...[$request, &$response]);
        }

        if ($output instanceof Response) {
            return $output;
        }

        if ($request->http('x-requested-with') == 'XMLHttpRequest' || $request->http('sec-fetch-mode') == 'cors') {
            $response->setContent([
                'status' => $response->getStatusCode() === 200,
                'message' => "Success",
                'data' => $output
            ]);
        } else {
            $response->setContent($output);
        }

        return $response;
    }



    private function handleStringCallback(string $callback, Request $request, Response $response): Response
    {
        list($controllerClassName, $method) = explode('@', $callback);
        $controller = $this->getControllerInstance($controllerClassName);
        if (!$controller) {
            throw new Exception("Controller not found: {$controllerClassName}");
        }

        $output = $controller->$method(...[$request, &$response]);

        if ($output instanceof Response) {
            return $output;
        }

        if ($request->http('x-requested-with') == 'XMLHttpRequest'|| $request->http('sec-fetch-mode') == 'cors') {
            $response->setContent([
                'status' => $response->getStatusCode() == 200,
                'message' => "Success",
                'data' => $output
            ]);
            $response->setHeader('Content-Type', 'application/json');
        } else {
            $response->setContent($output);
        }

        return $response;
    }

    private function handleInvalidCallback(): Response
    {
        return new Response([
            'message' => 'Controller not found',
            'status' => false
        ], 500);
    }

    private function getControllerInstance(string $controllerClassName)
    {
        preg_match('/\\\Module\\\(.+?)\\\Controller\\\(.+)/i', $controllerClassName, $matches);
        if (isset($matches[1], $matches[2])) {
            $module = Box::module(ucfirst($matches[1]));
            if (!$module) {
                throw new Exception("Module not found: {$matches[1]}");
            }
            $controllerName = $matches[2];
            return $module->Controller->$controllerName ?? null;
        } elseif (class_exists($controllerClassName)) {
            $reflector = new \ReflectionClass($controllerClassName);
            return $reflector->newInstanceWithoutConstructor();
        }

        return null;
    }

    private function outputFromService($module, $function)
    {
        $request = Request::getInstance();
        $params = $module->__method_params($function);

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $paramName = $value->name;
                if (strtoupper($request->getMethod()) == "GET") {
                    if (!$value->isOptional()) {
                        $params[$key] = $request->$paramName ?? null;
                    } else {
                        if ($request->$paramName()) {
                            $params[$key] = $request->$paramName ?? null;
                        } else {
                            $params[$key] = $value->getDefaultValue();
                        }
                    }
                } else {
                    if (!$value->isOptional()) {
                        $params[$key] = $request->$paramName() ?? null;
                    } else {
                        if ($request->$paramName()) {
                            $params[$key] = $request->$paramName() ?? null;
                        } else {
                            $params[$key] = $value->getDefaultValue();
                        }
                    }
                }
            }

            return $module->$function(...array_values($params));
        }

        return $module->$function();
    }
}

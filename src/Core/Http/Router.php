<?php

namespace il4mb\Mpanel\Core\Http;

use Exception;
use il4mb\Mpanel\Core\Box;
use il4mb\Mpanel\Exceptions\Error;

class Router
{

    protected $routeStack = [
        "GET"    => [],
        "POST"   => [],
        "PUT"    => [],
        "DELETE" => []
    ];
    protected Box $box;
    protected $adminPrefix = '/panel/admin';


    public function setBox(Box $box)
    {

        $this->box = $box;
    }




    /**
     * Sets the admin prefix for the PHP function.
     *
     * @param string $prefix The admin prefix to be set.
     */
    public function setAdminPrefix($prefix)
    {

        $this->adminPrefix = $prefix;
    }




    /**
     * Retrieves a route object for a GET request.
     *
     * @param string $path The path for the route.
     * @param callable $callback The callback function for the route.
     * @param bool $isAdmin Determines if the route is for an admin.
     * @throws None
     * @return Route The route object.
     */
    public function get($path, $callback, $isAdmin = false): Route
    {

        if ($isAdmin) $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");

        $route = new Route(Route::GET, $path, $callback);

        return $this->addRoute(Route::GET, $route);
    }




    /**
     * Creates a new POST route and adds it to the route collection.
     *
     * @param string $path The path of the route.
     * @param callable $callback The callback function to be executed when the route is matched.
     * @param bool $isAdmin Whether the route is for admin only.
     * @return Route The created route.
     */
    public function post($path, $callback, $isAdmin = false): Route
    {

        if ($isAdmin) $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");

        $route = new Route(Route::POST, $path, $callback);

        return $this->addRoute(Route::POST, $route);
    }




    /**
     * Adds a PUT route to the router.
     *
     * @param string $path The path for the route.
     * @param callable $callback The callback function to be executed when the route is matched.
     * @param bool $isAdmin (optional) Whether the route is for admin use only. Defaults to false.
     * @return Route The added route.
     */
    public function put($path, $callback, $isAdmin = false): Route
    {

        if ($isAdmin) $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");

        $route = new Route(Route::PUT, $path, $callback);

        return $this->addRoute(Route::PUT, $route);
    }




    /**
     * Deletes a route.
     *
     * @param string $path The path of the route.
     * @param callable $callback The callback function to handle the route.
     * @param bool $isAdmin (optional) Indicates if the route is for an admin. Defaults to false.
     * @throws \Some_Exception_Class Description of the exception that can be thrown.
     * @return \Route The created route.
     */
    public function delete($path, $callback, $isAdmin = false): Route
    {

        if ($isAdmin) $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");

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

        if (!isset($this->routeStack[$method])) {
            throw new Exception("Unsupported HTTP method: $method", 405);
        }

        /**
         * @var Route $route
         */
        foreach ($this->routeStack[$method] as $route) {

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

        $pattern = preg_replace('/\//', '\/', $route);

        $pattern = '/^' . preg_replace('/\{(.?)\}/', '(.?)', $pattern) . '$/';

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

        $pattern = preg_replace('/\//', '\/', $route);

        $pattern = '/^' . preg_replace('/\{(.?)\}/', '(.?)', $pattern) . '$/';

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
     * @throws Some_Exception_Class If an exception occurs during processing.
     * @return Response|null The response object or null if no response is returned.
     */
    protected function handle(Request $request, Route $route): Response
    {

        $callback        = $route->getController();
        $middlewareStack = $route->getMiddlewareStack();

        $response =
            $middlewareStack->handle(
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
     * @throws Error If the callback is a string and the controller or method is not found.
     * @return Response The response returned by the callback function or a new Response object.
     */
    protected function callCallback(Request $request, $callback): Response
    {

        if (is_callable($callback)) {

            return call_user_func($callback, $request);
        } else if (is_string($callback) && strpos($callback, '@') !== false) {

            list($controller, $method) = explode('@', $callback);

            if (!class_exists($controller)) {
                $controllerClass = 'il4mb\\Mpanel\\Controllers\\' . $controller;

                if (!class_exists($controllerClass)) {

                    throw new Exception("Controller not found: $controllerClass", 404);
                }
            } else {
                $controllerClass = $controller;
            }


            $controllerInstance = $this->box->mod()->$controllerClass();

      //      print_r($controllerInstance);

            // if (!method_exists($controllerInstance, $method)) 
            // {

            //     throw new Exception("Method not found: $method", 404);

            // }

            return $controllerInstance->$method($request);

        } else {

            return new Response('Internal server error', 500);
        }
    }
}

<?php

namespace MerapiPanel\Utility;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Core\Cog\Config;
use MerapiPanel\Core\Exception\CodeException;
use MerapiPanel\Utility\Middleware\Component;
use MerapiPanel\Utility\Http\Response;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Exceptions\Error;
use MerapiPanel\Module\Users\Custom\Extension;

class Router extends Component
{

    protected $routeStack = [
        "GET"    => [],
        "POST"   => [],
        "PUT"    => [],
        "DELETE" => []
    ];
    protected Box $box;
    protected $adminPrefix = '/panel/admin';
    protected Config $config;


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
     * @param string $controller The callback function for the route.
     * @param bool $isAdmin Determines if the route is for an admin.
     * @throws None
     * @return Route The route object.
     */
    public function get($path, $method, $controller): Route
    {

        $this->validateController($controller);

        if (strtolower(basename($controller)) === "admin") {

            $path = rtrim($this->adminPrefix, "/") . "/" . trim($path, "/");
        }

        $route = new Route(Route::GET, $path, $controller . "@$method");

        return $this->addRoute(Route::GET, $route);
    }




    /**
     * Creates a new POST route and adds it to the route collection.
     *
     * @param string $path The path of the route.
     * @param string $controller The controller function to be executed when the route is matched.
     * @param bool $isAdmin Whether the route is for admin only.
     * @return Route The created route.
     */
    public function post($path, $method, $controller): Route
    {

        $this->validateController($controller);

        if (strtolower(basename($controller)) === "admin") {

            $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");
        }

        $route = new Route(Route::POST, $path, $controller . "@$method");

        return $this->addRoute(Route::POST, $route);
    }




    /**
     * Adds a PUT route to the router.
     *
     * @param string $path The path for the route.
     * @param string $controller The controller function to be executed when the route is matched.
     * @param bool $isAdmin (optional) Whether the route is for admin use only. Defaults to false.
     * @return Route The added route.
     */
    public function put($path, $method, $controller): Route
    {

        $this->validateController($controller);

        if (strtolower(basename($controller)) === "admin") {

            $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");
        }

        $route = new Route(Route::PUT, $path, $controller . "@$method");

        return $this->addRoute(Route::PUT, $route);
    }



    /**
     * Deletes a route.
     *
     * @param string $path The path of the route.
     * @param string $controller The controller function to handle the route.
     * @param bool $isAdmin (optional) Indicates if the route is for an admin. Defaults to false.
     * @throws \Some_Exception_Class Description of the exception that can be thrown.
     * @return \Route The created route.
     */
    public function delete($path, $method, $controller): Route
    {

        $this->validateController($controller);


        if (strtolower(basename($controller)) === "admin") {

            $path = rtrim($this->adminPrefix, "/") . "/" . ltrim($path, "/");
        }

        $route = new Route(Route::DELETE, $path, $controller . "@$method");

        return $this->addRoute(Route::DELETE, $route);
    }




    function validateController($controller)
    {

        if (
            strtolower(basename($controller)) !== "admin" &&
            strtolower(basename($controller)) !== "guest"
        ) {
            throw new Exception("Controller must be Admin or Guest");
        }
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

        if (strlen($path) > 1 && substr($path, -1) !== "/") $path .= "/";
        if (strlen($route) > 1 && substr($route, -1) !== "/") $route .= "/";

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

        $pattern = preg_replace('/\//', '\/', $route);

        $pattern = '/^' . preg_replace('/\{(.+?)\}/', '(.+?)', $pattern) . '$/';

        $path = $path[strlen($path) - 1] !== "/" ? $path . "/" : $path;
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

        // $response =
        //     $middlewareStack->handle(
        //         $request,
        //         function (Request $request) use ($callback) {
        //             return $this->callCallback($request, $callback);
        //         }
        //     );


        // if ($response instanceof Response) {

        //     return $response;
        // }

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
    protected function callCallback(Request $request, $callback)
    {


        $response = new Response();
        if (is_callable($callback)) {

            return call_user_func($callback, $request);
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


            /**
             * @var $controllerInstance
             * - init controller from class address
             */
            $controllerInstance = $this->box->$controllerClass();
            $view = $this->box->Module_ViewEngine();
            $view->addGlobal('request', $request->__toJson());

            if ($request->getMethod() === Route::GET) {


                $retrun = $controllerInstance->$method($view, $request);

                if (is_object($retrun) || is_array($retrun)) {
                    return $this->handleApiResponse($retrun);
                } elseif (is_string($retrun)) {
                    return $this->handleViewResponse([
                        'controller' => $controllerInstance,
                        'file-view'  => $retrun
                    ]);
                } else {
                    throw new Exception("Unxpected response type, Controller: $controller Method: $method should return string or array but null returned", 400);
                }
            } else {

                return $this->handleApiResponse($controllerInstance->$method($view, $request));
            }

            return $response;
        } else {

            return new Response('Internal server error', 500);
        }
    }



    public function handleApiResponse($json = [])
    {

        $response = new Response();
        $response->setHeader('Content-Type', 'application/json');

        $content = ["code" => 304, "message" => "No change", "data" => null];

        if (is_array($json)) {

            $content["code"]    = isset($json['code']) ? $json['code'] : null;
            $content["message"] = isset($json['message']) ? $json['message'] : null;
            $content["data"]    = isset($json['data']) ? $json['data'] : null;
        } elseif (is_string($json)) {

            $json = json_decode($json, true);

            if (json_last_error() === JSON_ERROR_NONE) {

                $content["code"]    = isset($json['code']) ? $json['code'] : null;
                $content["message"] = isset($json['message']) ? $json['message'] : null;
                $content["data"]    = isset($json['data']) ? $json['data'] : null;
            } else {

                $content["code"]    = 200;
                $content["message"] = $json;
                $content["data"]    = null;
            }
        }

        header('HTTP/1.1 ' . $content["code"] . ' ' . $content["message"]);
        $response->setContent($content);

        return $response;
    }

    public function handleViewResponse($opt = [
        'file-view' => null,
        'controller' => null,
    ])
    {

        /**
         * @var object $controller
         */
        $controller = $opt['controller'];
        /**
         * @var string $fileView
         */
        $fileView   = $opt['file-view'];

        /**
         * @var Response $response
         */
        $response = new Response();


        $meta   = $controller->__getMeta();
        $file   = $meta['file'];
        $zone   = strtolower(basename(pathinfo($file, PATHINFO_BASENAME), ".php"));
        $module = strtolower(basename($meta['location']));


        $view = $this->box->Module_ViewEngine();

        $file = "@$zone>$module" . "/" . ltrim($fileView, "\/");
        $template = $view->load($file);
        $content = $template->render($view->getVariables());

        $response->setContent($content);

        return $response;
    }
}

<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Database;
use il4mb\Mpanel\Core\Directory;
use il4mb\Mpanel\Exceptions\ErrorHandle;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;
use il4mb\Mpanel\Logger\Logger;
use il4mb\Mpanel\Core\Http\Router;
use il4mb\Mpanel\Core\EventSystem;
use il4mb\Mpanel\Core\LocaleEngine;
use il4mb\Mpanel\Core\Plugin\PluginManager;
use il4mb\Mpanel\Exceptions\Error;
use il4mb\Mpanel\Modules\ModuleStack;
use il4mb\Mpanel\Twig\TemplateEngine;
use Throwable;

error_reporting(0);

 // Register the shutdown function
register_shutdown_function(ErrorHandle::getInstance()->shutdown());


class App
{

    protected $router;
    protected $database;
    protected $logger;
    protected PluginManager $pluginManager;
    protected Directory $directory;
    protected EventSystem $eventSystem;
    protected ModuleStack $moduleManager;
    protected TemplateEngine $template;
    protected LocaleEngine $locale;


    /**
     * Constructor function for initializing the class.
     * 
     * It creates instances of the EventSystem, MiddlewareStack,
     * Router, Config, Logger, and PluginManager classes.
     *
     * @return void
     */
    public function __construct()
    {

       
        try {

            $this->locale          = new LocaleEngine($this);
            $this->template        = new TemplateEngine($this);
            $this->eventSystem     = new EventSystem();
            $this->router          = Router::getInstance();
            $this->logger          = new Logger();
            $this->pluginManager   = new PluginManager($this);
            $this->moduleManager   = new ModuleStack();

        } catch (Throwable $e) {

            $this->error_handler($e);
        }

        
    }


    public function getLocalEngine(): LocaleEngine
    {
        return $this->locale;
    }

    public function getTemplate(): TemplateEngine
    {
        return $this->template;
    }


    function error_handler(Throwable $e): void
    {


        if ($e instanceof Error) {

            echo $e->getHtmlView($this);
        } else {

            // $error = new Error($e->getMessage(), $e->getCode());
            // echo  $error->getHtmlView($this);
        }

        exit;
    }



    public function getEventSystem(): EventSystem
    {

        return $this->eventSystem;
    }



    /**
     * Retrieves the PluginManager object.
     *
     * @return PluginManager The PluginManager object.
     */
    public function getPluginManager(): PluginManager
    {

        return $this->pluginManager;
    }

    function getDirectory()
    {

        return __DIR__;
    }

    /**
     * Set the router object.
     *
     * @param Router $router The router object to set.
     */
    public function setRouter(Router $router)
    {

        $this->router = $router;
    }

    /**
     * Retrieves the router object.
     *
     * @return Router The router object.
     */
    public function getRouter()
    {

        return $this->router;
    }



    /**
     * Sets the database for the object.
     *
     * @param Database $database The database object to set.
     */
    public function setDatabase(Database $database)
    {

        $this->database = $database;
    }




    /**
     * Retrieves the database object.
     *
     * @return Database The database object.
     */
    public function getDatabase(): Database
    {

        return $this->database;
    }




    /**
     * Set the logger for the class.
     *
     * @param Logger $logger the logger instance to set
     */
    public function setLogger(Logger $logger)
    {

        $this->logger = $logger;
    }




    /**
     * Retrieves the logger instance.
     *
     * @return Logger The logger instance.
     */
    public function getLogger(): Logger
    {

        return $this->logger;
    }




    /**
     * Get method for the router.
     *
     * @param string $path The path to match.
     * @param callable $callback The callback function.
     * @return Router The router object.
     */
    public function get(string $path, mixed $callback): Router
    {

        $this->router->get($path, $callback);

        return $this->router;
    }




    /**
     * Handles a POST request to a specific path.
     *
     * @param string $path The path to handle the POST request.
     * @param callable $callback The callback function to execute when the POST request is made.
     * @return Router The router object.
     */
    public function post(string $path, mixed $callback)
    {

        $this->router->post($path, $callback);

        return $this->router;
    }





    /**
     * Executes a PUT request on the specified path.
     *
     * @param string $path The path to execute the PUT request on.
     * @param callable $callback The callback function to handle the PUT request.
     * @return Router The Router object.
     */
    public function put(string $path, mixed $callback)
    {

        $this->router->put($path, $callback);

        return $this->router;
    }




    /**
     * Deletes a resource at the specified path.
     *
     * @param string $path The path of the resource to delete.
     * @param callable $callback The callback function to execute when the resource is deleted.
     * @return $this The current instance of the object.
     */
    public function delete(string $path, mixed $callback)
    {

        $this->router->delete($path, $callback);

        return $this->router;
    }




    /**
     * Runs the application.
     */
    public function run(): void
    {

        try {


            // Create a new request
            $request = new Request();

            // Run the plugins
            $this->pluginManager->runPlugins();

            // Send the response
            $this->sendResponse($this->router->dispatch($request));
        } catch (Throwable $e) {

            $this->error_handler($e);
        }
    }





    // Method to Send HTTP Response
    protected function sendResponse(Response $response): void
    {

        $response->send();

        if ($response->getHeader("Content-Type") == "text/html") {

            $data = json_decode($response->getContent(), true) ?? [];

            if (!json_last_error()) {

                //  $data = array_merge($data, $this->getConfig()->all());
            } else {

                // $data = $this->config->all();
            }

            // if (isset($this->templateEngine) && $this->templateEngine instanceof TemplateEngine) {

            //     $response->setContent($this->templateEngine->render($data));
            // }
        }

        // Send content
        echo $response->getContent();
    }
}

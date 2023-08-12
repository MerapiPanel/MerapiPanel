<?php

namespace il4mb\Mpanel;

use il4mb\Mpanel\Config\Config;
use il4mb\Mpanel\Core\Database;
use il4mb\Mpanel\Core\Directory;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;
use il4mb\Mpanel\Logger\Logger;
use il4mb\Mpanel\Core\Http\Router;
use il4mb\Mpanel\Core\EventSystem;
use il4mb\Mpanel\Core\File;
use il4mb\Mpanel\Core\Plugin\PluginManager;
use il4mb\Mpanel\TemplateEngine\TemplateEngine;


class Application
{


    const GET_ASSIGNMENT      = 'app_get_assignment';
    const POST_ASSIGNMENT     = 'app_post_assignment';
    const PUT_ASSIGNMENT      = 'app_put_assignment';
    const VIEW_ASSIGNMENT     = 'app_view_assignment';
    const DELETE_ASSIGNMENT   = 'app_delete_assignment';
    const ON_SET_TEMPLATE     = 'on_set_template';
    const ON_GET_TEMPLATE     = 'on_get_template';
    const ON_SET_CONFIG       = 'on_setconfig';
    const ON_GET_CONFIG       = 'on_getconfig';
    const ON_SET_DATABASE     = 'on_setdatabase';
    const ON_GET_DATABASE     = 'on_getdatabase';
    const ON_GET_PLUGIN       = 'on_getplugin';
    const ON_SET_PLUGIN       = 'on_setplugin';
    const ON_SET_LOGGER       = 'on_setlogger';
    const ON_GET_LOGGER       = 'on_getlogger';
    const ON_CONTENT_RESPONSE = 'on_response';
    const ON_REQUEST          = 'on_request';
    const BEFORE_REQUEST      = 'before_request';
    const AFTER_REQUEST       = 'after_request';


    protected $router;
    protected $config;
    protected $database;
    protected $logger;
    protected TemplateEngine $templateEngine;
    protected PluginManager $pluginManager;
    private static Application $instance;
    protected Directory $directory;


    /**
     * Constructor function for initializing the class.
     * 
     * It creates instances of the EventSystem, MiddlewareStack,
     * Router, Config, Logger, and PluginManager classes.
     *
     * @return void
     */
    private function __construct()
    {

        $call_from_file        = new File(debug_backtrace()[1]['file']);
        $this->directory       = $call_from_file->getDirectory();

        $this->router          = Router::getInstance();
        $this->config          = new Config([]);
        $this->logger          = new Logger();
        $this->pluginManager   = new PluginManager($this);
    }

    

    /**
     * Returns the instance of the Application class.
     *
     * @throws None if instance is not set
     * @return Application the instance of the Application class
     */
    static function getInstance(): Application
    {

        if (!isset(self::$instance)) 
        {

            self::$instance = new Application();

        }

        return self::$instance;

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

    function get_directory(): Directory
    {

        return $this->directory;

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
     * Sets the template engine for the class.
     *
     * @param TemplateEngine $templateEngine The template engine to be set.
     * @return void
     */
    public function setTemplateEngine(TemplateEngine $templateEngine)
    {

        $this->templateEngine = $templateEngine;

        EventSystem::getInstance()->fire(self::ON_SET_TEMPLATE, [$templateEngine]);

    }

    /**
     * Retrieves the template engine used by this object.
     *
     * @return TemplateEngine The template engine instance.
     */
    public function getTemplateEngine(): TemplateEngine
    {

        EventSystem::getInstance()->fire(self::ON_GET_TEMPLATE, [$this->templateEngine]);

        return $this->templateEngine;

    }




    /**
     * Set the configuration for the object.
     *
     * @param Config $config The configuration object.
     */
    public function setConfig(Config $config)
    {

        $this->config = $config;

        EventSystem::getInstance()->fire(self::ON_SET_CONFIG, [$config]);

    }




    /**
     * Retrieves the configuration.
     *
     * @return Config The configuration.
     */
    public function getConfig(): Config
    {

        EventSystem::getInstance()->fire(self::ON_GET_CONFIG, [$this->config]);

        return $this->config;

    }




    /**
     * Sets the database for the object.
     *
     * @param Database $database The database object to set.
     */
    public function setDatabase(Database $database)
    {

        $this->database = $database;

        EventSystem::getInstance()->fire(self::ON_SET_DATABASE, [$database]);

    }




    /**
     * Retrieves the database object.
     *
     * @return Database The database object.
     */
    public function getDatabase(): Database
    {

        EventSystem::getInstance()->fire(self::ON_GET_DATABASE, [$this->database]);

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

        EventSystem::getInstance()->fire(self::ON_SET_LOGGER, [$logger]);

    }




    /**
     * Retrieves the logger instance.
     *
     * @return Logger The logger instance.
     */
    public function getLogger(): Logger
    {

        EventSystem::getInstance()->fire(self::ON_GET_LOGGER, [$this->logger]);

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

        EventSystem::getInstance()->fire(self::GET_ASSIGNMENT, [$path, $callback]);

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

        EventSystem::getInstance()->fire(self::POST_ASSIGNMENT, [$path, $callback]);

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

        EventSystem::getInstance()->fire(self::PUT_ASSIGNMENT, [$path, $callback]);

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

        EventSystem::getInstance()->fire(self::DELETE_ASSIGNMENT, [$path, $callback]);

        $this->router->delete($path, $callback);

        return $this->router;

    }




    /**
     * Runs the application.
     */
    public function run(): void
    {

        // Trigger the BEFORE_REQUEST event
        EventSystem::getInstance()->fire(self::BEFORE_REQUEST);

        // Create a new request
        $request = new Request();

        // Run the plugins
        $this->pluginManager->runPlugins();

        // Send the response
        $this->sendResponse($this->router->dispatch($request));

        // Trigger the AFTER_REQUEST event
        EventSystem::getInstance()->fire(self::AFTER_REQUEST);

    }





    // Method to Send HTTP Response
    protected function sendResponse(Response $response): void
    {

        $response->send();

        if ($response->getHeader("Content-Type") == "text/html") 
        {

            $data = json_decode($response->getContent(), true) ?? [];

            if (!json_last_error()) 
            {

                $data = array_merge($data, $this->getConfig()->all());

            } 
            else 
            {

                $data = $this->config->all();

            }

            if (isset($this->templateEngine) && $this->templateEngine instanceof TemplateEngine) 
            {

                $response->setContent($this->templateEngine->render($data));

            }

        }

        EventSystem::getInstance()->fire(self::ON_CONTENT_RESPONSE, [$response]);

        // Send content
        echo $response->getContent();

    }

}

<?php

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Database;
use il4mb\Mpanel\Core\Directory;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;
use il4mb\Mpanel\Core\Http\Router;
use il4mb\Mpanel\Core\EventSystem;
use il4mb\Mpanel\Core\LocaleEngine;
use il4mb\Mpanel\Core\Plugin\PluginManager;
use il4mb\Mpanel\Core\Exception\ErrorHandle;
use il4mb\Mpanel\Core\Logger\Logger;
use il4mb\Mpanel\Core\Module\ModuleSystem;
use il4mb\Mpanel\Core\Twig\TemplateEngine;
use Throwable;

error_reporting(0);

// Register the shutdown function
register_shutdown_function([ErrorHandle::getInstance(), 'shutdown']);

const app_config = __DIR__ . "/../config/app.yml";

$config = new Config(app_config);


class App extends Container
{

    protected $database;
    protected $logger;
    protected PluginManager $pluginManager;
    // protected Directory $directory;
    protected EventSystem $eventSystem;
    protected ModuleSystem $moduleSystem;
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

        try 
        {

            $this->register(Router::class);

            $this->locale        = new LocaleEngine($this);
            $this->template      = new TemplateEngine($this);
            $this->eventSystem   = new EventSystem();
            $this->logger        = new Logger();
            $this->pluginManager = new PluginManager($this);
            $this->moduleSystem  = new ModuleSystem();

        }
        catch (Throwable $e) 
        {

            ErrorHandle::getInstance()->catch_error($e);
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
            $this->sendResponse($this->router()->dispatch($request));
        } catch (Throwable $e) {
            ErrorHandle::getInstance()->catch_error($e);
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

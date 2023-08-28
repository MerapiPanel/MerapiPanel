<?php

namespace il4mb\Mpanel\Core;

use Exception;
use il4mb\Mpanel\Core\Exception\ErrorHandle;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;
use Throwable;

// error_reporting(0);

// Register the shutdown function
register_shutdown_function([ErrorHandle::getInstance(), 'shutdown']);

const app_config = __DIR__ . "/../config/app.yml";
$config = new Config(app_config);

class App extends Container
{

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
       
        $this->register(Template\Engine::class);
        $this->register(Http\Router::class);
        $this->register(Module\System::class);        

    }


    function getDirectory()
    {

        return __DIR__;
    }


    /**
     * Runs the application.
     */
    public function run(): void
    {

        try {

            // Create a new request
            $request = new Request();
            // Send the response
            $this->sendResponse($this("http", "router")->dispatch($request));

        } catch (Throwable $e) {
            ErrorHandle::getInstance()->catch_error($e);
        }
    }





    // Method to Send HTTP Response
    protected function sendResponse(Response $response): void
    {

        $response->send();

        // Send content
        echo $response->getContent();
    }
}

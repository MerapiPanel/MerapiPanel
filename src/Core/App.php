<?php

/**
 * @package il4mb\Mpanel
 * This is main file of Mpanel
 * do not edit this file or any of its contents
 * by editing this file, you are breaking the Application
 */

namespace il4mb\Mpanel\Core;

use il4mb\Mpanel\Core\Http\Response;
use Throwable;

class App extends BoxApp
{

    const app_config = __DIR__ . "/../config/app.yml";
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

       // ob_start();

        $this->core_error();
        $this->core_template();
        $this->setConfig(self::app_config);

        if ($this->getConfig()->get("service")) {

            $service = $this->getConfig()->get("service");

            // $address = $mod . "\\Controller\\Guest";
            // $address = strtolower(str_replace("\\", "_", $address));

            $mod = $this->core_boxmod()->$service();
            // $mod->api_hallo();
        }
        //ob_end_clean();
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

            // $request = $this->core_http_request();
            // Send the response
            // $this->sendResponse($this->core_http_router()->dispatch($request));
        } catch (Throwable $e) {

            // $this->core_error()->catch_error($e);
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

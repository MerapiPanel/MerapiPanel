<?php

/**
 * @package Mp
 * This is main file of Mpanel
 * do not edit this file or any of its contents
 * by editing this file, you are breaking the Application
 */

namespace MerapiPanel;

use Throwable;

$GLOBALS['time_start'] = microtime(true);

class App extends Box
{

    const app_config = __DIR__ . "/config/app.yml";
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

        setcookie('auth', 'admin', time() + 3600, "/");


        // ob_start();
        $this->Core_Exception();
        $this->Module_ViewEngine();
        $this->setConfig(self::app_config);
        $this->__registerController();

        if ($this->getConfig()->get("service")) {

            $service = $this->getConfig()->get("service");
            $this->$service();
        }
    }


    /**
     * Runs the application.
     */
    public function run(): void
    {

        try {

            $request = $this->utility_http_request();
            // Send the response
            echo $this->utility_router()->dispatch($request->getRealInstance());
        } catch (Throwable $e) {

            // print_r($e);

            $this->core_exception()->catch_error($e);
        }
    }
}

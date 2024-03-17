<?php



namespace MerapiPanel;

ini_set("error_log", __DIR__ . "/php-error.log");

use MerapiPanel\Core\Proxy;
use Throwable;

$GLOBALS['time_start'] = microtime(true);

$conf = [];
$conf['root'] = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS["conf"] = $conf;
$GLOBALS["debug"] = true;
$_ENV['AES_KEY'] = "a3af08095b8a63cf50d35129d514ca2703c89d159963dc7a53e5766361bbc3c9";
$_ENV['ROOT'] = __DIR__;
$_ENV['APP'] = realpath(__DIR__ . "/..");

// error_log($_ENV['AES_KEY']);

/**
 * Description: Main class for the MerapiPanel application. 
 * @author      ilham b <durianbohong@gmail.com>
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 * @lastUpdate  2024-02-10
 * 
 * @package     MerapiPanel
 * @extends     Box
 * @uses        EventSystem, MiddlewareStack, Router, Config, Logger, PluginManager
 */
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

        $this->__registerEvent();
        parent::setConfig(self::app_config);
        $this->Core_Exception_Handler();
        // $this->Module_ViewEngine();
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

            $request = Proxy::Real($this->utility_http_request());
            echo $this->utility_router()->dispatch($request);

        } catch (Throwable $e) {
            // send to exception handler to find the error
            $this->core_exception_handler()->handle_error($e);
        }
    }
}
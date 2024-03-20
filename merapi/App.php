<?php
namespace MerapiPanel;

ini_set("error_log", __DIR__ . "/php-error.log");

use MerapiPanel\Core\Proxy;
use Throwable;

$GLOBALS['time_start'] = microtime(true);

$config = [
    "START_TIME" => microtime(true),
    "APP" => realpath(__DIR__ . "/..")
];

$loaded_config = json_decode(file_get_contents(__DIR__ . "/config/env.json"), true);

$config = array_merge($config, $loaded_config);
if (isset ($config['$schema'])) {
    unset($config['$schema']);
}
$config = array_combine(array_map(fn($x) => ("__MP_" . preg_replace("/[^A-Z]+/im", "_", strtoupper($x)) . "__"), array_keys($config)), $config);

foreach ($config as $key => $value) {
    $_ENV[$key] = $value;
}


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
        $this->core_exception_handler();
        $this->__registerController();

        if (isset ($_ENV['__MP_SERVICE__'])) {
            $service = $_ENV['__MP_SERVICE__'];
            $this->$service();
        }
    }


    /**
     * Runs the application.
     */
    public function run(): void
    {

        ob_start();
        try {

            $request = Proxy::Real($this->utility_http_request());
            echo $this->utility_router()->dispatch($request);

        } catch (Throwable $e) {
            // send to exception handler to find the error
            $this->core_exception_handler()->handle_error($e);
        }

        ob_end_flush();
    }
}

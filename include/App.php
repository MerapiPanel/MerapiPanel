<?php
namespace MerapiPanel {

    use MerapiPanel\Standlone\ENV;
    use MerapiPanel\Standlone\ENV_Container;
    use MerapiPanel\Utility\Http\Request;
    use MerapiPanel\Utility\Router;

    ini_set("error_log", __DIR__ . "/php-error.log");


    $config = [
        "START_TIME" => microtime(true), // start time
        "CWD" => realpath(__DIR__ . "/.."), // current working directory
        "APP" => __DIR__,
    ];

    $loaded_config = json_decode(file_get_contents(__DIR__ . "/config/env.json"), true); // load config

    $config = array_merge($config, $loaded_config);
    if (isset($config['$schema'])) {
        unset($config['$schema']); // remove $schema
    }
    $config = array_combine(array_map(fn($x) => ("__MP_" . preg_replace("/[^A-Z]+/im", "_", strtoupper($x)) . "__"), array_keys($config)), $config); // convert all keys to uppercase

    foreach ($config as $key => $value) {
        $_ENV[$key] = $value; // set environment variable
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


        protected readonly Request $request;


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
            $this->request = Request::getInstance();
            // initialize parent first to preventive any error
            parent::__construct();
            // send signal to prepare
            $this->prepare();
        }


        protected function prepare()
        {

            // send signal for prepare to all modules in parent
            parent::initialize();

            // $service = $_ENV['__MP_SERVICE__'];
            // if (is_array($service)) {
            //     foreach ($service as $module) {
            //         $module = Box::module($module)->Service;
            //     }
            // } else if (is_string($service)) {
            //     Box::module($service);
            // }
        }


        /**
         * Runs the application.
         */
        public function run(): void
        {

            ob_start();
            echo Router::dispatch(Request::getInstance());
            $output = ob_get_contents();
            ob_end_clean();

            // send signal for shutdown to all modules in parent
            // parent::shutdown();

            echo $output;
        }
    }
}
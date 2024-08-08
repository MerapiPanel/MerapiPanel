<?php
namespace MerapiPanel {

    use MerapiPanel\Utility\Http\Request;
    use MerapiPanel\Utility\Router;

    ini_set("error_log", __DIR__ . "/php-error.log");

    $config = [
        "START_TIME" => microtime(true), // start time
        "CWD" => realpath(__DIR__ . "/.."), // current working directory
        "APP" => __DIR__,
        "VERSION" => "1.1.1"
    ];

    if (!file_exists($config['APP'] . "/config/env.php") && file_exists($config['CWD'] . "/install/index.php")) {
        header("Location: /install/index.php");
        exit;
    } else if (!file_exists($config['APP'] . "/config/env.php") && !file_exists($config['CWD'] . "/install/index.php")) {
        exit("MerapiPanel installer not found. Please install MerapiPanel first. Visit https://github.com/MerapiPanel/MerapiPanel to install.");
    }

    $loaded_config = include __DIR__ . "/config/env.php"; // load config

    if (isset($loaded_config['timezone'])) {
        date_default_timezone_set($loaded_config['timezone']);
    }

    $config = array_merge($config, $loaded_config);
    if (isset($config['$schema'])) {
        unset($config['$schema']); // remove $schema
    }

    if (isset($config['timezone'])) {
        date_default_timezone_set($config['timezone']);
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

            $_ENV['__MP_ACCESS__'] = 'guest';

            if (isset($_ENV['__MP_ADMIN__']['prefix'])) {
                if (strpos($this->request->getPath(), $_ENV['__MP_ADMIN__']['prefix']) === 0) {
                    $_ENV['__MP_ACCESS__'] = "admin";
                }
            }

            // send signal for prepare to all modules in parent
            parent::initialize();
        }


        /**
         * Runs the application.
         */
        public function run(): void
        {

            echo Router::dispatch(Request::getInstance());
        }
    }
}
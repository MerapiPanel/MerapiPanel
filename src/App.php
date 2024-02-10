<?php



namespace MerapiPanel;

ini_set("error_log", __DIR__ . "/php-error.log");

use MerapiPanel\Core\Mod\Proxy;
use Throwable;

$GLOBALS['time_start'] = microtime(true);

$conf = [];
$conf['root'] = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS["conf"] = $conf;
$GLOBALS["debug"] = true;


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

        // setcookie('auth', 'admin', time() + 3600, "/");

        parent::setConfig(self::app_config);
        // ob_start();
        $this->Core_Exception_Handler();
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

            $request = Proxy::Real($this->utility_http_request());
            // Send the response
            echo $this->utility_router()->dispatch($request);
        } catch (Throwable $e) {
            $this->core_exception()->catch_error($e);
        }
    }
}

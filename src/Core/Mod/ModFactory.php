<?php

namespace il4mb\Mpanel\Core\Module;

use il4mb\Mpanel\Core\AppBox;
use il4mb\Mpanel\Core\Config;

class ModFactory
{

    const USER   = "user";
    const PUBLIC = "public";

    protected Config $config;
    protected string $directory;
    protected object $mod;
    protected string $className;


    public function __construct($yml = [])
    {

        if (!isset($yml['location'])) {
            throw new \Exception('Module location is not set');
        }

        $this->config    = Config::fromArray($yml);
        $this->className = "il4mb\\Mpanel\\Modules\\" . basename($yml['location']) . "\\Engine";

    }

    function setBox(AppBox $box) {

        if($this->engine_exist()) {
            $this->mod = new $this->className($box);
        }
    }


    function engine_exist()
    {

        if (file_exists($this->config->get('location') . "/Engine.php")) {
            return true;
        }

        return false;
    }


    function controller_exist($type = self::PUBLIC)
    {

        if (file_exists($this->config->get('location') . "/Controller/" . $type . ".php")) {
            return true;
        }
        return false;
    }


    function api_exist($type = self::PUBLIC)
    {
        if (file_exists($this->config->get('location') . "/Api/" . $type . ".php")) {
            return true;
        }
        return false;
    }


    function view_exist($type = self::PUBLIC)
    {
        if (file_exists($this->config->get('location') . "/View/" . $type . ".php")) {
            return true;
        }
        return false;
    }
}

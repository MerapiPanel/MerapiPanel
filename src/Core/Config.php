<?php

namespace il4mb\Mpanel\Core;

use Symfony\Component\Yaml\Yaml;

/**
 * Config class
 * used for reading config file
 * also for setting and getting
 */
class Config
{

    protected $config;
    private $yml;


    public function __construct($yml = null)
    {
        if ($yml) {

            if (!pathinfo($yml, PATHINFO_EXTENSION) === 'yml') {

                throw new \Exception('Config file must be a .yml file');
            }

            if (!file_exists($yml)) {
                throw new \Exception('Config file does not exist');
            }

            $this->config = Yaml::parse($yml);
            $this->yml = $yml;
            
        } else {
            $this->config = [];
        }
    }


    public function get($key)
    {
        return $this->config[$key]?? null;
    }


    public function set($key, $value) {
        $this->config[$key] = $value;

        if($this->yml) {
            file_put_contents($this->yml, Yaml::dump($this->config));
        }
    }


    function getAll() {
        return $this->config;
    }
}

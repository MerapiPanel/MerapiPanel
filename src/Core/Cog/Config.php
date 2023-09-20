<?php

namespace MerapiQu\Core\Cog;

use ArrayAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * Config class
 * used for reading config file
 * also for setting and getting
 */
class Config implements \ArrayAccess
{

    protected $config = [];
    protected $yml;


    public function __construct($yml = null)
    {
        if ($yml) {

            if (!pathinfo($yml, PATHINFO_EXTENSION) === 'yml') {

                throw new \Exception('Config file must be a .yml file');
            }

            if (!file_exists($yml)) {
                throw new \Exception('Config file does not exist');
            }

            $yml = realpath($yml);
            $value = Yaml::parseFile($yml);

            $this->config =  $value;
            $this->yml    = $yml;
        } else {
            $this->config = [];
        }
    }


    function offsetExists($k) : bool
    {
        return isset($this->config[$k]);
    }
    function offsetGet($k) : mixed
    {

        return $this->config[$k];
    }
    function offsetSet($k, $v) : void
    {

        $this->config[$k] = $v;
    }
    function offsetUnset($k) : void
    {
        unset($this->config[$k]);
    }


    public function get($key)
    {
        return $this->config[$key] ?? null;
    }


    public function set($key, $value)
    {

        $this->config[$key] = $value;

        if ($this->yml) {
            file_put_contents($this->yml, Yaml::dump($this->config));
        }
    }

    public function save()
    {
        if ($this->yml) {
            file_put_contents($this->yml, Yaml::dump($this->config));
        }
    }


    public static function fromArray($array = []): Config
    {
        $config = new Config();
        foreach ($array as $key => $value) {
            $config->set($key, $value);
        }
        return $config;
    }
}

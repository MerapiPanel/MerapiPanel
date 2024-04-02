<?php

namespace MerapiPanel\Core\Cog;

use ArrayAccess;


/**
 * Config class
 * used for reading config file
 * also for setting and getting
 */
class Config implements ArrayAccess
{

    protected $config = [];
    protected $json;


    public function __construct($json = null)
    {
        if ($json) {

            if (!pathinfo($json, PATHINFO_EXTENSION) === 'json') {

                throw new \Exception('Config file must be a .json file');
            }

            if (!file_exists($json)) {
                throw new \Exception('Config file does not exist');
            }

            $json = realpath($json);
            $this->config = json_decode(file_get_contents($json), true);
            $this->json = $json;
        } else {
            $this->config = [];
        }
    }


    function offsetExists($k): bool
    {
        return isset ($this->config[$k]);
    }
    function offsetGet($k): mixed
    {

        return $this->config[$k];
    }
    function offsetSet($k, $v): void
    {

        $this->config[$k] = $v;
    }
    function offsetUnset($k): void
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

        if ($this->json) {
            file_put_contents($this->json, json_encode($this->config));
        }
    }

    public function save()
    {
        if ($this->json) {
            file_put_contents($this->json, json_encode($this->config));
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

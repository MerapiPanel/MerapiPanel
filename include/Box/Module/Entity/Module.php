<?php

namespace MerapiPanel\Box\Module\Entity {

    use Closure;
    use MerapiPanel\Box\Module\AbstractLoader;
    use MerapiPanel\Box\Container;
    use ReflectionObject;
    use ReflectionProperty;

    class Module
    {

        public readonly string $path;
        public readonly string $namespace;
        protected $fragments = [];
        public Props|null $props = null;
        protected Container $container;
        protected array $listener = [];



        public function __construct(Container $container, array $payload)
        {

            $this->container = $container;
            $this->props = new Props();

            $required_payload = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_READONLY);
            foreach ($required_payload as $value) {
                $name = $value->getName();
                if (array_key_exists($name, $payload)) {
                    $this->$name = $payload[$name];
                } else {
                    throw new \Exception('Missing required property: ' . $name);
                }
            }
        }


        public function __on($key, Closure $callback)
        {
            $this->listener[$key][] = $callback;
        }


        public function getLoader(): AbstractLoader
        {

            return $this->container->getLoader();
        }


        public function __get($name): Proxy|Fragment|bool
        {

            if (empty($this->fragments[$name])) {
                $fragment = $this->getLoader()->loadFragment($name, $this);
                $this->fragments[$name] = $fragment;
            } else {
                $fragment = &$this->fragments[$name];
            }
            $result = &$fragment;
            if (isset($this->listener[$name])) {
                foreach ($this->listener[$name] as $callback) {
                    $result = $callback($result, $this);
                }
            }
            return $result;
        }



        public function __call($method, $args)
        {
            $service = $this->Service;
            if (!$service) {
                throw new \Exception("Default service not found in " . $this->namespace);
            }
            return $service->$method(...$args);
        }



        public function __toString()
        {

            return "Module: {$this->namespace}";
        }
    }


    

    class Props
    {
        protected $stack = [];

        protected $listeners = [
            "set" => [],
            "get" => [],
        ];

        public function __construct()
        {
        }


        public function addOnSetListener($name, Closure $callback)
        {
            if (!isset($this->listeners['set'][$name])) {
                $this->listeners['set'][$name] = [];
            }
            $this->listeners['set'][$name][] = $callback;
        }


        public function addOnGetListener($name, Closure $callback)
        {
            if (!isset($this->listeners['get'][$name])) {
                $this->listeners['get'][$name] = [];
            }
            $this->listeners['get'][$name][] = $callback;
        }

        function __get($name)
        {

            $result = $this->stack[$name] ?? null;
            $output = &$result;

            if (isset($this->listeners['get'][$name])) {
                foreach ($this->listeners['get'][$name] as $callback) {
                    $output = $callback($output, $this);
                }
            }
            return $output;
        }

        function __set($name, $value)
        {
            $this->stack[$name] = $value;

            if (isset($this->listeners['set'][$name])) {
                foreach ($this->listeners['set'][$name] as $callback) {
                    $callback($value);
                }
            }
        }


        function __isset($name)
        {
            return isset($this->stack[$name]);
        }
    }

}



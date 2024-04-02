<?php

namespace MerapiPanel\Box\Module\Entity {

    use Closure;
    use Exception;
    use Symfony\Component\Filesystem\Path;

    class Fragment
    {
        public readonly Fragment|Module $parent;
        public readonly string $name;
        public readonly string $path;
        protected array $childrens = [];
        protected array $listener = [];


        public function __construct(string $name, Module|Fragment $parent)
        {
            $this->name = $name;
            $this->parent = $parent;
            $this->path = $this->resolvePath();
        }


        public function __on($key, Closure $callback)
        {
            $this->listener[$key][] = $callback;
        }


        public function getModule(): Module
        {

            $fragment = $this;
            while (($fragment->parent instanceof Fragment)) {
                $fragment = $fragment->parent;
            }

            return $fragment->parent;
        }


        public function resolvePath($name = null)
        {
            $path = "";
            $fragment = $this;
            while (($fragment->parent instanceof Fragment)) {
                $path = Path::join($fragment->parent->name, $path);
                $fragment = $fragment->parent;
            }
            return Path::join($fragment->parent->path, $path, $this->name, ($name ? $name : ""));
        }



        public function resolveClassName($name = null)
        {
            $path = "";
            $fragment = $this;
            while (($fragment->parent instanceof Fragment)) {
                $path = Path::join($fragment->parent->name, $path);
                $fragment = $fragment->parent;
            }
            return str_replace("/", "\\", Path::join($fragment->parent->namespace, $path, $this->name, ($name ? $name : "")));
        }



        /**
         * Magic method
         */
        public function __get($name)
        {

            if (empty($this->childrens[$name])) {
                $fragment = $this->getModule()->getLoader()->loadFragment($name, $this);
                $this->childrens[$name] = $fragment;
            } else {
                $fragment = $this->childrens[$name];
            }

            $result = &$fragment;
            if (isset($this->listener[$name])) {
                foreach ($this->listener[$name] as $callback) {
                    $result = $callback($result, $this);
                }
            }
            return $result;
        }


        /**
         * Magic method
         */
        public function __call($method, $args)
        {
            throw new Exception("Cannot call method $method, " . $this->resolveClassName() . " is not object");
        }


        public function __toString()
        {
            return "Fragment: {$this->resolvePath()}";
        }
    }
}
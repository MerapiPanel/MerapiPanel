<?php
namespace MerapiPanel\Box\Module\Entity {

    use Closure;
    use MerapiPanel\Box\Module\__Fragment;
    use ReflectionClass;

    class Proxy extends Fragment
    {

        private mixed $instance;
        public string $className;
        protected array $errors = [];
        protected array $listener = [];


        public function __construct(string $name, Fragment|Module $parent)
        {

            parent::__construct($name, $parent);
            unset($this->childrens);

            $this->className = $this->resolveClassName();
            $reflector       = new ReflectionClass($this->className);
            $this->instance  = $reflector->newInstanceWithoutConstructor();

            if (!($this->instance instanceof __Fragment)) {
                throw new \Exception("The class " . $this->className . " should extend " . __Fragment::class);
            }


            if (method_exists($this->instance, "onCreate")) {
                //ob_start();
                $this->instance->onCreate($this->getModule());
                //ob_end_clean();
            }
        }


        public function __on($key, Closure $callback)
        {
            if (!isset($this->listener[$key])) {
                $this->listener[$key] = [];
            }
            $this->listener[$key][] = $callback;
        }



        public function __call($method, $args)
        {

            if (!method_exists($this->instance, $method)) {
                throw new \Exception("Method not found: " . $method . " in " . $this->className);
            }

            $output = call_user_func_array([$this->instance, $method], $args);
            $result = &$output;

            if (isset($this->listener[$method])) {
                foreach ($this->listener[$method] as $callback) {
                    $result = $callback($result, $this);
                }
            }
            return $result;
        }



        public function __get($name)
        {

            if (!property_exists($this->instance, $name)) {

                throw new \Exception("Property not found: {" . $name . "} in " . $this->className);
            }
            return $this->instance->$name;
        }



        public static function instanceOf(Proxy $a, Proxy $b)
        {
            return $a->className == $b->className;
        }


        public function method_exists($method) {

            return method_exists($this->instance, $method);
        }

        public function __toString()
        {

            return "Proxy: {$this->className}";
        }
    }
}
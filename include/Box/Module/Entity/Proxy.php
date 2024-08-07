<?php

namespace MerapiPanel\Box\Module\Entity {

    use Closure;
    use Exception;
    use MerapiPanel\Box\Module\__Fragment;
    use MerapiPanel\Exception\Model\Faker;
    use ReflectionClass;
    use Throwable;

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
            $reflector = new ReflectionClass($this->className);
            $this->instance = $reflector->newInstanceWithoutConstructor();

            if (!($this->instance instanceof __Fragment)) {
                $this->getModule()->throwError(
                    new Exception("The class {$this->className} should extend " . __Fragment::class),
                    $this->path,
                    $this->getClassStartLine()
                );
            }

            if (method_exists($this->instance, "onCreate")) {
                try {
                    $this->instance->onCreate($this->getModule());
                } catch (Throwable $t) {
                    // $this->getModule()->__putError($t);
                }
            }
        }


        public function listenOn($key, Closure $callback)
        {
            if (!isset($this->listener[$key])) {
                $this->listener[$key] = [];
            }
            $this->listener[$key][] = $callback;
        }



        public function __call($method, $args)
        {

            try {
                if (!method_exists($this->instance, $method)) {
                    throw new Exception("Method not found");
                }

                if (!$this->__isMethodAllowed($method)) {
                    throw new Exception("Method not allowed");
                }

                $output = call_user_func_array([$this->instance, $method], $args);
                $result = &$output;

                if (isset($this->listener[$method])) {
                    foreach ($this->listener[$method] as $callback) {
                        $callback($result, ...$args);
                    }
                }
                if (isset($this->listener["*"])) {
                    foreach ($this->listener["*"] as $callback) {
                        $callback($method, $result, ...$args);
                    }
                }
                return $result;
            } catch (Throwable $t) {
                $this->getModule()->throwError($t);
            }
        }



        public function __get($name)
        {
            if (!property_exists($this->instance, $name)) {
                return false;
            }
            return $this->instance->$name;
        }




        public static function instanceOf(Proxy $a, Proxy $b)
        {
            return $a->className == $b->className;
        }



        public function __isMethodAllowed($method)
        {

            $module = $this->getModule();
            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            if (!$reflectorMethod->isPublic()) {
                $this->getModule()->throwError(
                    new Exception("Method not public: {$method} in {$this->className}"),
                    $this->path,
                    $this->__method_line($method)
                );
                return false;
            }
            $comment = $reflectorMethod->getDocComment();
            preg_match("/@admin\s+(\w+)/", $comment, $matches);
            if (isset($matches[1]) && $module->Service instanceof Proxy && $module->Service->__method_exists("isAdmin") && $module->Service->isAdmin()) {
                return $matches[1] == 'true';
            }
            preg_match("/@guest\s+(\w+)/", $comment, $matches);
            if (isset($matches[1]) && ($module->Service instanceof Proxy && $module->Service->__method_exists("isGuest") && $module->Service->isGuest() || $matches[1] == 'false')) {
                return $matches[1] == 'true';
            }

            return true;
        }



        private function getClassStartLine()
        {
            $reflector = new ReflectionClass($this->instance);
            return $reflector->getStartLine();
        }



        public function __method_is_public($method)
        {

            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            return $reflectorMethod->isPublic();
        }

        public function __method_is_private($method)
        {
            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            return $reflectorMethod->isPrivate();
        }
        public function __method_is_protected($method)
        {
            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            return $reflectorMethod->isProtected();
        }
        public function __method_is_abstract($method)
        {
            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            return $reflectorMethod->isAbstract();
        }
        public function __method_is_final($method)
        {
            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            return $reflectorMethod->isFinal();
        }
        public function __method_is_static($method)
        {
            $reflectorMethod = new \ReflectionMethod($this->instance, $method);
            return $reflectorMethod->isStatic();
        }

        public function __method_line($methodName)
        {
            $reflector = new \ReflectionMethod($this->instance, $methodName);
            return $reflector->getStartLine();
        }




        public function __method_params($method)
        {

            $reflectionMethod = new \ReflectionMethod($this->className, $method);
            return $reflectionMethod->getParameters();
        }



        public function __method_exists($method)
        {

            return method_exists($this->instance, $method);
        }



        public function __toString()
        {

            return "Proxy: {$this->className}";
        }
    }
}

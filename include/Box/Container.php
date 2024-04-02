<?php
namespace MerapiPanel\Box {

    use MerapiPanel\Box\Module\AbstractLoader;
    use MerapiPanel\Box\Module\Entity\Module;
    use Throwable;

    class Container
    {

        private array $stack = [];
        protected AbstractLoader $loader;


        public function __construct(AbstractLoader $loader)
        {

            $this->setLoader($loader);
        }



        /**
         * Set the loader of a specific type with the given loader instance.
         *
         * @param AbstractLoader $loader The loader instance to set
         * @throws Throwable If an error occurs during initialization
         */
        public final function setLoader(AbstractLoader $loader)
        {
            $this->loader = $loader;
        }



        public final function getLoader(): AbstractLoader
        {
            return $this->loader;
        }


        public function __isset($name)
        {

            return isset($this->stack[$name]);
        }



        public function __set($name, Module $value)
        {
            $this->stack[$name] = $value;
        }

        public function __unset($name)
        {
            unset($this->stack[$name]);
        }



        public function initialize()
        {
            $this->loader->initialize($this);
        }




        public function __get($name)
        {
            if (empty($this->stack[$name])) {
                $this->stack[$name] = $this->loader->loadModule($name, $this);
            }
            return $this->stack[$name];
        }
    }
}
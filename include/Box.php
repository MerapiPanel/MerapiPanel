<?php

namespace MerapiPanel {

    use MerapiPanel\Box\Container;
    use MerapiPanel\Box\Module\Entity\Fragment;
    use MerapiPanel\Box\Module\Entity\Module;
    use MerapiPanel\Box\Module\Entity\Proxy;
    use MerapiPanel\Box\Module\ModuleLoader;
    use MerapiPanel\Views\View;

    /**
     * Description: Box is an instance used for communication between instances in MerapiPanel, especially for modules. With a box, it allows for communication between modules.
     *
     * For more information, see the Class Box at https://github.com/MerapiPanel/MerapiPanel/wiki/Class-Box.
     *
     * @author      ilham b <durianbohong@gmail.com>
     * @copyright   Copyright (c) 2022 MerapiPanel
     * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
     * @lastUpdate  2024-02-10
     */

    class Box
    {


        private static $instance;
        private Container $module_container;




        protected function __construct()
        {
            if ($_ENV['__MP_CACHE__'] == true) {
                if (file_exists(__DIR__ . "/__.dat")) {
                    $serialize = file_get_contents(__DIR__ . "/__.dat");
                    $this->module_container = unserialize($serialize);
                    self::$instance = $this;
                    return;
                }
            } else if (file_exists(__DIR__ . "/__.dat")) {
                unlink(__DIR__ . "/__.dat");
            }

            $this->module_container = new Container(new ModuleLoader(__DIR__ . "/Module"));
            self::$instance = $this;
        }




        protected function initialize()
        {
            $this->module_container->initialize();
        }




        public static function module($name = null): Container|Fragment|Proxy|Module|null
        {

            if (!$name || empty($name)) {
                return self::$instance->module_container;
            }
            return self::$instance->module_container->$name;
        }


        public static function shutdown()
        {
            if ($_ENV['__MP_CACHE__'] == true) {

                if (file_exists(__DIR__ . "/__.dat")) {
                    if (filemtime(__DIR__ . "/__.dat") < time() - 60 * 60 * 24) { // 24 hours
                        $serialize = serialize(self::$instance->module_container);
                        file_put_contents(__DIR__ . "/__.dat", $serialize);
                    }
                } else {
                    $serialize = serialize(self::$instance->module_container);
                    file_put_contents(__DIR__ . "/__.dat", $serialize);
                }
            } else if (file_exists(__DIR__ . "/__.dat")) {
                unlink(__DIR__ . "/__.dat");
            }
        }
    }
}

namespace {

    use MerapiPanel\Exception\Catcher;

    Catcher::init();
}
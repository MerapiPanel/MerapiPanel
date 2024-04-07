<?php
namespace MerapiPanel\Box\Module {

    use MerapiPanel\Box\Container;
    use MerapiPanel\Box\Module\Entity\Fragment;
    use MerapiPanel\Box\Module\Entity\Module;

    /**
     * Description: Abstract Loader for Container.
     * @author      ilham b <durianbohong@gmail.com>
     * @copyright   Copyright (c) 2022 MerapiPanel
     * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
     * @lastUpdate  2024-02-10
     */

    abstract class AbstractLoader
    {

        protected string $directory;
        abstract function __construct(string $directory);
        abstract function loadModule(string $name, Container $container): Module;
        abstract function loadFragment(string $name, Module|Fragment $parent): Fragment|false;
        abstract function initialize(Container $container): void;
    }
}
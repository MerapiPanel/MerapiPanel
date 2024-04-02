<?php
namespace MerapiPanel\Module\Client;

use MerapiPanel\Box\Module\Entity\Proxy;
use MerapiPanel\Box\Module\ModuleLoader;
use MerapiPanel\Box\Module\Entity\Fragment;
use MerapiPanel\Box\Module\Entity\Module;
use Symfony\Component\Filesystem\Path;

class ClientModuleLoader extends ModuleLoader
{

    protected string $classNamePrefix = "\\Client\\Module";

    public function __construct(string $directory)
    {
        parent::__construct($directory);
    }

    public function initialize(\MerapiPanel\Box\Container $container): void
    {

        foreach (glob(Path::join($this->directory, "*"), GLOB_ONLYDIR) as $dirname) {

            $module = basename($dirname);
            if (isset($container->$module)) {
                unset($container->$module);
            }
        }

        parent::initialize($container);
    }

    function loadFragment(string $name, Fragment|Module $parent): false|Fragment
    {
        if ($parent instanceof Fragment) {

            if (!class_exists($parent->resolveClassName($name))) {
                if (is_dir($parent->resolvePath($name))) {
                    return new Fragment($name, $parent);
                } elseif (is_file($parent->resolvePath($name) . ".php")) {
                    require_once $parent->resolvePath($name) . ".php";
                    return new Proxy($name, $parent);
                }
                return false;
            }

            return new Proxy($name, $parent);

        } else if ($parent instanceof Module) {
            if (!class_exists($parent->namespace . '\\' . $name)) {
                if (is_dir(Path::join($parent->path, $name))) {
                    return new Fragment($name, $parent);
                } elseif (is_file(Path::join($parent->path, $name) . ".php")) {
                    require_once Path::join($parent->path, $name) . ".php";
                    return new Proxy($name, $parent);
                }
                return false;
            }
            return new Proxy($name, $parent);
        }

        return false;
    }
}
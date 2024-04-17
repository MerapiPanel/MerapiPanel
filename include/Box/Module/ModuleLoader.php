<?php
namespace MerapiPanel\Box\Module {

    use Exception;
    use MerapiPanel\Box\Module\AbstractLoader;
    use MerapiPanel\Box\Container;
    use MerapiPanel\Box\Module\Entity\Module;
    use MerapiPanel\Box\Module\Entity\Fragment;
    use MerapiPanel\Box\Module\Entity\Proxy;
    use MerapiPanel\Utility\Http\Request;
    use Symfony\Component\Filesystem\Path;
    use Throwable;

    class ModuleLoader extends AbstractLoader
    {

        protected string $directory;
        protected string $classNamePrefix = "\\MerapiPanel\\Module";


        public function __construct(string $directory)
        {
            $this->directory = $directory;
        }


        public function initialize(Container $container): void
        {
            $this->registerController($container);
        }


        public final function registerController($container)
        {

            $access = ['guest'];
            if (isset($_ENV['__MP_ADMIN__']['prefix'])) {
                if (strpos(Request::getInstance()->getPath(), $_ENV['__MP_ADMIN__']['prefix']) === 0) {
                    $access[] = "admin";
                }
            }

            foreach (glob(Path::join($this->directory, "*"), GLOB_ONLYDIR) as $dirname) {
                $module = basename($dirname);

                /**
                 * @var Fragment $controller
                 */
                if ($controller = $container->$module->Controller) {

                    foreach ($access as $accessName) {
                        $accessName = ucfirst($accessName);
                        if ($controller->$accessName) {
                            try {
                                $controller->$accessName->register();
                            } catch (Throwable $e) {
                                 error_log("Unable to register controller: $module, " . $e->getMessage());
                            }
                        }
                    }
                }
            }

        }


        function loadFragment(string $name, Module|Fragment $parent): Fragment|null
        {
            if ($parent instanceof Fragment) {

                if (!class_exists($parent->resolveClassName($name))) {

                    if (file_exists($parent->resolvePath($name))) {
                        return new Fragment($name, $parent);
                    }

                    return null;
                }

                return new Proxy($name, $parent);

            } else if ($parent instanceof Module) {

                if (!class_exists($parent->namespace . '\\' . $name)) {
                    if (file_exists(Path::join($parent->path, $name))) {
                        return new Fragment($name, $parent);
                    }
                    return null;
                }
                return new Proxy($name, $parent);
            }

            return null;
        }


        function loadModule(string $name, Container $container): Module
        {

            $path = Path::join($this->directory, $name);
            if (!file_exists($path)) {
                throw new Exception("Module not found: $name");
            }
            return new Module($container, [
                "namespace" => $this->classNamePrefix . "\\$name",
                "path" => $path,
            ]);

        }
    }
}
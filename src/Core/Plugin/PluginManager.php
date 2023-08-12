<?php

namespace il4mb\Mpanel\Core\Plugin;

use il4mb\Mpanel\Application;
use ReflectionClass;

class PluginManager
{

    
    protected array $plugins = [];
    protected array $erros   = [];
    private Application $app;





    /**
     * Initializes a new instance of the class.
     *
     * @param Application $app The application object.
     */
    public function __construct(Application $app)
    {

        $this->app = $app;

    }



    

    /**
     * Adds an error to the list of errors.
     *
     * @param array $error An array containing the error, file, and code.
     * @throws Exception
     * @return void
     */
    public function addError(array $error = [
        'error' => '',
        'file' => '',
        'code' => '',
    ]): void
    {

        $this->erros[] = $error;

    }




    /**
     * Retrieves the errors stored in the class.
     *
     * @return array The array containing the errors.
     */
    public function getErros(): array
    {

        return $this->erros;

    }




    
    /**
     * Registers a plugin.
     *
     * @param PluginInterface $plugin The plugin to be registered.
     * @throws None
     * @return void
     */
    public function register(PluginInterface $plugin): void
    {

        $this->plugins[$plugin->getName()] = $plugin;

    }




    /**
     * Get the plugins.
     *
     * @return array The array of plugins.
     */
    public function getPlugins(): array
    {

        return $this->plugins;
        
    }





    /**
     * Enable a plugin.
     *
     * @param string $name The name of the plugin to enable.
     * @throws Some_Exception_Class If the plugin does not exist.
     * @return void
     */
    public function enablePlugin(string $name): void
    {

        if (isset($this->plugins[$name])) 
        {

            $this->plugins[$name]->enable();

        }

    }




    
    /**
     * Disables a plugin.
     *
     * @param string $name The name of the plugin to disable.
     * @return void
     */
    public function disablePlugin(string $name): void
    {

        if (isset($this->plugins[$name])) 
        {

            $this->plugins[$name]->disable();

        }

    }





    /**
     * Configures a plugin with the given name and configuration.
     *
     * @param string $name The name of the plugin.
     * @param array $config The configuration for the plugin.
     * @throws Exception If the plugin is not found.
     * @return void
     */
    public function configurePlugin(string $name, array $config): void
    {

        if (isset($this->plugins[$name])) 
        {

            $this->plugins[$name]->configure($config);

        }

    }




    
    /**
     * Runs all the plugins.
     *
     * @throws void
     */
    public function runPlugins(): void
    {

        foreach ($this->plugins as $plugin) 
        {

            $plugin->run();

        }

    }




    
    /**
     * Retrieves the array of registered plugins.
     *
     * @return array The array of registered plugin names.
     */
    public function getRegisteredPlugins(): array
    {

        $registeredPlugins = [];

        foreach ($this->plugins as $plugin) 
        {

            $reflection = new ReflectionClass($plugin);

            if ($reflection->implementsInterface(PluginInterface::class)) 
            {

                $registeredPlugins[] = $reflection->getName();

            }

        }

        return $registeredPlugins;

    }



    

    /**
     * Recursively discovers and registers plugins from a given directory.
     *
     * @param string $pluginsDirectory The directory to search for plugins.
     * @throws Some_Exception_Class Description of exception
     * @return void
     */
    public function discoverAndRegisterPluginsRecursive($pluginsDirectory)
    {

        $pluginPaths = glob($pluginsDirectory . '/*/index.php', GLOB_NOSORT | GLOB_NOESCAPE);

        foreach ($pluginPaths as $pluginPath) 
        {

            $pluginName  = basename(dirname($pluginPath));
            $pluginClass = 'il4mb\\Mpanel\\Plugins\\' . $pluginName;

            if (class_exists($pluginClass)) 
            {

                $plugin = new $pluginClass($this->app);
                $this->app->getPluginManager()->register($plugin);

            } 
            elseif(is_file($pluginPath))
            {

                include $pluginPath;

                if (class_exists($pluginClass)) 
                {

                    $plugin = new $pluginClass($this->app);
                    $this->app->getPluginManager()->register($plugin);

                }

            }

        }

    }
    
}

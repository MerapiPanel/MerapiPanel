<?php

namespace il4mb\Mpanel\TemplateEngine\Adapter;

use Error;
use il4mb\Mpanel\Application;
use il4mb\Mpanel\TemplateEngine\TemplateEngine;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twigg implements TemplateEngine
{


    protected $twig;
    protected $loader;
    protected $templatePath;
    protected $environmentOptions;
    protected $template;
    protected $app;



    /**
     * Constructs a new instance of the class.
     *
     * @param string $templatePath The path to the template.
     * @param array $environmentOptions The environment options.
     */
    public function __construct(Application $app, string $templatePath, array $environmentOptions = [])
    {

        $this->app                = $app;
        $this->environmentOptions = $environmentOptions;
        $this->loader             = new FilesystemLoader($this->templatePath);
        $this->twig               = new Environment($this->loader, $this->environmentOptions);

        $this->setTemplatePath($templatePath);

        $this->app->getEventSystem()->fire(self::ON_CONSTRUCT, [$this]);

    }




    /**
     * Sets the template for the object.
     *
     * @param string $template The template to be set.
     * @throws None If an error occurs while setting the template.
     * @return void
     */
    public function setTemplate(string $template): void
    {

        $this->template = $template;

        $this->app->getEventSystem()->fire(self::ON_SET_TEMPLATE, [$this]);

    }




    /**
     * Retrieves the template associated with this object.
     *
     * @throws None If the event system fails to fire the ON_GET_TEMPLATE event.
     * @return string The template associated with this object.
     */
    public function getTemplate(): string
    {

        $this->app->getEventSystem()->fire(self::ON_GET_TEMPLATE, [$this]);

        return $this->template;

    }




    /**
     * Sets the environment for the class.
     *
     * @param Environment $environment The environment object.
     * @throws Exception Exception thrown when setting the environment fails.
     * @return void
     */
    public function setEnvironment(Environment $environment): void
    {

        $this->twig = $environment;

        $this->app->getEventSystem()->fire(self::ON_SET_ENVIRONMENT, [$this]);

    }





    /**
     * Retrieves the environment.
     *
     * @return Environment The environment object.
     */
    public function getEnvironment(): Environment
    {

        $this->app->getEventSystem()->fire(self::ON_GET_ENVIRONMENT, [$this]);

        return $this->twig;

    }




    /**
     * Sets the template path for the object.
     *
     * @param string $templatePath The new template path.
     * @throws Exception Description of exception (if applicable).
     * @return void
     */
    public function setTemplatePath(string $templatePath): void
    {

        if(strpos(PHP_OS, "WIN") === 0) 
        {

            $templatePath = str_replace("\\", "/", $templatePath);
            
        }
        
        $this->templatePath = $templatePath;
        $this->loader       = new FilesystemLoader($this->templatePath);

        $this->twig->setLoader($this->loader);
        $this->app->getEventSystem()->fire(self::ON_SET_TEMPLATE_PATH, [$this]);

    }




    /**
     * Retrieves the template path for the PHP function.
     *
     * @return string The template path.
     */
    public function getTemplatePath(): string
    {

        $this->app->getEventSystem()->fire(self::ON_GET_TEMPLATE_PATH, [$this]);

        return $this->templatePath;
    }




    /**
     * Set the environment options for the class.
     *
     * @param array $environmentOptions The environment options to set.
     * @throws void
     * @return void
     */
    public function setEnvironmentOptions(array $environmentOptions): void
    {

        $this->environmentOptions = $environmentOptions;
        $this->twig               = new Environment($this->loader, $this->environmentOptions);

        $this->app->getEventSystem()->fire(self::ON_SET_ENVIRONMENT_OPTIONS, [$this]);
    }




    /**
     * Retrieves the environment options.
     *
     * @return array The environment options.
     */
    public function getEnvironmentOptions(): array
    {

        $this->app->getEventSystem()->fire(self::ON_GET_ENVIRONMENT_OPTIONS, [$this]);

        return $this->environmentOptions;
    }




    /**
     * Renders the template with the provided data.
     *
     * @param array $data The data to pass to the template.
     * @throws Error If no template is found.
     * @return string The rendered template.
     */
    public function render(array $data = []): string
    {

        $root = getenv("DOCUMENT_ROOT") ?? $_SERVER['DOCUMENT_ROOT'];
        if(strpos(PHP_OS, "WIN") === 0) {
            $root = str_replace("\\", "/", $root);
        }

        $variables = [
            "template" => [
                "path" => [
                    "absolute" => $this->getTemplatePath(),
                    "relative" => str_replace($root, "", $this->getTemplatePath())
                ]
            ]
        ];

        $variables = array_merge($variables, $data);
        $template = $this->getTemplate();


        $this->app->getEventSystem()->fire(self::ON_RENDER, [$this]);

        // Check if a custom error template exists with .twig extension
        if ($this->templateExists($template . '.twig')) {

            $variables['template']['name'] = $template;

            // Render the .twig template with the provided data
            return $this->twig->render($template . '.twig', $variables);

        }

        // Check if a custom error template exists with .html extension
        if ($this->templateExists($template . '.html')) {

            $variables['template']['name'] = $template;

            // Render the .html template with the provided data
            return $this->twig->render($template . '.html', $variables);

        }

        throw new Error("Error rendering template: No template $template found", 500);
        // Fallback to the default error template (error.twig) if no custom template is found

    }




    /**
     * Adds a global variable to the Twig template engine.
     *
     * @param string $name The name of the global variable.
     * @param mixed $value The value of the global variable.
     * @throws Some_Exception_Class A description of the exception that can be thrown.
     * @return void
     */
    public function addGlobal(string $name, $value): void
    {

        $this->twig->addGlobal($name, $value);

        $this->app->getEventSystem()->fire(self::ON_ADD_GLOBALVAR, [$this]);

    }





    /**
     * Retrieves the global variables used in the PHP function.
     *
     * @return array The global variables.
     */
    public function getGlobals(): array
    {

        $this->app->getEventSystem()->fire(self::ON_GET_GLOBALVAR, [$this]);

        return $this->twig->getGlobals();

    }




    /**
     * Retrieves a filter by its name.
     *
     * @param string $name The name of the filter.
     * @throws None If an error occurs while retrieving the filter.
     * @return mixed The retrieved filter.
     */
    public function getFilter(string $name)
    {

        $this->app->getEventSystem()->fire(self::ON_GET_FILTER, [$this]);

        return $this->twig->getFilter($name);

    }



    public function addFilter($filter): void
    {

        $this->twig->addFilter($filter);

        $this->app->getEventSystem()->fire(self::ON_ADD_FILTER, [$this]);

    }




    /**
     * @param string $name
     * @return \Twig\TwigFunction
     */
    public function getFunction(string $name): \Twig\TwigFunction
    {

        $this->app->getEventSystem()->fire(self::ON_GET_FUNCTION, [$this]);

        return $this->twig->getFunction($name);

    }




    /**
     * Adds a function to the Twig environment.
     *
     * @param mixed $function The function to be added.
     * @throws Exception Description of the exception that may be thrown.
     * @return void
     */
    public function addFunction($function): void
    {

        $this->twig->addFunction($function);

        $this->app->getEventSystem()->fire(self::ON_ADD_FUNCTION, [$this]);

    }





    /**
     * Retrieves a test by name.
     *
     * @param string $name The name of the test.
     * 
     * @return Test|null The test object, or null if not found.
     */
    public function getTest(string $name)
    {

        $this->app->getEventSystem()->fire(self::ON_GET_TEST, [$this]);

        return $this->twig->getTest($name);

    }





    /**
     * Adds a test to the test collection.
     *
     * @param mixed $test The test to be added.
     * @return void
     */
    public function addTest($test): void
    {

        $this->twig->addTest($test);

        $this->app->getEventSystem()->fire(self::ON_ADD_TEST, [$this]);

    }




    /**
     * Check if a template exists.
     *
     * @param string $template The name of the template.
     * @return bool Returns true if the template exists, false otherwise.
     */
    protected function templateExists(string $template): bool
    {

        return is_file(rtrim($this->templatePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $template);
        
    }
}

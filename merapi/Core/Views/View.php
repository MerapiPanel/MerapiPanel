<?php

namespace MerapiPanel\Core\Views;

use Exception;
use MerapiPanel\Box;
use MerapiPanel\Core\Proxy;
use MerapiPanel\Core\Views\Loader;
use MerapiPanel\Core\Section;
use MerapiPanel\Core\Views\Component\ProcessingComponent;
use Twig\Extension\AbstractExtension;
use Twig\Loader\ArrayLoader;
use Twig\TemplateWrapper;

class View
{
    protected $twig;
    protected $loader;
    protected $variables = [];
    private $file;
    public static $renderedFile = null;

    public function __construct(array|ArrayLoader $loader = [])
    {

        if (gettype($loader) == "array") {
            $loader = array_merge([realpath(__DIR__ . "/../../base/views")], $loader);
            $this->loader = new Loader($loader);
        } else {
            $this->loader = $loader;
        }

        $this->twig = new Twig(new ProcessingComponent($this->loader), ['cache' => false]);

        $this->twig->enableDebug();

        // Load our own Twig extensions
        $files = glob(__DIR__ . "/extension/*.php");
        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = trim(__NAMESPACE__ . "\\Extension\\" . ucfirst($file_name));

            if (class_exists($className)) {

                $this->twig->addExtension(new $className(Box::Get($this)));
            }
        }

        $this->addGlobal("request", Proxy::Real(Box::utility_http_request()));
        $this->addGlobal("api", new ApiServices());
    }


    function getLoader()
    {
        return $this->loader;
    }



    function addGlobal($name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }



    private TemplateWrapper $wrapper;
    public function load($file)
    {

        $this->file = $file;
        $this->wrapper = $this->twig->load($this->file);
        self::$renderedFile = $this->wrapper->getSourceContext()->getPath();

        return $this->wrapper;
    }


    private function addVariable(array $data = [])
    {
        $this->variables = array_merge($this->variables, $data);
    }



    /**
     * Get the template.
     *
     * @return mixed
     * @throws Exception Please load view first before get template
     */
    public function getTemplate()
    {
        if (!isset ($this->wrapper))
            throw new Exception("Please load view first before get template");
        return $this->wrapper;
    }


    function getTwig(): Twig
    {
        return $this->twig;
    }

    public function __toString()
    {

        if (!isset ($this->wrapper))
            return "Unprepare wrapper or unready view";
        return $this->wrapper->render($this->variables);
    }






    private static $instance = null;

    private static function getInstance(): View
    {
        if (self::$instance == null)
            self::$instance = new View();
        return self::$instance;
    }

    public static function newInstance($loader = []): View
    {
        return new View($loader);
    }




    public static function AddSection(Section $section)
    {
        $e = self::getInstance();
        $e->twig->addGlobal($section->getName(), $section);
    }





    public static function AddExtension(AbstractExtension $extension)
    {
        self::getInstance()->twig->addExtension($extension);
    }





    public static function render(string $file, array $data = []): mixed
    {

        $view = self::getInstance();
        $twig = $view->getTwig();

        if ($twig->getLoader()->exists($file)) {

            $view->load($file);

        } else {

            $backtrace = debug_backtrace();
            $caller = $backtrace[0]; // Index 0 is the current function, index 1 is its caller
            $module_name = false;
            $accessName = "guest";

            if (isset ($caller['file'])) {

                $file_path = $caller['file'];

                $module_index = strpos($file_path, "\\Module");
                if ($module_index !== false) {
                    $module_path = substr($file_path, 0, $module_index + strlen("\\Module"));
                    $endfix = str_replace($module_path, "", $file_path);
                    $module_name = array_values(array_filter(explode("\\", $endfix)))[0];
                }

                if (in_array(strtolower(basename($caller['file'], ".php")), array_keys($_ENV["__MP_ACCESS__"]))) {
                    $accessName = strtolower(basename($caller['file'], ".php"));
                }
            }

            if ($accessName !== "guest" && $twig->getLoader()->exists("@" . strtolower($module_name) . "/" . $accessName . "/" . ltrim($file, "\\/"))) {
                // if (class_exists("\\MerapiPanel\\Module\\{$module_name}\\Views\\{$accessName}\\Api")) {
                //     $view->addGlobal("api", new Proxy("\\MerapiPanel\\Module\\{$module_name}\\Views\\{$accessName}\\Api", [$view]));
                // }
                $view->load(("@" . strtolower($module_name) . "/" . $accessName . "/" . ltrim($file, "\\/")));
            } else {
                // if (class_exists("\\MerapiPanel\\Module\\{$module_name}\\Views\\Api")) {
                //     $view->addGlobal("api", new Proxy("\\MerapiPanel\\Module\\{$module_name}\\Views\\Api", [$view]));
                // }
                $view->load(("@" . strtolower($module_name) . "/" . ltrim($file, "\\/")));
            }
        }

        $view->addVariable($data);
        Box::event()->notify([self::class, "render"], $view);
        return $view;
    }
}

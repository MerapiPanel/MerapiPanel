<?php

namespace MerapiPanel\Views;

use Exception;
use MerapiPanel\Views\Loader;
use MerapiPanel\Utility\Http\Request;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Filesystem\Path;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;
use Twig\TemplateWrapper;

class View
{

    protected $twig;
    protected $loader;
    protected $variables = [];
    private $file;
    protected Intl $intl;
    protected $lang = false;
    private TemplateWrapper $wrapper;


    public function __construct(array|ArrayLoader $loader = [])
    {

        if (gettype($loader) == "array") {
            $loader = array_merge([Path::join($_ENV["__MP_APP__"], "Buildin", "Views")], $loader);
            $this->loader = new Loader($loader);
        } else {
            $this->loader = $loader;
        }

        $this->intl = new Intl();
        $this->twig = new Twig($this->loader, [
            "callback" => function ($name) {
                // error_log($name);
            }
        ]);

        $this->twig->enableDebug();
        $this->twig->AddExtension(new TranslationExtension($this->intl));
        $this->lang = $this->intl->getLocale();

        // Load our own Twig extensions
        $files = glob(__DIR__ . "/Extension/*.php");

        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = trim(__NAMESPACE__ . "\\Extension\\" . ucfirst($file_name));

            if (class_exists($className)) {

                $this->twig->addExtension(new $className($this));
            }
        }


        $this->addGlobal("request", Request::getInstance());
        $this->addGlobal("lang", $this->lang);
        $this->addGlobal("api", new ApiServices());
    }





    function getLoader(): LoaderInterface
    {
        return $this->twig->getLoader();
    }




    function setLoader($loader)
    {
        $this->twig->setLoader($loader);
    }



    function addGlobal($name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }





    public function load(string $file)
    {

        $this->file = $file;
        $this->wrapper = $this->twig->load($this->file);
        $this->wrapper->getSourceContext()->getPath();

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
        if (!isset($this->wrapper))
            throw new Exception("Please load view first before get template");
        return $this->wrapper;
    }




    function getTwig(): Twig
    {
        return $this->twig;
    }




    public function __toString()
    {

        if (!isset($this->wrapper))
            return "Unprepare wrapper or unready view";
        return $this->wrapper->render($this->variables);
    }






    private static $instance = null;


    public static function getInstance(): View
    {
        if (self::$instance == null)
            self::$instance = new View();
        return self::$instance;
    }




    public static function newInstance($loader = []): View
    {
        return new View($loader);
    }





    /**
     * Render function to generate and display the specified file with optional data and language.
     *
     * @param string $file The file to render.
     * @param array $data An array of data to pass to the rendering function. Default is an empty array.
     * @param mixed $lang The language to render the file in. Default is null.
     * @throws Exception View file not found: $template
     * @return mixed The rendered file content.
     */
    public static function render(string $file, array $data = [], $lang = null): mixed
    {


        $backtrace = debug_backtrace();
        $caller = $backtrace[0];


        // set locale
        if ($lang && $lang != self::getInstance()->lang) {
            self::getInstance()->intl->setLocale($lang);
        }


        if (isset($caller['file'])) {

            // find the module name
            $file_path = $caller['file'];
            if (PHP_OS == "WINNT") {
                preg_match("/\\\Module\\\(.*?)\\\.*\\\(.*)\\..*/im", $file_path, $matches);
            } else {
                preg_match("/\\/Module\\/(.*?)\\/.*\\/(.*)\\..*/im", $file_path, $matches);
            }


            if (isset($matches[1], $matches[2])) { // is a module

                $module_name = strtolower($matches[1]);
                $template = "@$module_name/$file";

                if ($matches[2] !== "guest" && in_array(strtolower($matches[2]), array_keys($_ENV["__MP_ACCESS__"]))) {
                    if (self::getInstance()->getLoader()->exists("@$module_name/$matches[2]/$file")) {
                        $template = "@$module_name/$matches[2]/$file";
                    }
                }

                if (!self::getInstance()->getLoader()->exists($template))
                // if template not found in current loader
                {
                   
                    if (self::getInstance()->getLoader()->exists($file)) {
                        $template = "$file";
                    } else {
                        throw new Exception("View file not found: $template");
                    }
                }



                // tell intl to scan views folder
                self::getInstance()->intl->scanResources(
                    isset($module_path)
                    ? Path::join($module_path, "Views")
                    : Path::join(
                            substr($file_path, 0, (strpos($file_path, $matches[1]) + strlen($matches[1]))),
                            "Views"
                        )
                );

                self::getInstance()->intl->onViewFileLoaded(
                    is_file($template)
                    ? $template
                    : Path::join(
                            substr($file_path, 0, (strpos($file_path, $matches[1]) + strlen($matches[1]))),
                            "Views",
                            (strtolower($matches[2]) !== "guest" ? $matches[2] : ""),
                            $file
                        )
                );
                return self::getInstance()->load($template)->render($data);
            }

            return self::getInstance()->load($file)->render($data);
        }
    }


}
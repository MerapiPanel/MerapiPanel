<?php

namespace MerapiPanel\Views;

use Exception;
use MerapiPanel\Views\Loader;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Views\Abstract\Extension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Filesystem\Path;
use Throwable;
use Twig\Error\RuntimeError;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;
use Twig\TemplateWrapper;


class ViewException extends Extension
{
    private static $templates = [];

    function fn_error_template($code, $template)
    {
        self::$templates[$code] = $template;
    }

    function fn_throw($message, $code = 0)
    {
        throw new Exception($message ?? 'Unknown error!', $code);
    }

    public static function send(Throwable $t)
    {
        $template = false;
        if (isset(self::$templates[$t->getCode()])) {
            $template = self::$templates[$t->getCode()];
        } else if (isset(self::$templates["default"])) {
            $template = self::$templates["default"];
        } else {
            throw $t;
        }
        $view = View::getInstance();
        return $view->load($template)->render([
            "code" => $t->getCode(),
            "message" => $t->getMessage(),
            "previus" => $t->getPrevious(),
            "trace" => $t->getTrace(),
            "file" => $t->getFile(),
            "line" => $t->getLine()
        ]);
    }
}

class View
{
    protected $twig;
    protected $loader;
    protected $variables = [];
    private $file;
    protected Intl $intl;
    protected $lang = false;
    private TemplateWrapper|null $wrapper = null;
    private static ViewException $viewException;


    public function __construct(array|ArrayLoader $loader = [])
    {

        if (gettype($loader) == "array") {
            $loader = array_merge([Path::join($_ENV["__MP_APP__"], "buildin", "Views")], $loader);
            $this->loader = new Loader($loader);
        } else {
            $this->loader = $loader;
        }

        $this->intl = new Intl();
        $this->lang = $this->intl->getLocale();

        $this->twig = new Twig($this->loader);
        $this->twig->enableDebug();
        $this->twig->AddExtension(new TranslationExtension($this->intl));

        // Load our own Twig extensions
        $files = glob(__DIR__ . "/Extension/*.php");

        foreach ($files as $file) {

            $file_name = pathinfo($file, PATHINFO_FILENAME);
            $className = trim(__NAMESPACE__ . "\\Extension\\" . ucfirst($file_name));

            if (class_exists($className)) {

                $this->twig->addExtension(new $className($this));
            }
        }
        self::$viewException = new ViewException();
        $this->twig->addExtension(self::$viewException);


        $this->addGlobal("http", [
            "request" => Request::getInstance()
        ]);
        $this->addGlobal("_lang", $this->lang);
        $this->addGlobal("_box", new ApiServices());
        $this->addGlobal('__env__', $_ENV);
    }



    function getIntl(): Intl
    {
        return $this->intl;
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




    function getTwig(): Twig|string
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


        try {

            $backtrace = debug_backtrace();
            $caller = $backtrace[0];

            // set locale
            if ($lang && $lang != self::getInstance()->lang) {
                self::getInstance()->intl->setLocale($lang);
            }


            if (isset($caller['file'])) {

                // find the module name
                $caller_file_path = $caller['file'];
                if (PHP_OS == "WINNT") {
                    preg_match("/\\\Module\\\(.*?)\\\.*/im", $caller_file_path, $matches);
                } else {
                    preg_match("/\\/Module\\/(.*?)\\/.*/im", $caller_file_path, $matches);
                }

                if (isset($matches[1])) { // is a module

                    if (!preg_match('/\.\w+$/', $file)) {
                        $file = $file . ".twig";
                    }

                    $module_name = strtolower($matches[1]);
                    $template = "@$module_name/$file";

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
                                substr($caller_file_path, 0, (strpos($caller_file_path, $matches[1]) + strlen($matches[1]))),
                                "Views"
                            )
                    );

                    // tell intl to load the intl template
                    self::getInstance()->intl->onViewFileLoaded(self::getInstance()->getLoader()->getSourceContext($template)->getPath());

                    // render the template
                    return self::getInstance()->load($template)->render($data);
                }

                return self::getInstance()->load($file)->render($data);
            }
        } catch (Throwable $t) {
            if ($t instanceof RuntimeError) {
                return self::$viewException->send($t->getPrevious() ?? $t);
            }
            return self::$viewException->send($t);
        }
    }
}

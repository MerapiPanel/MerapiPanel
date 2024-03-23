<?php

namespace MerapiPanel\Utility\Http;

use MerapiPanel\Core\Abstract\MagicAccess;

class Request
{

    protected RequestHeader $header;
    protected RequestQuery $query;
    protected RequestForm $form;
    protected $method;
    protected $path;
    private static $instance;

    /**
     * Constructs a new instance of the class.
     *
     * @return void
     */
    private function __construct()
    {

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->header = new RequestHeader();
        $this->query = new RequestQuery();
        $this->form = new RequestForm();

    }


    public static function getInstance() : Request
    {

        if (!isset (self::$instance)) {
            self::$instance = new Request();
        }
        return self::$instance;
    }




    public function setParams($routeParams)
    {

        foreach (array_keys($routeParams) as $name) {
            $this->query->$name = $routeParams[$name];
        }
    }

    /**
     * Retrieves the method value.
     *
     * @return mixed The method value.
     */
    public function getMethod()
    {

        return $this->method;
    }




    /**
     * Retrieves the path.
     *
     * @return string The path.
     */
    public function getPath()
    {

        return $this->path;
    }




    /**
     * Retrieves the query parameters.
     *
     * @return RequestQuery The query parameters.
     */
    public function getQuery()
    {

        return $this->query;
    }





    /**
     * Retrieves the value of a specified header.
     *
     * @param string $headerName The name of the header to retrieve.
     * @return mixed|null The value of the header if it exists, or null otherwise.
     */
    public function getHeader()
    {

        // Check if the header exists in the headers array
        return $this->header;
    }




    /**
     * http method use for return header value
     */
    public function http($name): string|false
    {
        $name = $this->camelCaseToKebabCase($name);
        if (!isset ($this->header->$name))
            return false;
        return $this->header->$name;
    }




    /**
     * Magic method use for return from value
     */
    function __call($name, $arguments): string|false
    {

        $name = $this->camelCaseToKebabCase($name);
        if (!isset ($this->form->$name)) {
            if (empty ($arguments))
                return $this->__get($name);
            return false;
        }
        return $this->form->$name;
    }




    /**
     * Magic method use fro return get query value
     * if not set will forward to __get
     */
    public function __get($name): string|false
    {

        // error_log("From Request Traying Get : " . $name);
        $name = $this->camelCaseToKebabCase($name);
        if (!isset ($this->query->$name))
            return false;
        return $this->query->$name;
    }




    /**
     * description: transform camel case to kebab case example : FooBar => foo-bar
     * if not set return false
     */
    function camelCaseToKebabCase($string)
    {
        // First, split the string at every uppercase letter.
        $words = preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);

        // Convert the array back to a string, with words separated by a hyphen and converted to lowercase.
        $kebabCase = strtolower(implode('-', $words));

        return $kebabCase;
    }


    // public function __toJson()
    // {

    //     return [
    //         'headers' => $this->header,
    //         'body' => $this->body,
    //         'params' => $this->params,
    //         'query' => $this->query,
    //         'method' => $this->method,
    //         'path' => $this->path,
    //     ];
    // }
}





class RequestHeader extends MagicAccess
{
    private $stack_data = [];
    public function __construct()
    {

        foreach ($_SERVER as $name => $value) {

            if (strpos($name, 'HTTP_') === 0) {

                $headerName = str_replace('HTTP_', '', $name);
                unset($_SERVER['HTT_' . $headerName]);

                $headerName = str_replace('_', '-', $headerName);
                $headerName = strtolower($headerName);
                $this->stack_data[$headerName] = $value;
            }
        }
    }

    public function __set($key, $value)
    {
        $this->stack_data[$key] = $value;
    }
    public function __get($key)
    {
        return $this->stack_data[$key];
    }

    public function __isset($key)
    {
        return isset ($this->stack_data[$key]);
    }

    public function __unset($key)
    {
        unset($this->stack_data[$key]);
    }
}




class RequestQuery extends MagicAccess
{
    private $stack_data = [];
    public function __construct()
    {
        foreach ($_GET as $key => $value) {
            $this->stack_data[$key] = $value;
            unset($_GET[$key]);
        }
    }

    public function __set($key, $value)
    {
        $this->stack_data[$key] = $value;
    }
    public function __get($key)
    {
        return $this->stack_data[$key];
    }

    public function __isset($key)
    {
        return isset ($this->stack_data[$key]);
    }

    public function __unset($key)
    {
        unset($this->stack_data[$key]);
    }
}





class RequestForm extends MagicAccess
{


    private $stack_data = [];


    public function __construct()
    {

        if (strtolower($_SERVER['REQUEST_METHOD']) == "post") {
            $this->dataFromPost();
        } else
            $this->dataFromPut();
    }


    private function dataFromPost()
    {
        foreach ($_POST as $key => $value) {
            $this->stack_data[$key] = $value;
            unset($_POST[$key]);
        }
    }




    private function dataFromPut()
    {

        // Read incoming data
        $input = file_get_contents('php://input');

        // Attempt to get the boundary if it's a multipart/form-data request
        $boundary = self::getBoundary();

        if ($boundary) {
            // Split content by boundary and get rid of last -- element
            $a_blocks = preg_split("/-+$boundary/", $input);
            array_pop($a_blocks);

            // Loop data blocks
            foreach ($a_blocks as $id => $block) {
                if (empty ($block)) {
                    continue;
                }

                // Parse uploaded files
                if (strpos($block, 'application/octet-stream') !== FALSE) {
                    // Match "name", then everything after "stream" (optional) except for prepending newlines
                    preg_match('/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s', $block, $matches);
                } else {
                    // Match "name" and optional value in between newline sequences
                    preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                }
                if (!empty ($matches[1])) {
                    $this->stack_data[$matches[1]] = $matches[2];
                }
            }
        }
    }


    /**
     * Extracts the boundary from the Content-Type header.
     *
     * @return string|null The boundary string, if found; otherwise, null.
     */
    private static function getBoundary()
    {
        if (!empty ($_SERVER['CONTENT_TYPE'])) {
            preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
            if (isset ($matches[1])) {
                return $matches[1];
            }
        }
        return null;
    }





    public function __set($key, $value)
    {
        $this->stack_data[$key] = $value;
    }
    public function __get($key)
    {
        return $this->stack_data[$key];
    }

    public function __isset($key)
    {
        return isset ($this->stack_data[$key]);
    }

    public function __unset($key)
    {
        unset($this->stack_data[$key]);
    }
}

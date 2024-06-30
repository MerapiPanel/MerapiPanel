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
    private RequestURI $uri;

    /**
     * Constructs a new instance of the class.
     *
     * @return void
     */
    private function __construct()
    {

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = new RequestURI();
        $this->header = new RequestHeader();
        $this->query = new RequestQuery();
        $this->form = new RequestForm();
    }


    public function getURI(): RequestURI
    {
        return $this->uri;
    }
    public static function getInstance(): Request
    {

        if (!isset(self::$instance)) {
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



    function getForm()
    {
        return $this->form;
    }



    /**
     * Retrieves the value of a specified header.
     *
     * @param string $headerName The name of the header to retrieve.
     * @return mixed|null The value of the header if it exists, or null otherwise.
     */
    public function getHeader(): RequestHeader|null
    {

        // Check if the header exists in the headers array
        return $this->header;
    }




    /**
     * http method use for return header value
     */
    public function http($name = null): mixed
    {
        if ($name == null) {
            return $this->header->__get(null);
        }
        $name = $this->camelCaseToKebabCase($name);
        if (!isset($this->header->$name))
            return false;
        return $this->header->$name;
    }




    /**
     * Magic method use for return from value
     */
    function __call($name, $arguments): mixed
    {

        $name = $this->camelCaseToKebabCase($name);
        if (!isset($this->form->$name)) {
            if (empty($arguments))
                return $this->__get($name);
            return false;
        }
        return $this->form->$name;
    }




    /**
     * Magic method use fro return get query value
     * if not set will forward to __get
     */
    public function __get($name): mixed
    {

        // error_log("From Request Traying Get : " . $name);
        $name = $this->camelCaseToKebabCase($name);
        if (!isset($this->query->$name))
            return '';
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


    static function getClientIP()
    {
        switch (true) {
            case (!empty($_SERVER['HTTP_X_REAL_IP'])):
                return $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])):
                return $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])):
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            default:
                return $_SERVER['REMOTE_ADDR'];
        }
    }


    public static function getUserAgent()
    {
        return self::getInstance()->http('user-agent');
    }
}



class RequestURI
{
    private $url;
    private $protocol;
    private $host;
    private $path;
    private $query;
    private $fragment;

    public function __construct()
    {
        // Check if the current request is using HTTPS
        $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        // Get the protocol
        $this->url = ($is_https ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $this->protocol = parse_url($this->url, PHP_URL_SCHEME);
        $this->host = parse_url($this->url, PHP_URL_HOST);
        $this->path = parse_url($this->url, PHP_URL_PATH);
        $this->query = parse_url($this->url, PHP_URL_QUERY);
        $this->fragment = parse_url($this->url, PHP_URL_FRAGMENT);
    }

    public function addQuery($name, $value)
    {
        // Parse the existing query string into an array
        $queryArray = [];
        if ($this->query) {
            parse_str($this->query, $queryArray);
        }

        // Add the new query parameter
        $queryArray[$name] = $value;

        // Build the new query string
        $this->query = http_build_query($queryArray);

        // Rebuild the URL
        return $this->buildUrl();
    }

    public function setQuery(string|array $query)
    {
        if (is_array($query)) {
            $this->query = http_build_query($query);
        } else {
            $this->query = $query;
        }

        // Rebuild the URL
        return $this->buildUrl();
    }

    public function setPath($path)
    {
        $this->path = $path;

        // Rebuild the URL
        return $this->buildUrl();
    }

    private function buildUrl()
    {
        $this->url = $this->protocol . '://' . $this->host . $this->path;
        if ($this->query) {
            $this->url .= '?' . $this->query;
        }
        if ($this->fragment) {
            $this->url .= '#' . $this->fragment;
        }

        return $this->url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    function __toString()
    {
        return $this->url;
    }
}




class RequestHeader
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
        if ($key == null) {
            return $this->stack_data;
        }
        return $this->stack_data[$key];
    }

    public function __isset($key)
    {
        return isset($this->stack_data[$key]);
    }

    public function __unset($key)
    {
        unset($this->stack_data[$key]);
    }

    public function toArray()
    {
        return $this->stack_data;
    }
}




class RequestQuery implements \ArrayAccess
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
        return isset($this->stack_data[$key]);
    }

    public function __unset($key)
    {
        unset($this->stack_data[$key]);
    }



    function offsetExists(mixed $offset): bool
    {
        return isset($this->stack_data[$offset]);
    }
    function offsetGet(mixed $offset): mixed
    {
        return $this->stack_data[$offset];
    }
    function offsetSet(mixed $offset, mixed $value): void
    {
        $this->stack_data[$offset] = $value;
    }
    function offsetUnset(mixed $offset): void
    {
        unset($this->stack_data[$offset]);
    }

    public function toArray()
    {
        return $this->stack_data;
    }
}





class RequestForm
{


    private $stack_data = [];


    public function __construct()
    {

        if (strtoupper($_SERVER['REQUEST_METHOD']) == "POST") {
            $this->dataFromPost();
        } else {
            $this->dataFromPut();
        }
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
                if (empty($block)) {
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
                if (!empty($matches[1])) {
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
        if (!empty($_SERVER['CONTENT_TYPE'])) {
            preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
            if (isset($matches[1])) {
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
        return isset($this->stack_data[$key]);
    }

    public function __unset($key)
    {
        unset($this->stack_data[$key]);
    }

    public function toArray()
    {
        return $this->stack_data;
    }
}

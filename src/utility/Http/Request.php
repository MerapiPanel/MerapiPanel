<?php

namespace MerapiPanel\Utility\Http;

class Request
{

    protected $method;
    protected $path;
    protected $query;
    protected $headers;
    protected $body;
    protected $params;

    protected $_POST;
    protected $_GET;



    /**
     * Constructs a new instance of the class.
     *
     * @return void
     */
    public function __construct()
    {

        $this->_POST = $_POST;
        $this->_GET  = $_GET;

        $this->method  = $_SERVER['REQUEST_METHOD'];
        $this->path    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query   = $_GET;
        $this->headers = $this->getAllHeaders();
        $this->body    = $this->getRequestBody();
        $this->params  = [];

        // Unset all $_GET parameters
        foreach ($_GET as $key => $value) {
            unset($_GET[$key]);
        }

        // Unset all $_POST parameters
        foreach ($_POST as $key => $value) {
            unset($_POST[$key]);
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
     * @return array The query parameters.
     */
    public function getQueryParams()
    {

        return $this->query;
    }




    /**
     * Retrieves the value of the specified parameter from the query array.
     *
     * @param mixed $paramName The name of the parameter to retrieve.
     * @return mixed|null The value of the parameter if it exists, otherwise null.
     */
    public function getQuery($paramName)
    {

        return isset($this->query[$paramName]) ? $this->query[$paramName] : null;
    }




    /**
     * Retrieves the value of a specified header.
     *
     * @param string $headerName The name of the header to retrieve.
     * @return mixed|null The value of the header if it exists, or null otherwise.
     */
    public function getHeader($headerName)
    {

        // Check if the header exists in the headers array
        return isset($this->headers[$headerName]) ? $this->headers[$headerName] : null;
    }




    /**
     * Retrieves the request body from the input stream.
     *
     * @return array The contents of the request body.
     */
    public function getRequestBody()
    {
        if (!empty($this->_POST)) {

            return $this->_POST;

        } elseif (!empty(file_get_contents('php://input'))) {
            $phpin = [];
            self::parse_raw_http_request($phpin);
            return $phpin;
        }

        return [];
    }


    /**
     * Parses the raw HTTP request data.
     *
     * @param array &$data Reference to the data array to populate with parsed data.
     */
    private static function parse_raw_http_request(array &$data)
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
                    $data[$matches[1]] = $matches[2];
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


    /**
     * Retrieves the parameters of the PHP function.
     *
     * @return array The parameters of the function.
     */
    public function getParams()
    {

        return $this->params;
    }




    /**
     * Sets the parameters for the function.
     *
     * @param array $params An array containing the parameters.
     */
    public function setParams(array $params)
    {

        $this->params = $params;
    }




    /**
     * Retrieves the value of a specified parameter.
     *
     * @param string $paramName The name of the parameter to retrieve.
     * @return mixed The value of the specified parameter, or null if it doesn't exist.
     */
    public function getParam($paramName)
    {

        return isset($this->params[$paramName]) ? $this->params[$paramName] : null;
    }




    /**
     * Retrieves all HTTP headers from the current request.
     *
     * @return array An associative array containing all the HTTP headers.
     */
    protected function getAllHeaders()
    {

        $headers = [];

        foreach ($_SERVER as $name => $value) {

            if (strpos($name, 'HTTP_') === 0) {

                $headerName = str_replace('HTTP_', '', $name);
                $headerName = str_replace('_', '-', $headerName);
                $headerName = strtolower($headerName);

                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }


    function __call($name, $arguments)
    {

        $name = $this->camelCaseToKebabCase($name);

        if (isset($this->query[$name])) {
            return $this->query[$name];
        }

        if (isset($this->params[$name])) {
            return $this->params[$name];
        }

        if (isset($this->body[$name])) {
            return $this->body[$name];
        }

        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
    }


    function camelCaseToKebabCase($string)
    {
        // First, split the string at every uppercase letter.
        $words = preg_split('/(?=[A-Z])/', $string, -1, PREG_SPLIT_NO_EMPTY);

        // Convert the array back to a string, with words separated by a hyphen and converted to lowercase.
        $kebabCase = strtolower(implode('-', $words));

        return $kebabCase;
    }


    public function __toJson()
    {

        return [
            'headers' => $this->headers,
            'body' => $this->body,
            'params' => $this->params,
            'query' => $this->query,
            'method' => $this->method,
            'path' => $this->path,
        ];
    }
}

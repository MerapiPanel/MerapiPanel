<?php

namespace Mp\Module\Utility\Http;

class Request
{

    protected $method;
    protected $path;
    protected $query;
    protected $headers;
    protected $body;
    protected $params;

    

    /**
     * Constructs a new instance of the class.
     *
     * @return void
     */
    public function __construct()
    {

        $this->method  = $_SERVER['REQUEST_METHOD'];
        $this->path    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query   = $_GET;
        $this->headers = $this->getAllHeaders();
        $this->body    = $this->getRequestBody();
        $this->params  = [];
        
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
    public function getQuery($paramName) {

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
     * @return string The contents of the request body.
     */
    public function getRequestBody()
    {

        return file_get_contents('php://input');

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

        foreach ($_SERVER as $name => $value) 
        {

            if (strpos($name, 'HTTP_') === 0) 
            {
                
                $headerName = str_replace('HTTP_', '', $name);
                $headerName = str_replace('_', '-', $headerName);
                $headerName = strtolower($headerName);

                $headers[$headerName] = $value;

            }

        }

        return $headers;

    }
    
}

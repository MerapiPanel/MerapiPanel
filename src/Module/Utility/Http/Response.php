<?php

namespace Mp\Module\Utility\Http;

class Response
{

    protected $content;
    protected $statusCode;
    protected $headers;



    /**
     * Constructs a new instance of the class.
     *
     * @param string|array $content The content of the instance. Default is an empty string.
     * @param int $statusCode The status code of the instance. Default is 200.
     * @param array $headers The headers of the instance. Default is an empty array.
     */
    public function __construct(string|array|object $content = '', int $statusCode = 200, array $headers = [])
    {
        
        $this->content    = $content;
        $this->statusCode = $statusCode;
        $this->headers    = $headers;

    }



    /**
     * Sets the content of the object.
     *
     * @param mixed $content The content to be set.
     * @return void
     */
    public function setContent(mixed $content): void
    {

        $this->content = $content;

    }


    

    /**
     * Retrieves the content of the object.
     *
     * @return string The content of the object.
     */
    public function getContent(): mixed
    {

        return $this->content;

    }



    
    /**
     * Set the status code for this object.
     *
     * @param int $statusCode The status code to set.
     * @return void
     */
    public function setStatusCode(int $statusCode): void
    {

        $this->statusCode = $statusCode;

    }



    
    /**
     * Returns the status code of the PHP function.
     *
     * @return int The status code of the PHP function.
     */
    public function getStatusCode(): int
    {

        return $this->statusCode;

    }




    /**
     * Sets a header value in the headers array.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     * @return void
     */
    public function setHeader(string $name, string $value): void
    {

        $this->headers[$name] = $value;

    }




    
    /**
     * Retrieves the header value associated with the given name.
     *
     * @param string $name The name of the header.
     * @return string|null The value of the header, or null if it doesn't exist.
     */
    public function getHeader(string $name): ?string
    {

        return $this->headers[$name] ?? null;

    }



    
    /**
     * Get the headers.
     *
     * @return array The headers.
     */
    public function getHeaders(): array
    {

        return $this->headers;

    }



    
    /**
     * Sends the HTTP response with the specified status code and headers.
     *
     * @throws Error if there is an error in sending the response.
     * @return void
     */
    public function send(): void
    {

        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) 
        {

            header("$name: $value");

        }

    }


    public function __toString() {

        if(gettype($this->content) !== "string") {
            $this->setHeader("Content-Type", "application/json");
        }

        foreach($this->headers as $name => $value) {
            header("$name: $value");
        }

        return !is_string($this->content) ? json_encode($this->content) : $this->content;
    }

}

<?php

namespace MerapiPanel\Utility\Http;

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

        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
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
     * @throws \Exception if there is an error in sending the response.
     * @return string
     */
    public function send(): string
    {

        if (!headers_sent()) {
            header_remove();
        }

        if (gettype($this->content) !== "string") {
            $this->setHeader("Content-Type", "application/json");
        }

        header("X-Powered-By: MerapiPanel 1.0.0; PHP " . PHP_VERSION);

        self::http_response_code($this->statusCode);

        if (isset ($this->headers['location'])) {
            $redirect = $this->headers['location'];
            unset($this->headers['location']);
        }

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }


        if (isset ($redirect)) {
            header("Location: $redirect");
        }

        return !is_string($this->content) ? json_encode($this->content) : $this->content;
    }



    private static function http_response_code(int $code): void
    {

        if (!function_exists('http_response_code')) {

            switch ($code) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    exit ('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

            $protocol = (isset ($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);
        } else {

            http_response_code($code);
        }


    }



    public function __toString()
    {

        return $this->send();
    }



    public static function with($content): Action
    {

        $response = new self($content);
        $action = new Action($response);

        return $action;
    }
}


class Action
{

    private Response $res;

    public function __construct(Response $res)
    {
        $this->res = $res;
    }

    public function redirect($target): Response
    {
        $this->res->setHeader("Location", $target);
        $this->res->setStatusCode(301);

        return $this->res;
    }

    public function json()
    {
    }
}

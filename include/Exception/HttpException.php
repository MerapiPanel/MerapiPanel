<?php
namespace MerapiPanel\Exception;
use Exception;

enum HTTP_CODE : int
{
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
}


class HttpException extends Exception
{

    public function __construct(string $message, HTTP_CODE $code)
    {
        parent::__construct($message, $code->value);
    }
}
<?php

use il4mb\Mpanel\Application;
use il4mb\Mpanel\Core\Http\Middleware;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;
use il4mb\Mpanel\Core\Http\Router;
use il4mb\Mpanel\Exceptions\Error;
use il4mb\Mpanel\TemplateEngine\Twigg;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->run();
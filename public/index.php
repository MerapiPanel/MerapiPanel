<?php

use il4mb\Mpanel\Application;
use il4mb\Mpanel\Core\Http\Middleware;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;
use il4mb\Mpanel\Core\Http\Router;
use il4mb\Mpanel\Exceptions\Error;
use il4mb\Mpanel\TemplateEngine\Twigg;

require_once __DIR__ . '/../vendor/autoload.php';

class Auth implements Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (true) {
            return $next($request);
        }
        return new Response("Not allowed", 401);
    }
}





$template = new Twigg(__DIR__ . "/template");

try {

    $application = Application::getInstance();
    $application->setTemplateEngine($template);


    // echo $application->get_directory();

    Router::get("/", function () use ($application) {

        $application->getTemplateEngine()->setTemplate("index");

        $response = new Response([
            "status" => 200
        ]);
        $response->setHeader("Content-Type", "text/html");
        return $response;

    })->addMiddleware(new Auth());


    $application->run();
} catch (Error $e) {

    echo $e->getHtmlView();
} catch (Throwable $e) {

    echo $e->getMessage();
}

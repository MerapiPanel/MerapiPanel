<?php

namespace Mp\Core\Error;

use Throwable;

class Entity extends ErrorAbstract
{


    private bool $locked = false;

    function shutdown()
    {

        $error = error_get_last();

        if ($error === null) {
            return;
        }

        $errorMessage = $error['message'];

        // Extract the error type
        $errorType = '';
        if (strpos($errorMessage, ':') !== false) {
            $errorType = trim(substr($errorMessage, 0, strpos($errorMessage, ':')));
        }

        // Extract the error message
        $errorMessage = trim(substr($errorMessage, strpos($errorMessage, ':') + 1));

        // Extract the stack trace
        $stackTrace = [];
        if (strpos($errorMessage, 'Stack trace:') !== false) {
            $stackTraceSection = substr($errorMessage, strpos($errorMessage, 'Stack trace:') + 12);
            $stackTraceLines = explode("\n", trim($stackTraceSection));
            foreach ($stackTraceLines as $line) {
                $stackTrace[] = trim($line);
            }
        }

        $this->setType($errorType);
        $this->setMessage($errorMessage);
        $this->setCode(999);
        $this->setFile($error['file']);
        $this->setLine($error['line']);
        $this->setStackTrace($stackTrace);
        $this->view();
        exit;
    }

    function catch_error(Throwable $e)
    {

        $this->setType(basename(get_class($e)));
        $this->setMessage($e->getMessage());
        $this->setCode($e->getCode());
        $this->setFile($e->getFile());
        $this->setLine($e->getLine());
        $this->setStackTrace($e->getTrace());
        $this->view();
        exit;
    }


    function view()
    {

        try {

            if ($this->locked) {
                return;
            }
            $this->locked = true;

            $error            = $this->toArray();
            $error['snippet'] = $this->getSnippet();

            $view = $this->box->view();
            $view->addGlobal('error', $error);
            $template = null;

            if ($view->templateExists("/error/error$error[code].html.twig")) {

               $template = $view->load("/error/error$error[code].html.twig");
            } else {

               $template = $view->load("/error/error.html.twig");
            }

            echo $template->render([]);
            
        } catch (\Exception $e) {

            print_r($e);
           
        }
    }
}

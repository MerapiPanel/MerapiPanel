<?php

namespace MerapiQu\View;

use Twig\TemplateWrapper;

class Twig extends \Twig\Environment
{

    protected $view;

    public function __construct($loader, array $options = [])
    {
        parent::__construct($loader, $options);
    }


    public function load($name): TemplateWrapper
    {
        $this->view = $name;
        return parent::load($name);
    }

    public function getView(): string
    {
        return $this->view;
    }

}
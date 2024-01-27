<?php

namespace MerapiPanel\Core\View;

use Twig\TemplateWrapper;

class Twig extends \Twig\Environment
{

    public function __construct($loader, array $options = [])
    {
        parent::__construct($loader, $options);
    }


    public function load($name): TemplateWrapper
    {
        return parent::load($name);
    }
}
<?php

namespace il4mb\Mpanel\Core\Template;

use Twig\TemplateWrapper;

class Twig extends \Twig\Environment
{

    protected $template;

    public function __construct($loader, array $options = [])
    {
        parent::__construct($loader, $options);
    }


    public function load($name): TemplateWrapper
    {
        $this->template = $name;
        return parent::load($name);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

}
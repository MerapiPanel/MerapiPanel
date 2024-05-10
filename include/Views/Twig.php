<?php

namespace MerapiPanel\Views;

use Twig\TemplateWrapper;

class Twig extends \Twig\Environment
{

    public function __construct($loader, array $options = [])
    {
        if ($_ENV["__MP_DEBUG__"]) {
            $options["debug"] = true;
        }
        if($_ENV["__MP_CACHE__"]) {
            $options["cache"] = new \Twig\Cache\FilesystemCache(__DIR__ . "/cache", \Twig\Cache\FilesystemCache::FORCE_BYTECODE_INVALIDATION);
        }
        $options['legacy_named_arguments'] = false;

        parent::__construct($loader, $options);
    }


    public function load($name): TemplateWrapper
    {
        return parent::load($name);
    }
}
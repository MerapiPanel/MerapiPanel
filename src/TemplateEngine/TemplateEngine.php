<?php

namespace il4mb\Mpanel\TemplateEngine;

use Twig\Environment;

interface TemplateEngine
{


    const ON_CONSTRUCT               = 'on_construct';
    const ON_SET_TEMPLATE            = 'on_set_template';
    const ON_GET_TEMPLATE            = 'on_get_template';
    const ON_SET_ENVIRONMENT         = 'on_set_environment';
    const ON_GET_ENVIRONMENT         = 'on_get_environment';
    const ON_SET_TEMPLATE_PATH       = 'on_set_template_path';
    const ON_GET_TEMPLATE_PATH       = 'on_get_template_path';
    const ON_SET_ENVIRONMENT_OPTIONS = 'on_set_environment_options';
    const ON_GET_ENVIRONMENT_OPTIONS = 'on_get_environment_options';
    const ON_ADD_GLOBALVAR           = "on_add_globalvar";
    const ON_GET_GLOBALVAR           = "on_get_globalvar";
    const ON_ADD_FILTER              = 'on_add_filter';
    const ON_GET_FILTER              = 'on_get_filter';
    const ON_ADD_FUNCTION            = 'on_add_extension';
    const ON_GET_FUNCTION            = 'on_get_extension';
    const ON_ADD_TEST                = 'on_add_test';
    const ON_GET_TEST                = 'on_get_test';
    const ON_RENDER                  = 'on_render';

    
    
    public function __construct(string $templatePath, array $environmentOptions = []);

    public function setTemplate(string $template): void;
    public function getTemplate(): string;

    public function setTemplatePath(string $templatePath): void;
    public function getTemplatePath(): string;

    public function setEnvironmentOptions(array $environmentOptions): void;
    public function getEnvironmentOptions(): array;

    public function setEnvironment(Environment $environment): void;
    public function getEnvironment(): Environment;

    public function addGlobal(string $name, $value): void;
    public function getGlobals(): array;

    public function getFilter(string $name);
    public function addFilter($filter): void;

    public function getFunction(string $name);
    public function addFunction($function): void;

    public function render(array $data = []): string;

}

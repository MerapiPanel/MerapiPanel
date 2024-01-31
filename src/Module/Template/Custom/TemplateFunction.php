<?php

namespace MerapiPanel\Module\Template\Custom;

use MerapiPanel\Core\View\Abstract\FunctionAbstract;

class TemplateFunction extends FunctionAbstract
{


    function create_style($value)
    {
        return "<style>" . $value . "</style>";
    }
}

<?php

namespace MerapiPanel\Module\Template\Custom;

use MerapiPanel\Module\ViewEngine\Abstract\FunctionAbstract;

class TemplateFunction extends FunctionAbstract
{


    function create_style($value)
    {
        return "<style>" . $value . "</style>";
    }
}

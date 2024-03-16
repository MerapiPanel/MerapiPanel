<?php

namespace MerapiPanel\Core\View\Abstract;

use \Twig\Extension\AbstractExtension;

class ViewFunction extends AbstractExtension
{

    public function getFunctions()
    {
        $thisMethod = get_class_methods($this);
        $functions = array_diff($thisMethod, get_class_methods(AbstractExtension::class));

        $regs = [];
        foreach ($functions as $function) {
            $regs[] = new \Twig\TwigFunction("$function", [$this, $function]);
        }

        return $regs;
    }

    public function time()
    {
        return time();
    }
}

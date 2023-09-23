<?php

namespace MerapiPanel\Module\ViewEngine\Abstract;

use \Twig\Extension\AbstractExtension;

class FilterAbstract extends AbstractExtension
{

    public function getFunctions()
    {
        $thisMethod = get_class_methods($this);
        $functions = array_diff($thisMethod, get_class_methods(AbstractExtension::class));

        $regs = [];
        foreach ($functions as $function) {
            $regs[] = new \Twig\TwigFilter("$function", [$this, $function]);
        }

        return $regs;
    }
}

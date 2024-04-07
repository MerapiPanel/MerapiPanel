<?php
namespace MerapiPanel\Box\Module;

use Closure;
use MerapiPanel\Box\Module\Entity\Module;
use MerapiPanel\Utility\Http\Request;


/**
 * Description: Module Abstract Middleware.
 * @author      ilham b <durianbohong@gmail.com>
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 * @lastUpdate  2024-02-10
 */
abstract class __Middleware extends __Fragment
{
    protected Module $module;
    public function onCreate(Module $module)
    {
        $this->module = $module;
    }

    abstract function handle(Request $request, Closure $next);
}
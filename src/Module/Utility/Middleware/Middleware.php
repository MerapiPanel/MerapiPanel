<?php

namespace Mp\Module\Utility\Middleware;

use Closure;
use Mp\Module\Utility\Http\Request;
use Mp\Module\Utility\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
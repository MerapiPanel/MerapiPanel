<?php

namespace Mp\Core\Utilities\Middleware;

use Closure;
use Mp\Core\Utilities\Http\Request;
use Mp\Core\Utilities\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
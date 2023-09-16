<?php

namespace Mp\Utility\Middleware;

use Closure;
use Mp\Utility\Http\Request;
use Mp\Utility\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
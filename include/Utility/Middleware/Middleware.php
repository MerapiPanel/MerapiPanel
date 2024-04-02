<?php

namespace MerapiPanel\Utility\Middleware;

use Closure;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
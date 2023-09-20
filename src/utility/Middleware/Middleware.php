<?php

namespace MerapiQu\Utility\Middleware;

use Closure;
use MerapiQu\Utility\Http\Request;
use MerapiQu\Utility\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
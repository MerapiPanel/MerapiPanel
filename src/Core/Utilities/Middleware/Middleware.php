<?php

namespace il4mb\Mpanel\Core\Utilities\Middleware;

use Closure;
use il4mb\Mpanel\Core\Utilities\Http\Request;
use il4mb\Mpanel\Core\Utilities\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
<?php
namespace il4mb\Mpanel\Core\Http;

use Closure;
use il4mb\Mpanel\Core\Http\Request;
use il4mb\Mpanel\Core\Http\Response;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
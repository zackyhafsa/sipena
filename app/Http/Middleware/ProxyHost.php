<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('host') === '$host') {
            $request->headers->set('host', 'si-pena.web.id');
            $request->server->set('HTTP_HOST', 'si-pena.web.id');
        }

        return $next($request);
    }
}

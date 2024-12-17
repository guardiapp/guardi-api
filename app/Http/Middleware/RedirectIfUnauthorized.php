<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class RedirectIfUnauthorized
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (AuthorizationException $e) {
            // Redirigir al dashboard con mensaje
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }
    }
}

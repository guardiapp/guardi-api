<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class RedirectIfUnauthorized
{
    public function handle($request, Closure $next)
    {
        try {
            // return $next($request);
            $response = $next($request);

            // Fuerza a JSON si está en la ruta de la API
            if ($request->is('api/*')) {
                $response->headers->set('Content-Type', 'application/json');
            }

            return $response;
        } catch (AuthorizationException $e) {
            // Redirigir al dashboard con mensaje
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }
    }
}

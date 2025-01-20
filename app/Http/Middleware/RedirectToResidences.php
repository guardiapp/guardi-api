<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToResidences
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Aplica la redirección solo a '/' o '/dashboard'
        if ($request->is('/') || $request->is('dashboard')) {
            if ($user && $user->type === 'Manager') {
                $residences = $user->residences;

                if ($residences->count() === 1) {
                    return redirect()->route('residences.show', $residences->first()->id);
                } elseif ($residences->count() > 1) {
                    return redirect()->route('residences.index');
                }
            }
        }

        return $next($request);
    }
}

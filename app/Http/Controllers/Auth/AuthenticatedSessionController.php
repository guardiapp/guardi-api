<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        // Obtener el usuario autenticado
        $user = Auth::user();
        //dd($user->type);

        // Redireccionar según el tipo de usuario
        if ($user->type === 'Admin') {
            return redirect()->route('dashboard');
        }

        if ($user->type === 'Manager') {
            $residences = $user->residences;

            if ($residences->count() === 1) {
                // Redirigir a la vista única de la residencia
                return redirect()->route('residences.show', ['id' => $residences->first()->id]);
            }

            // Redirigir a la lista de residencias si hay varias
            return redirect()->route('residences.index');
        }

        // Redirigir a una ruta por defecto si no hay casos específicos
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

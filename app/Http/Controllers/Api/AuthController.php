<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware() {
        return [
            new Middleware('auth:api', except: ['login', 'register'])
        ];
    }
    /**
     * Registro de nuevos usuarios.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Inicio de sesión de usuarios.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $token = '';
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->refresh_token = $user->createToken("refresh")->plainTextToken;
        $user->save();

        $token = $this->generateJwtToken($user); //auth()->attempt($validated);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'refresh_token' => $user->refresh_token
        ]);
    }

    private function generateJwtToken(User $user) {
        $custom_claims = [
            'user_id' => $user->id,
            'email' => $user->email,
            'lifetime_min' => 120,
            'exp' => now()->addMinutes(120)->timestamp
        ];

        return JWTAuth::claims($custom_claims)->fromUser($user);
    }
    /**
     * Cierre de sesión del usuario autenticado.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Información del usuario autenticado.
     */
    public function me(Request $request)
    {
        return response()->json(Auth::user());
    }

    public function refreshToken(Request $request) {
        $refresh_token = $request->refresh_token;
        if(!$refresh_token || $refresh_token == '') return response()->json([
            "success" => false,
            "message" => "token missing"
        ]);
        $user = User::where("refresh_token", "=", $refresh_token)
            ->first();

        if(!$user) return response()->json([
            "success" => false,
            "message" => "unauthorized"
        ]);

        $user->refresh_token = $user->createToken("refresh")->plainTextToken;
        $user->save();

        return response()->json([
            "user" => $user,
            "token" => $this->generateJwtToken($user),
            'refresh_token' => $user->refresh_token
        ]);
    }
}

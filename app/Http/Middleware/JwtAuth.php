<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth as JwtAuthPackage;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Intenta obtener el token JWT de la solicitud
            $token = JwtAuthPackage::parseToken();

            // Intenta autenticar al usuario con el token
            $user = $token->authenticate();

            if (!$user) {
                return response()->json(['message' => 'user not found'], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'expired jwt'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'invalid token'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'missing jwt'], 401);
        }

        return $next($request);
    }
}

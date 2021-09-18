<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // On récupère le token dans le header
        $token = $request->header('API-TOKEN');

        // S'il n'y a pas de token => 403
        if (!$token) {
            return response()->json(['errors' => 'Missing token'], 403);
        }

        // On récupère le user, si token invalide => 403
        $user = User::where('api_token', $token)->firstOr(function () {
            return response()->json(['errors' => 'Invalid Token'], 403);
        });

        // On connecte le user
        Auth::login($user);
        
        return $next($request);
    }
}

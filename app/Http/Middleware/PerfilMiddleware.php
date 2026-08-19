<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerfilMiddleware
{
    public function handle(Request $request, Closure $next, string $perfil): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->perfil !== $perfil) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
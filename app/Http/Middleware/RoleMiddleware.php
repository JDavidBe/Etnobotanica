<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!auth()->check()) {
            abort(403, 'No autenticado.');
        }

        // Separar roles por coma
        $requiredRoles = array_map('trim', explode(',', $roles));
        
        // Verificar si el usuario tiene al menos uno de los roles
        if (!auth()->user()->hasAnyRole($requiredRoles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}

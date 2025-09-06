<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role->name !== $role) {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}

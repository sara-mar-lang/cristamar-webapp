<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckVendedor {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || !auth()->user()->activo || !in_array(auth()->user()->rol, ['vendedor', 'administrador'])) {
            return redirect('/')->with('error', 'Acceso exclusivo para el equipo comercial.');
        }
        return $next($request);
    }
}

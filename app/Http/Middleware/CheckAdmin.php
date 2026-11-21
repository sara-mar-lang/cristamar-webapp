<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || !auth()->user()->activo || auth()->user()->rol !== 'administrador') {
            return redirect('/')->with('error', 'Acceso exclusivo para el Administrador General.');
        }
        return $next($request);
    }
}

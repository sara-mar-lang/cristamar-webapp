<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || auth()->user()->rol !== 'administrador') {
            return redirect('/')->with('error', 'No tienes permiso para entrar aquí.');
        }
        return $next($request);
    }
}

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUsuarioActivo {
    public function handle(Request $request, Closure $next) {
        if (auth()->check() && !auth()->user()->activo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido suspendida o vetada por incumplimiento de normas de CRISTAMAR.'
            ]);
        }
        return $next($request);
    }
}

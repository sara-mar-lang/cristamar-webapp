<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-slate-900">Iniciar Sesión</h1>
        <p class="text-sm text-slate-500 mt-1">Accede a tus cotizaciones o al panel administrativo</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="usuario@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordar mi sesión</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                    ¿No tienes cuenta? Regístrate
                </a>
            @endif

            <button type="submit" class="ms-3 inline-flex items-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-lg font-semibold text-xs text-amber-400 uppercase tracking-widest hover:bg-slate-800 transition">
                Ingresar
            </button>
        </div>
    </form>
</x-guest-layout>

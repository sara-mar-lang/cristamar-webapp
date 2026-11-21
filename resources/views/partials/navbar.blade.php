<nav class="bg-slate-950 text-white sticky top-0 z-50 border-b border-amber-500/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
        <a href="{{ route('inicio') }}" class="flex items-center space-x-3">
            <span class="w-10 h-10 rounded bg-amber-500/20 border border-amber-400 flex items-center justify-center font-serif font-bold text-amber-400 text-xl">C</span>
            <span class="text-xl font-serif tracking-widest text-white font-bold">CRISTAMAR</span>
        </a>
        <div class="hidden md:flex items-center space-x-6 text-xs font-semibold tracking-wider uppercase">
            <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'bg-white text-slate-950 px-3 py-1.5 rounded' : 'text-slate-300 hover:text-amber-400' }}">Inicio</a>
            <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'bg-white text-slate-950 px-3 py-1.5 rounded' : 'text-slate-300 hover:text-amber-400' }}">Nosotros</a>
            <a href="{{ route('servicios') }}" class="{{ request()->routeIs('servicios') ? 'bg-white text-slate-950 px-3 py-1.5 rounded' : 'text-slate-300 hover:text-amber-400' }}">Servicios</a>
            <a href="{{ route('locaciones') }}" class="{{ request()->routeIs('locaciones') ? 'bg-white text-slate-950 px-3 py-1.5 rounded' : 'text-slate-300 hover:text-amber-400' }}">Locaciones</a>
            <a href="{{ route('cotizador.index') }}" class="{{ request()->routeIs('cotizador.index') ? 'bg-white text-slate-950 px-3 py-1.5 rounded' : 'text-slate-300 hover:text-amber-400' }}">Cotizador VIP</a>
        </div>
        <div class="flex items-center space-x-3 text-xs">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-slate-800 border border-amber-400/50 text-amber-300 font-bold px-3 py-2 rounded uppercase">
                    {{ Auth::user()->rol === 'administrador' ? 'Admin' : (Auth::user()->rol === 'vendedor' ? 'Vendedor' : 'Mis Eventos') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-semibold uppercase">Ingresar</a>
            @endauth
            <a href="{{ route('cotizador.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2.5 rounded uppercase tracking-wider transition">
                Cotiza tu Evento
            </a>
        </div>
    </div>
</nav>

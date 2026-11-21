<nav class="bg-slate-950 border-b border-amber-500/20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <a href="{{ route('inicio') }}" class="text-xl font-serif font-bold tracking-widest text-amber-400">CRISTAMAR</a>
                <div class="hidden sm:flex space-x-6 text-sm">
                    <a href="{{ route('inicio') }}" class="text-slate-300 hover:text-amber-400 transition">Inicio</a>
                    <a href="{{ route('cotizador.index') }}" class="text-slate-300 hover:text-amber-400 transition">Cotizador VIP</a>
                    <a href="{{ route('dashboard') }}" class="text-amber-400 font-semibold">
                        @if(Auth::user()->rol === 'administrador') Panel Administrador
                        @elseif(Auth::user()->rol === 'vendedor') Panel Comercial (Vendedora)
                        @else Mis Solicitudes @endif
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-4 text-sm">
                <span class="text-slate-300">{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="text-slate-400 hover:text-white">Mi Perfil</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-slate-800 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Locaciones | CRISTAMAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-slate-900 antialiased" x-data="{ filtro: 'todas' }">
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto py-16 px-4">
        <div class="text-center mb-10">
            <span class="text-xs uppercase tracking-widest text-amber-700 font-bold">Escenarios de Alto Impacto</span>
            <h1 class="text-4xl font-serif font-bold mt-2">Locaciones Exclusivas en Bogotá & la Sabana</h1>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <button @click="filtro = 'todas'" :class="filtro === 'todas' ? 'bg-slate-950 text-white' : 'bg-white border'" class="px-5 py-2.5 rounded-xl font-bold text-sm">Todas las Zonas</button>
            <button @click="filtro = 'Sabana'" :class="filtro === 'Sabana' ? 'bg-slate-950 text-white' : 'bg-white border'" class="px-5 py-2.5 rounded-xl font-bold text-sm">Sabana (Chía, Sopó, Tenjo, Cajicá)</button>
            <button @click="filtro = 'Bogota'" :class="filtro === 'Bogota' ? 'bg-slate-950 text-white' : 'bg-white border'" class="px-5 py-2.5 rounded-xl font-bold text-sm">Bogotá VIP (Chicó, Usaquén, Rosales)</button>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($locaciones as $loc)
                <div x-show="filtro === 'todas' || filtro === '{{ $loc->zona }}'" class="bg-white p-6 rounded-2xl border shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full {{ $loc->zona === 'Sabana' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $loc->zona === 'Sabana' ? 'Sabana' : 'Bogotá VIP' }}
                        </span>
                        <h3 class="text-xl font-serif font-bold mt-3">{{ $loc->nombre }}</h3>
                        <p class="text-sm text-slate-600 mt-2">Capacidad hasta <strong>{{ $loc->capacidad_max }} invitados</strong> · Logística integral CRISTAMAR.</p>
                    </div>
                    <a href="{{ route('cotizador.index') }}" class="mt-6 block text-center bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-2.5 rounded-lg text-sm">
                        Cotizar en esta Locación
                    </a>
                </div>
            @endforeach
        </div>
    </main>
</body>
</html>

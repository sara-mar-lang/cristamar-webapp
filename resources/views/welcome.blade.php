<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRISTAMAR | Producción de Eventos en Bogotá y la Sabana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-slate-900 antialiased">
    @include('partials.navbar')

    <header class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white py-24 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <span class="inline-block border border-amber-400/40 bg-amber-500/10 text-amber-300 text-xs font-semibold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-6">
                Logística de Precisión & Producción Audiovisual de Alta Gama
            </span>
            <h1 class="text-4xl sm:text-6xl font-serif font-bold leading-tight mb-6">
                Reinventamos la Producción de Eventos en Bogotá y la Sabana
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10">
                Fusión perfecta entre logística de alta precisión, diseño de experiencias memorables y tecnología interactiva.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                <a href="{{ route('cotizador.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-8 py-4 rounded-lg shadow-lg transition">
                    Cotizar mi Evento en Tiempo Real
                </a>
                <a href="https://wa.me/573152292638?text=Hola%20CRISTAMAR,%20quiero%20cotizar%20mi%20evento." target="_blank" class="border border-slate-500 hover:border-amber-400 text-white font-semibold px-8 py-4 rounded-lg transition">
                    Agendar Asesoría VIP
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 border-t border-slate-800 pt-10 text-center">
                <div><span class="block text-3xl font-bold text-amber-400">+150</span><span class="text-xs uppercase tracking-wider text-slate-400">Eventos Ejecutados</span></div>
                <div><span class="block text-3xl font-bold text-amber-400">90%</span><span class="text-xs uppercase tracking-wider text-slate-400">Satisfacción de Clientes</span></div>
                <div><span class="block text-3xl font-bold text-amber-400">100%</span><span class="text-xs uppercase tracking-wider text-slate-400">Transparencia Presupuestal</span></div>
                <div><span class="block text-xl font-bold text-amber-400">Bogotá & Sabana</span><span class="text-xs uppercase tracking-wider text-slate-400">Chía, Sopó, Tenjo, Cajicá</span></div>
            </div>
        </div>
    </header>
    <footer class="bg-slate-950 text-slate-500 py-8 text-center text-xs">
        Derechos de Autor © 2026 CRISTAMAR · Todos los derechos reservados.
    </footer>
</body>
</html>

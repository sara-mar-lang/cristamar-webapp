<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRISTAMAR | Eventos Corporativos y Bodas de Autor en Bogotá y la Sabana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <!-- Navbar -->
    <nav class="bg-slate-950 text-white sticky top-0 z-50 border-b border-amber-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <a href="{{ route('inicio') }}" class="flex items-center space-x-2">
                <span class="text-2xl font-serif tracking-widest text-amber-400 font-bold">CRISTAMAR</span>
                <span class="hidden sm:inline text-xs uppercase tracking-widest text-slate-400">| Bogotá & Sabana</span>
            </a>
            <div class="flex items-center space-x-4 text-sm">
                <a href="#servicios" class="hidden md:inline hover:text-amber-400 transition">Servicios</a>
                <a href="#locaciones" class="hidden md:inline hover:text-amber-400 transition">Locaciones</a>
                <a href="{{ route('cotizador.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-semibold px-4 py-2 rounded-lg transition">
                    Simular Presupuesto
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-amber-300 hover:underline font-medium">Mi Panel</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white">Ingresar</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('error'))
        <div class="bg-red-600 text-white text-center py-3 px-4 font-medium">{{ session('error') }}</div>
    @endif

    <!-- Hero Section -->
    <header class="relative bg-slate-900 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block bg-amber-500/10 border border-amber-400/30 text-amber-300 text-xs font-semibold uppercase tracking-widest px-3 py-1 rounded-full mb-4">
                    Producción de Eventos de Alto Nivel
                </span>
                <h1 class="text-4xl sm:text-5xl font-serif font-bold leading-tight mb-6">
                    Eventos corporativos y bodas de autor en <span class="text-amber-400">Bogotá y la Sabana</span>, con presupuesto transparente al instante.
                </h1>
                <p class="text-lg text-slate-300 mb-8 leading-relaxed">
                    A diferencia de las casas de banquetes tradicionales que tardan días en cotizar, en CRISTAMAR simulas tu inversión en menos de un minuto y recibes respuesta formal en 15 minutos.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-center px-8 py-4 rounded-xl shadow-lg shadow-amber-500/20 transition text-lg">
                        Simular mi evento ahora →
                    </a>
                    <a href="#locaciones" class="border border-slate-600 hover:border-amber-400 text-white text-center px-8 py-4 rounded-xl transition">
                        Explorar Haciendas y Salones
                    </a>
                </div>
            </div>
            <div class="bg-slate-800/80 border border-slate-700 p-8 rounded-2xl shadow-2xl">
                <h2 class="text-amber-400 font-semibold uppercase tracking-wider text-sm mb-4">¿Por qué elegir CRISTAMAR?</h2>
                <ul class="space-y-4 text-slate-200">
                    <li class="flex items-start gap-3">
                        <span class="text-amber-400 font-bold">✓</span>
                        <div><strong>Transparencia desde el segundo cero:</strong> Calcula invitados, catering de autor, sonido e iluminación sin letras pequeñas.</div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-amber-400 font-bold">✓</span>
                        <div><strong>Logística Dual Bogotá & Sabana:</strong> Cobertura propia en salones ejecutivos de Bogotá y haciendas exclusivas en Chía, Cajicá y Sopó.</div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-amber-400 font-bold">✓</span>
                        <div><strong>Trazabilidad Digital:</strong> Código único de cotización (CR-xxxx) y seguimiento en línea de tus solicitudes.</div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Catálogo de Servicios -->
    <section id="servicios" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold text-slate-900">Portafolio y Tarifas Base</h2>
            <p class="text-slate-600 mt-2">Curaduría gastronómica, montaje arquitectónico y producción audiovisual a medida.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($tarifas->take(3) as $tarifa)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-amber-400 transition">
                    <span class="text-xs uppercase font-bold tracking-wider text-amber-600">Experiencia Integral</span>
                    <h3 class="text-xl font-bold mt-2 mb-3">{{ $tarifa->nombre }}</h3>
                    <p class="text-3xl font-extrabold text-slate-900 mb-4">
                        ${{ number_format($tarifa->precio, 0, ',', '.') }} <span class="text-sm font-normal text-slate-500">/ persona</span>
                    </p>
                    <a href="{{ route('cotizador.index') }}" class="text-amber-600 font-semibold hover:underline">Cotizar este formato →</a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Catálogo de Locaciones -->
    <section id="locaciones" class="py-16 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-serif font-bold text-slate-900">Locaciones Aliadas en Bogotá y la Sabana</h2>
                <p class="text-slate-600 mt-2">Espacios verificados para eventos desde 20 hasta 1.000 invitados.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($locaciones as $loc)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full {{ $loc->zona === 'Sabana' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                            Zona {{ $loc->zona }}
                        </span>
                        <h3 class="font-bold text-lg mt-3">{{ $loc->nombre }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Capacidad máxima: <strong>{{ $loc->capacidad_max }} personas</strong></p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-slate-950 text-slate-400 py-10 text-center text-sm">
        <p>© {{ date('Y') }} CRISTAMAR Eventos & Producción · Bogotá y Sabana de Cundinamarca.</p>
    </footer>
</body>
</html>

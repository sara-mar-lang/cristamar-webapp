<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nosotros | CRISTAMAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-slate-900 antialiased" x-data="{ perfil: 'empresas' }">
    @include('partials.navbar')

    <section class="bg-slate-950 text-white py-20 px-4">
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="border border-amber-500/40 bg-slate-900 p-10 rounded-2xl text-center">
                <div class="text-5xl font-serif text-amber-400 font-bold tracking-widest mb-4">M & S</div>
                <h2 class="text-2xl font-serif font-bold">Marlon Romero & Sara Vargas</h2>
                <p class="text-xs uppercase tracking-widest text-amber-400 mt-1">Fundadores & Directores Generales</p>
                <span class="inline-block mt-4 px-3 py-1 bg-amber-500/10 border border-amber-400/30 rounded-full text-xs text-amber-200">
                    Matrimonio & Pasión por los Eventos
                </span>
            </div>
            <div>
                <span class="text-xs uppercase tracking-widest text-amber-400 font-bold">Historia de los Fundadores</span>
                <blockquote class="text-lg text-slate-200 italic leading-relaxed mt-4 border-l-2 border-amber-400 pl-4">
                    “CRISTAMAR nació de la complicidad de nuestro matrimonio. Creemos firmemente que cada celebración —desde una boda mágica en los valles de la Sabana hasta una cumbre corporativa de alto impacto en la capital— debe vivirse con autenticidad, calma absoluta y un estándar de producción impecable.”
                </blockquote>
                <div class="grid grid-cols-3 gap-3 mt-6 text-xs text-center">
                    <div class="bg-slate-900 p-3 rounded-lg border border-slate-800">Trato cercano persona a persona</div>
                    <div class="bg-slate-900 p-3 rounded-lg border border-slate-800">Coordinación milimétrica</div>
                    <div class="bg-slate-900 p-3 rounded-lg border border-slate-800">Cobertura Bogotá & Sabana</div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-5xl mx-auto py-16 px-4">
        <div class="text-center mb-10">
            <span class="text-xs uppercase tracking-widest text-amber-700 font-bold">Nuestra Metodología</span>
            <h2 class="text-3xl font-serif font-bold mt-2">Producción de Autor con Distinción Estética</h2>
        </div>

        <div class="flex justify-center gap-4 mb-8">
            <button @click="perfil = 'empresas'" :class="perfil === 'empresas' ? 'bg-slate-950 text-white' : 'bg-white text-slate-700 border'" class="px-6 py-3 rounded-xl font-bold text-sm transition">
                Para Empresas / Corporativo
            </button>
            <button @click="perfil = 'parejas'" :class="perfil === 'parejas' ? 'bg-slate-950 text-white' : 'bg-white text-slate-700 border'" class="px-6 py-3 rounded-xl font-bold text-sm transition">
                Para Parejas Bodas & Sociales
            </button>
        </div>

        <div x-show="perfil === 'empresas'" class="bg-white p-8 rounded-2xl shadow-sm border">
            <span class="text-xs font-bold uppercase text-amber-600">Enfoque Corporativo & BTL</span>
            <h3 class="text-2xl font-serif font-bold mt-1 mb-4">Precisión Cronometrada al Segundo & Rider Técnico de Clase Mundial</h3>
            <ul class="space-y-3 text-slate-700 text-sm mb-6">
                <li><strong>• Rigor de Escala Minuto a Minuto:</strong> Coordinación de oradores, iluminación protocolaria y señalización sin retrasos.</li>
                <li><strong>• Infraestructura Audiovisual Pro:</strong> Pantallas LED micro-pitch, microfonía Shure inalámbrica y streaming sin latencia.</li>
                <li><strong>• Gestión BTL de Alto Impacto:</strong> Activaciones de marca y convenciones con +50 marcas líderes.</li>
            </ul>
            <a href="{{ route('cotizador.index') }}" class="inline-block bg-amber-500 text-slate-950 font-bold px-6 py-3 rounded-lg text-sm">Cotizar Producción Corporativa →</a>
        </div>

        <div x-show="perfil === 'parejas'" class="bg-white p-8 rounded-2xl shadow-sm border">
            <span class="text-xs font-bold uppercase text-amber-600">Enfoque Bodas de Autor & Sociales VIP</span>
            <h3 class="text-2xl font-serif font-bold mt-1 mb-4">Sensibilidad Estética Inolvidable con Coordinación Silenciosa</h3>
            <ul class="space-y-3 text-slate-700 text-sm mb-6">
                <li><strong>• Diseño Floral y Escenografía de Autor:</strong> Curaduría botánica estacional, iluminación cálida y carpas en la Sabana.</li>
                <li><strong>• Alta Cocina & Coctelería de Autor:</strong> Menú de 3 o 4 tiempos maridados con vinos selectos.</li>
                <li><strong>• Producción de Fiesta & DJ de Gala:</strong> Pistas infinitas de cristal, shows de chispas frías e iluminación robótica.</li>
            </ul>
            <a href="{{ route('cotizador.index') }}" class="inline-block bg-amber-500 text-slate-950 font-bold px-6 py-3 rounded-lg text-sm">Cotizar Boda o Fiesta Social →</a>
        </div>
    </section>
</body>
</html>

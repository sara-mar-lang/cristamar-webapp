<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicios | CRISTAMAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-slate-900 antialiased" x-data="{ tab: 'corp' }">
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto py-16 px-4">
        <div class="text-center mb-10">
            <span class="text-xs uppercase tracking-widest text-amber-700 font-bold">Catálogo de Producción</span>
            <h1 class="text-4xl font-serif font-bold mt-2">Servicios Diseñados para Trascender</h1>
            <p class="text-slate-600 mt-2">Filtra nuestra cartera de servicios especializados y cotiza al instante.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-10">
            <button @click="tab = 'corp'" :class="tab === 'corp' ? 'bg-slate-950 text-white' : 'bg-white border'" class="px-5 py-2.5 rounded-xl font-bold text-sm">Corporativo & BTL</button>
            <button @click="tab = 'bodas'" :class="tab === 'bodas' ? 'bg-slate-950 text-white' : 'bg-white border'" class="px-5 py-2.5 rounded-xl font-bold text-sm">Bodas & Eventos Sociales</button>
            <button @click="tab = 'tecnica'" :class="tab === 'tecnica' ? 'bg-slate-950 text-white' : 'bg-white border'" class="px-5 py-2.5 rounded-xl font-bold text-sm">Producción Técnica</button>
        </div>

        <div x-show="tab === 'corp'" class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl border shadow-sm">
                <h3 class="text-2xl font-serif font-bold">Cumbres & Congresos Corporativos</h3>
                <p class="text-sm text-slate-600 mt-2 mb-4">Organización integral para convenciones y asambleas en hoteles de Bogotá o centros de eventos en la Sabana.</p>
                <ul class="text-sm space-y-2 text-slate-700 mb-6">
                    <li>★ Pantallas LED p2.6 sonido Line Array</li>
                    <li>★ Registro biométrico y acreditación ágil</li>
                    <li>★ Coffee breaks ejecutivos y almuerzos de gala</li>
                </ul>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-bold text-amber-700">Desde $75.000 / asistente</span>
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Agregar cotización</a>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl border shadow-sm">
                <h3 class="text-2xl font-serif font-bold">Lanzamiento de Marca & BTL</h3>
                <p class="text-sm text-slate-600 mt-2 mb-4">Impacto sensorial con escenografías futuristas, videomapping arquitectónico y dinámicas inmersivas.</p>
                <ul class="text-sm space-y-2 text-slate-700 mb-6">
                    <li>★ Efectos especiales (Cryo jets, chispas frías)</li>
                    <li>★ Estaciones interactivas fotográficas</li>
                    <li>★ Personal de protocolo bilingüe</li>
                </ul>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-bold text-amber-700">Personalización Total</span>
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Agregar cotización</a>
                </div>
            </div>
        </div>

        <div x-show="tab === 'bodas'" class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl border shadow-sm">
                <h3 class="text-2xl font-serif font-bold">Bodas de Autor Campestres</h3>
                <p class="text-sm text-slate-600 mt-2 mb-4">Celebraciones inolvidables en las haciendas más icónicas de Chía, Sopó y Tenjo con curaduría floral.</p>
                <ul class="text-sm space-y-2 text-slate-700 mb-6">
                    <li>★ Wedding Planner Senior dedicado</li>
                    <li>★ Carpas transparentes y cielo estrellado LED</li>
                    <li>★ Menú de 3 o 4 tiempos maridado</li>
                </ul>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-bold text-amber-700">Desde $120.000 / invitado</span>
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Agregar cotización</a>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl border shadow-sm">
                <h3 class="text-2xl font-serif font-bold">Fiestas Sociales & 15 Años VIP</h3>
                <p class="text-sm text-slate-600 mt-2 mb-4">Aniversarios, galas privadas y 15 años temáticos de vanguardia con montaje de discoteca de lujo.</p>
                <ul class="text-sm space-y-2 text-slate-700 mb-6">
                    <li>★ DJ profesional de renombre y VJ en vivo</li>
                    <li>★ Pista de baile infinita de cristal templado</li>
                    <li>★ Coctelería de autor y barra libre premium</li>
                </ul>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-bold text-amber-700">Desde $90.000 / invitado</span>
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Agregar cotización</a>
                </div>
            </div>
        </div>

        <div x-show="tab === 'tecnica'" class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl border shadow-sm">
                <h3 class="text-2xl font-serif font-bold">Iluminación Robótica & Sonido Line Array</h3>
                <p class="text-sm text-slate-600 mt-2 mb-4">Equipos de estándar internacional para recintos cerrados o espacios abiertos con ecualización acústica.</p>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-bold text-amber-700">Paquete Pro $1.200.000 COP</span>
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Agregar cotización</a>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl border shadow-sm">
                <h3 class="text-2xl font-serif font-bold">Escenografía, Truss & Domos Geodésicos</h3>
                <p class="text-sm text-slate-600 mt-2 mb-4">Estructuras en aluminio certificado, cerramientos transparentes y climatización para el frío de la Sabana.</p>
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="font-bold text-amber-700">Cotización Modular</span>
                    <a href="{{ route('cotizador.index') }}" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Agregar cotización</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

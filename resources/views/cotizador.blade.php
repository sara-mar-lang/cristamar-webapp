<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cotizador Inteligente VIP | CRISTAMAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF8F5] text-slate-900 antialiased">
    @include('partials.navbar')

    <main class="max-w-6xl mx-auto py-12 px-4"
          x-data="{
              tipoEvento: '{{ old('tipo_evento', $repetir->tipo_evento ?? 'Corporativo / BTL / Congreso') }}',
              invitados: {{ old('invitados', $repetir->invitados ?? 100) }},
              zona: '{{ old('zona', $repetir->zona ?? 'Sabana') }}',
              adicionalesSeleccionados: @json(old('adicionales', $repetir ? $repetir->lineas->pluck('tarifa_id')->map(fn($id) => (string)$id)->toArray() : [])),
              cargando: false,
              preciosBase: {
                  'Corporativo / BTL / Congreso': 75000,
                  'Boda de Autor Campestre': 120000,
                  'Fiesta Social VIP / 15 Años': 90000
              },
              preciosAdicionales: {
                  @foreach($tarifas->whereNotIn('nombre', ['Corporativo / BTL / Congreso', 'Boda de Autor Campestre', 'Fiesta Social VIP / 15 Años', 'Recargo Sabana']) as $ad)
                      '{{ $ad->id }}': { precio: {{ $ad->precio }}, tipo: '{{ $ad->tipo_cobro }}' },
                  @endforeach
              },
              get totalEstimado() {
                  let base = (this.preciosBase[this.tipoEvento] || 75000) * this.invitados;
                  let recargo = this.zona === 'Sabana' ? (12000 * this.invitados) : 0;
                  let extras = 0;
                  this.adicionalesSeleccionados.forEach(id => {
                      let item = this.preciosAdicionales[id];
                      if (item) extras += item.tipo === 'por_persona' ? (item.precio * this.invitados) : item.precio;
                  });
                  return base + recargo + extras;
              },
              formatoCOP(val) {
                  return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(val);
              }
          }">

        @if(session('exito_solicitud'))
            <div class="bg-emerald-50 border-2 border-emerald-600 rounded-2xl p-6 mb-8 shadow">
                <h2 class="text-xl font-bold text-emerald-900">✓ ¡Solicitud registrada con código {{ session('exito_solicitud.codigo') }}!</h2>
                <p class="text-sm text-emerald-800 mt-1">Estimado oficial: <strong>${{ session('exito_solicitud.total') }} COP</strong>. Puedes hacer seguimiento en tu panel o enviarla directo por WhatsApp.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a target="_blank" href="https://wa.me/573152292638?text=Hola%20CRISTAMAR,%20acabo%20de%20generar%20la%20cotizaci%C3%B3n%20{{ session('exito_solicitud.codigo') }}%20por%20${{ session('exito_solicitud.total') }}%20COP." class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-lg text-sm">Reservar Cotización vía WhatsApp</a>
                    @auth
                        <a href="{{ route('mis.solicitudes') }}" class="bg-slate-900 text-amber-400 font-bold px-4 py-2 rounded-lg text-sm">Ver en Mis Solicitudes</a>
                    @endauth
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded">
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <form method="POST" action="{{ route('cotizador.store') }}" @submit="cargando = true" class="lg:col-span-2 bg-white p-8 rounded-2xl border shadow-sm space-y-6">
                @csrf
                <h1 class="text-3xl font-serif font-bold">Cotizador Inteligente en Tiempo Real</h1>

                <div>
                    <label class="block text-sm font-bold mb-1">Tipo de Evento</label>
                    <select name="tipo_evento" x-model="tipoEvento" class="w-full rounded-xl border-slate-300">
                        <option value="Corporativo / BTL / Congreso">Corporativo / BTL / Congreso ($75.000 COP / persona)</option>
                        <option value="Boda de Autor Campestre">Boda de Autor Campestre ($120.000 COP / persona)</option>
                        <option value="Fiesta Social VIP / 15 Años">Fiesta Social VIP / 15 Años ($90.000 COP / persona)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Cantidad Estimada de Invitados (20 a 1.000)</label>
                    <div class="flex items-center gap-4">
                        <input type="range" min="20" max="1000" x-model.number="invitados" class="w-full accent-amber-500">
                        <input type="number" name="invitados" min="20" max="1000" x-model.number="invitados" class="w-28 rounded-xl border-slate-300 text-center font-bold">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1">Ubicación del Evento</label>
                        <select name="zona" x-model="zona" class="w-full rounded-xl border-slate-300">
                            <option value="Sabana">Hacienda en la Sabana (+$12.000 / persona)</option>
                            <option value="Bogota">Bogotá / Hotel VIP (Sin recargo)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Locación Preferida</label>
                        <select name="locacion_id" class="w-full rounded-xl border-slate-300">
                            <option value="">-- A definir con Asesor VIP --</option>
                            @foreach($locaciones as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">Servicios Adicionales de Producción</label>
                    <div class="space-y-2">
                        @foreach($tarifas->whereNotIn('nombre', ['Corporativo / BTL / Congreso', 'Boda de Autor Campestre', 'Fiesta Social VIP / 15 Años', 'Recargo Sabana']) as $ad)
                            <label class="border rounded-xl p-3.5 flex justify-between items-center cursor-pointer hover:bg-amber-50/40">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="adicionales[]" value="{{ $ad->id }}" x-model="adicionalesSeleccionados" class="rounded text-amber-500">
                                    <span class="font-bold text-sm">{{ $ad->nombre }}</span>
                                </div>
                                <span class="text-xs font-bold bg-slate-900 text-amber-400 px-2.5 py-1 rounded">
                                    +${{ number_format($ad->precio, 0, ',', '.') }} {{ $ad->tipo_cobro === 'por_persona' ? '/ persona' : 'fijo' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 pt-4 border-t">
                    <div>
                        <label class="block text-xs font-bold mb-1">Fecha del Evento *</label>
                        <input type="date" name="fecha_evento" value="{{ old('fecha_evento', now()->addDays(20)->format('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1">Nombre Completo *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', auth()->user()->name ?? '') }}" required class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1">Celular (10 dígitos) *</label>
                        <input type="text" name="celular" value="{{ old('celular', auth()->user()->celular ?? '') }}" placeholder="3152292638" required class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1">Correo Electrónico *</label>
                        <input type="email" name="correo" value="{{ old('correo', auth()->user()->email ?? '') }}" required class="w-full rounded-xl border-slate-300">
                    </div>
                </div>

                <button type="submit" :disabled="cargando" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-4 rounded-xl shadow transition">
                    <span x-text="cargando ? 'Generando código CR-xxxx...' : 'Guardar Cotización Oficial en Sistema'"></span>
                </button>
            </form>

            <!-- Recibo Estimado Oficial (igual al video) -->
            <aside class="bg-white p-6 rounded-2xl border-2 border-slate-900 shadow-lg h-fit sticky top-24">
                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <span class="font-serif font-bold text-lg">CRISTAMAR</span>
                        <span class="block text-xs text-slate-500">ESTIMADO OFICIAL</span>
                    </div>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">En Tiempo Real</span>
                </div>
                <div class="py-4 space-y-2 text-sm border-b">
                    <div class="flex justify-between"><span class="text-slate-500">Tipo:</span><strong x-text="tipoEvento"></strong></div>
                    <div class="flex justify-between"><span class="text-slate-500">Asistentes:</span><strong x-text="invitados + ' invitados'"></strong></div>
                    <div class="flex justify-between"><span class="text-slate-500">Zona:</span><strong x-text="zona"></strong></div>
                </div>
                <div class="py-6 text-center">
                    <span class="text-xs uppercase tracking-widest text-slate-500">Estimado Preliminar en COP:</span>
                    <div class="text-3xl font-extrabold text-slate-900 mt-1" x-text="formatoCOP(totalEstimado)"></div>
                </div>
                <a target="_blank" :href="'https://wa.me/573152292638?text=Hola%20CRISTAMAR,%20quiero%20reservar%20mi%20evento%20(' + tipoEvento + ')%20para%20' + invitados + '%20personas%20estimado%20en%20' + formatoCOP(totalEstimado)"
                   class="block w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3.5 rounded-xl transition">
                    Reservar Cotización vía WhatsApp
                </a>
            </aside>
        </div>
    </main>
</body>
</html>

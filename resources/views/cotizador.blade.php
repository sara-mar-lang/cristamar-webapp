<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulador de Cotización | CRISTAMAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
    <nav class="bg-slate-950 text-white py-4 px-6 flex justify-between items-center">
        <a href="{{ route('inicio') }}" class="text-xl font-serif font-bold text-amber-400 tracking-widest">CRISTAMAR</a>
        <div class="space-x-4 text-sm">
            <a href="{{ route('inicio') }}" class="hover:text-amber-400">Inicio</a>
            @auth
                <a href="{{ route('dashboard') }}" class="text-amber-400 font-semibold">Mi Panel</a>
            @else
                <a href="{{ route('login') }}" class="hover:text-amber-400">Iniciar Sesión</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-5xl mx-auto py-10 px-4 sm:px-6"
          x-data="{
              tipoEvento: '{{ old('tipo_evento', $repetir->tipo_evento ?? 'Corporativo / BTL') }}',
              invitados: {{ old('invitados', $repetir->invitados ?? 100) }},
              zona: '{{ old('zona', $repetir->zona ?? 'Sabana') }}',
              adicionalesSeleccionados: @json(old('adicionales', $repetir ? $repetir->lineas->pluck('tarifa_id')->map(fn($id) => (string)$id)->toArray() : [])),
              comentarios: '{{ old('comentarios', $repetir->comentarios ?? '') }}',
              cargando: false,
              preciosBase: {
                  'Corporativo / BTL': 75000,
                  'Boda de autor': 120000,
                  'Fiesta social o 15 años': 90000
              },
              preciosAdicionales: {
                  @foreach($tarifas->whereNotIn('nombre', ['Corporativo / BTL', 'Boda de autor', 'Fiesta social o 15 años', 'Recargo Sabana']) as $ad)
                      '{{ $ad->id }}': { precio: {{ $ad->precio }}, tipo: '{{ $ad->tipo_cobro }}' },
                  @endforeach
              },
              get totalEstimado() {
                  let base = (this.preciosBase[this.tipoEvento] || 75000) * this.invitados;
                  let recargo = this.zona === 'Sabana' ? (12000 * this.invitados) : 0;
                  let extras = 0;
                  this.adicionalesSeleccionados.forEach(id => {
                      let item = this.preciosAdicionales[id];
                      if (item) {
                          extras += item.tipo === 'por_persona' ? (item.precio * this.invitados) : item.precio;
                      }
                  });
                  return base + recargo + extras;
              },
              formatoCOP(valor) {
                  return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(valor);
              }
          }">

        <!-- ESTADO 3: ÉXITO -->
        @if(session('exito_solicitud'))
            <div class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-8 mb-8 shadow-md">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-500 text-white rounded-full p-3 text-2xl font-bold">✓</div>
                    <div>
                        <h2 class="text-2xl font-bold text-emerald-900">¡Tu solicitud fue enviada con éxito!</h2>
                        <p class="text-emerald-800 mt-1">
                            Código de seguimiento: <span class="font-mono font-bold text-lg bg-emerald-200 px-2 py-0.5 rounded">{{ session('exito_solicitud.codigo') }}</span>
                            · Estimado: <strong>${{ session('exito_solicitud.total') }} COP</strong>. Te responderemos en menos de 15 minutos.
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('mis.solicitudes') }}" class="bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-emerald-800">Ver mis solicitudes</a>
                    @else
                        <a href="{{ route('register') }}" class="bg-slate-900 text-amber-400 px-5 py-2.5 rounded-lg font-semibold hover:bg-slate-800">Crear cuenta para hacer seguimiento</a>
                    @endauth
                    <a href="{{ route('inicio') }}" class="border border-emerald-700 text-emerald-900 px-5 py-2.5 rounded-lg font-semibold hover:bg-emerald-100">Volver al inicio</a>
                </div>
            </div>
        @endif

        <!-- ESTADO 4: ERROR DE VALIDACIÓN -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded-r-xl">
                <p class="font-bold text-red-800">Revisa los campos marcados en rojo (tus datos escritos se conservan):</p>
                <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Formulario Principal -->
            <form method="POST" action="{{ route('cotizador.store') }}" @submit="cargando = true" class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200 space-y-6">
                @csrf
                <div>
                    <h1 class="text-2xl font-serif font-bold text-slate-900">Simulador de Presupuesto CRISTAMAR</h1>
                    <p class="text-sm text-slate-500">Ajusta los parámetros y visualiza el valor estimado en tiempo real.</p>
                </div>

                <!-- Tipo de Evento -->
                <div>
                    <label class="block text-sm font-semibold mb-2">1. Tipo de evento *</label>
                    <select name="tipo_evento" x-model="tipoEvento" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-amber-500">
                        <option value="Corporativo / BTL">Corporativo / BTL ($75.000 / persona)</option>
                        <option value="Boda de autor">Boda de autor ($120.000 / persona)</option>
                        <option value="Fiesta social o 15 años">Fiesta social o 15 años ($90.000 / persona)</option>
                    </select>
                </div>

                <!-- Invitados (Slider + Input sincronizados) -->
                <div>
                    <label class="block text-sm font-semibold mb-2">2. Cantidad de invitados (20 a 1.000) *</label>
                    <div class="flex items-center gap-4">
                        <input type="range" min="20" max="1000" x-model.number="invitados" class="w-full accent-amber-500">
                        <input type="number" name="invitados" min="20" max="1000" x-model.number="invitados" class="w-28 rounded-xl border-slate-300 text-center font-bold">
                    </div>
                </div>

                <!-- Zona y Locación -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">3. Ubicación *</label>
                        <select name="zona" x-model="zona" class="w-full rounded-xl border-slate-300">
                            <option value="Sabana">Sabana (+$12.000 / persona)</option>
                            <option value="Bogota">Bogotá (Sin recargo)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Locación sugerida (opcional)</label>
                        <select name="locacion_id" class="w-full rounded-xl border-slate-300">
                            <option value="">-- A definir con asesor --</option>
                            @foreach($locaciones as $loc)
                                <option value="{{ $loc->id }}" x-show="zona === '{{ $loc->zona }}'">{{ $loc->nombre }} (Máx. {{ $loc->capacidad_max }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Adicionales -->
                <div>
                    <label class="block text-sm font-semibold mb-2">4. Servicios adicionales opcionales</label>
                    <div class="grid sm:grid-cols-3 gap-3">
                        @foreach($tarifas->whereNotIn('nombre', ['Corporativo / BTL', 'Boda de autor', 'Fiesta social o 15 años', 'Recargo Sabana']) as $ad)
                            <label class="border rounded-xl p-3 flex items-start gap-2 cursor-pointer hover:bg-amber-50/50 transition">
                                <input type="checkbox" name="adicionales[]" value="{{ $ad->id }}" x-model="adicionalesSeleccionados" class="mt-1 rounded text-amber-500 focus:ring-amber-500">
                                <div class="text-xs">
                                    <span class="font-bold block text-slate-800">{{ $ad->nombre }}</span>
                                    <span class="text-slate-500">${{ number_format($ad->precio, 0, ',', '.') }} {{ $ad->tipo_cobro === 'por_persona' ? '/ pers.' : 'fijo' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Fecha y Datos de Contacto -->
                <div class="grid sm:grid-cols-2 gap-4 pt-2 border-t">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Fecha del evento *</label>
                        <input type="date" name="fecha_evento" value="{{ old('fecha_evento', now()->addDays(15)->format('Y-m-d')) }}" required class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nombre completo *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', auth()->user()->name ?? '') }}" placeholder="Ej. Camila Mendoza" required class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Celular colombiano (10 dígitos) *</label>
                        <input type="text" name="celular" value="{{ old('celular', auth()->user()->celular ?? '') }}" placeholder="3152292638" required class="w-full rounded-xl border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Correo electrónico *</label>
                        <input type="email" name="correo" value="{{ old('correo', auth()->user()->email ?? '') }}" placeholder="camila@empresa.com" required class="w-full rounded-xl border-slate-300">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <label class="font-semibold text-sm">Comentarios o requerimientos especiales</label>
                        <span class="text-slate-500"><span x-text="comentarios.length"></span>/300</span>
                    </div>
                    <textarea name="comentarios" maxlength="300" x-model="comentarios" rows="2" class="w-full rounded-xl border-slate-300"></textarea>
                </div>

                <!-- Botón con ESTADO 1 (Reposo) y ESTADO 2 (Espera / Spinner) -->
                <button type="submit" :disabled="cargando" class="w-full bg-amber-500 hover:bg-amber-400 disabled:opacity-60 text-slate-950 font-bold py-4 rounded-xl shadow-md transition flex justify-center items-center gap-3 text-lg">
                    <svg x-show="cargando" class="animate-spin h-5 w-5 text-slate-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="cargando ? 'Guardando tu solicitud...' : 'Enviar solicitud de cotización'"></span>
                </button>
            </form>

            <!-- Tarjeta Flotante de Resumen en Vivo -->
            <aside class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg h-fit sticky top-6">
                <span class="text-xs uppercase tracking-widest text-amber-400 font-bold">Estimado en tiempo real</span>
                <div class="text-3xl sm:text-4xl font-extrabold text-white mt-2 mb-4" x-text="formatoCOP(totalEstimado) + ' COP'"></div>
                <div class="space-y-2 text-sm text-slate-300 border-t border-slate-700 pt-4">
                    <div class="flex justify-between"><span>Formato:</span><strong x-text="tipoEvento"></strong></div>
                    <div class="flex justify-between"><span>Invitados:</span><strong x-text="invitados + ' personas'"></strong></div>
                    <div class="flex justify-between"><span>Zona:</span><strong x-text="zona"></strong></div>
                </div>
                <p class="text-xs text-slate-400 mt-6 bg-slate-800 p-3 rounded-lg">
                    * Valor calculado con tarifas oficiales vigentes. El servidor valida y sella este monto al generar tu código CR-xxxx.
                </p>
            </aside>
        </div>
    </main>
</body>
</html>

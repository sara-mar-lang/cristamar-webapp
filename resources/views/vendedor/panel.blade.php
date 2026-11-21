<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel Comercial · Asesora VIP ({{ Auth::user()->name }})</h2>
            <span class="bg-amber-100 text-amber-900 text-xs font-bold px-3 py-1 rounded-full uppercase">Rol: Vendedora</span>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ abrirFormulario: false }">
        @if(session('status'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg font-medium">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">{{ $errors->first() }}</div>
        @endif

        <div class="bg-slate-900 text-white p-6 rounded-2xl flex flex-wrap justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-amber-400">¿Un cliente te escribió por WhatsApp o te llamó?</h3>
                <p class="text-sm text-slate-300">Registra directamente su cotización en el sistema con las tarifas oficiales vigentes.</p>
            </div>
            <button @click="abrirFormulario = !abrirFormulario" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-5 py-3 rounded-xl text-sm transition">
                <span x-text="abrirFormulario ? 'Cerrar Formulario' : '+ Crear Cotización para Cliente'"></span>
            </button>
        </div>

        <section x-show="abrirFormulario" class="bg-white p-6 sm:p-8 rounded-2xl shadow-md border-2 border-amber-400">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Registrar Nueva Solicitud Comercial</h3>
            <form method="POST" action="{{ route('vendedor.solicitudes.store') }}" class="grid md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold mb-1">Canal de contacto *</label>
                    <select name="canal" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Llamada Telefónica">Llamada Telefónica</option>
                        <option value="Presencial">Presencial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Nombre completo del cliente *</label>
                    <input type="text" name="nombre" required placeholder="Ej. Andrés Gómez" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Celular del cliente (10 dígitos) *</label>
                    <input type="text" name="celular" required placeholder="3105554433" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Correo electrónico del cliente *</label>
                    <input type="email" name="correo" required placeholder="andres@empresa.com" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Tipo de evento *</label>
                    <select name="tipo_evento" class="w-full rounded-lg border-gray-300 text-sm">
                        @foreach($tarifas->whereIn('nombre', ['Corporativo / BTL / Congreso', 'Boda de Autor Campestre', 'Fiesta Social VIP / 15 Años']) as $t)
                            <option value="{{ $t->nombre }}">{{ $t->nombre }} (${{ number_format($t->precio, 0, ',', '.') }}/pers.)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Invitados (20 a 1.000) *</label>
                    <input type="number" name="invitados" min="20" max="1000" value="80" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Zona *</label>
                    <select name="zona" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="Sabana">Sabana (+$12.000/pers.)</option>
                        <option value="Bogota">Bogotá</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Locación sugerida</label>
                    <select name="locacion_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">-- A definir --</option>
                        @foreach($locaciones as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Fecha del evento *</label>
                    <input type="date" name="fecha_evento" value="{{ now()->addDays(15)->format('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold mb-1">Servicios adicionales</label>
                    <div class="flex flex-wrap gap-4">
                        @foreach($tarifas->whereNotIn('nombre', ['Corporativo / BTL / Congreso', 'Boda de Autor Campestre', 'Fiesta Social VIP / 15 Años', 'Recargo Sabana']) as $ad)
                            <label class="inline-flex items-center gap-2 text-xs bg-slate-100 px-3 py-2 rounded-lg">
                                <input type="checkbox" name="adicionales[]" value="{{ $ad->id }}">
                                <span>{{ $ad->nombre }} (${{ number_format($ad->precio, 0, ',', '.') }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="bg-slate-950 text-amber-400 font-bold px-6 py-3 rounded-xl text-sm">Guardar Cotización en Sistema</button>
                </div>
            </form>
        </section>

        <section class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                <h3 class="text-lg font-bold">Bandeja de Solicitudes de Clientes</h3>
                <form method="GET" class="flex gap-2">
                    <select name="estado" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" @selected(request('estado')=='pendiente')>Pendiente</option>
                        <option value="aprobada" @selected(request('estado')=='aprobada')>Aprobada</option>
                        <option value="rechazada" @selected(request('estado')=='rechazada')>Rechazada</option>
                    </select>
                    <button class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm">Filtrar</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">Código</th>
                            <th class="px-3 py-2 text-left">Cliente</th>
                            <th class="px-3 py-2 text-left">Detalle del Evento</th>
                            <th class="px-3 py-2 text-left">Total Oficial</th>
                            <th class="px-3 py-2 text-left">Estado</th>
                            <th class="px-3 py-2 text-left">Acción Comercial</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($solicitudes as $sol)
                            <tr>
                                <td class="px-3 py-3 font-mono font-bold">{{ $sol->codigo }}</td>
                                <td class="px-3 py-3">
                                    <strong>{{ $sol->nombre }}</strong><br>
                                    <span class="text-xs text-gray-500">{{ $sol->correo }} · {{ $sol->celular }}</span>
                                    @if($sol->comentarios)<p class="text-xs text-amber-700 mt-1">{{ $sol->comentarios }}</p>@endif
                                </td>
                                <td class="px-3 py-3">
                                    {{ $sol->tipo_evento }} · {{ $sol->invitados }} inv. · {{ $sol->zona }} ({{ $sol->fecha_evento }})
                                    @if($sol->locacion)<span class="block text-xs text-slate-500">Locación: {{ $sol->locacion->nombre }}</span>@endif
                                    @if(in_array($sol->id, $conflictosIds))
                                        <span class="inline-block mt-1 bg-red-100 text-red-800 text-[11px] font-bold px-2 py-0.5 rounded">⚠️ Fecha ocupada en esta locación</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 font-bold">${{ number_format($sol->total_estimado, 0, ',', '.') }}</td>
                                <td class="px-3 py-3 uppercase text-xs font-bold">{{ $sol->estado }}</td>
                                <td class="px-3 py-3">
                                    @if($sol->estado === 'pendiente')
                                        <div class="flex flex-col gap-2">
                                            <form method="POST" action="{{ route('vendedor.aprobar', $sol) }}">
                                                @csrf
                                                <button class="bg-emerald-600 text-white px-3 py-1 rounded text-xs font-bold">Aprobar</button>
                                            </form>
                                            <form method="POST" action="{{ route('vendedor.rechazar', $sol) }}" class="flex gap-1">
                                                @csrf
                                                <input type="text" name="motivo" placeholder="Motivo (mín. 10 car.)" required minlength="10" class="text-xs rounded border-gray-300 px-2 py-1">
                                                <button class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">Rechazar</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">{{ $sol->motivo_estado }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>

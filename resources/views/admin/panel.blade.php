<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel Ejecutivo · Administrador General</h2>
            <span class="bg-slate-900 text-amber-400 text-xs font-bold px-3 py-1 rounded-full uppercase">Control Total</span>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10" x-data="{ abrirFormAdmin: false }">
        @if(session('status'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg font-medium">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">{{ $errors->first() }}</div>
        @endif

        <div class="bg-slate-900 text-white p-5 rounded-2xl flex flex-wrap justify-between items-center gap-4">
            <div>
                <h3 class="text-base font-bold text-amber-400">Atención Ejecutiva / Contingencia Comercial</h3>
                <p class="text-xs text-slate-300">Registra directamente una solicitud VIP si una asesora no está disponible.</p>
            </div>
            <button @click="abrirFormAdmin = !abrirFormAdmin" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs uppercase">
                <span x-text="abrirFormAdmin ? 'Cerrar Formulario' : '+ Crear Cotización para Cliente'"></span>
            </button>
        </div>

        <section x-show="abrirFormAdmin" class="bg-white p-6 rounded-2xl shadow-md border-2 border-amber-400">
            <h3 class="text-base font-bold text-slate-900 mb-4">Registrar Cotización Ejecutiva</h3>
            <form method="POST" action="{{ route('vendedor.solicitudes.store') }}" class="grid md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold mb-1">Canal *</label>
                    <select name="canal" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="WhatsApp">WhatsApp VIP</option>
                        <option value="Llamada Telefónica">Llamada Directa</option>
                        <option value="Presencial">Reunión Presencial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Nombre del cliente *</label>
                    <input type="text" name="nombre" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Celular (10 dígitos) *</label>
                    <input type="text" name="celular" required placeholder="3152292638" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Correo electrónico *</label>
                    <input type="email" name="correo" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Tipo de evento *</label>
                    <select name="tipo_evento" class="w-full rounded-lg border-gray-300 text-sm">
                        @foreach($tarifas->whereIn('nombre', ['Corporativo / BTL / Congreso', 'Boda de Autor Campestre', 'Fiesta Social VIP / 15 Años']) as $t)
                            <option value="{{ $t->nombre }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Invitados *</label>
                    <input type="number" name="invitados" min="20" max="1000" value="100" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Zona *</label>
                    <select name="zona" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="Sabana">Sabana</option>
                        <option value="Bogota">Bogotá</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Locación</label>
                    <select name="locacion_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">-- A definir --</option>
                        @foreach($locaciones as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Fecha *</label>
                    <input type="date" name="fecha_evento" value="{{ now()->addDays(15)->format('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="bg-slate-950 text-amber-400 font-bold px-5 py-2.5 rounded-xl text-xs uppercase">Guardar en Sistema</button>
                </div>
            </form>
        </section>

        <div class="grid md:grid-cols-2 gap-8">
            <section class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-lg font-bold mb-1">Editar Tarifas Oficiales (Exclusivo Admin)</h3>
                <p class="text-xs text-gray-500 mb-4">Cualquier cambio actualiza de inmediato el simulador público y comercial.</p>
                <div class="space-y-3">
                    @foreach($tarifas as $tarifa)
                        <form method="POST" action="{{ route('admin.tarifas.update', $tarifa) }}" class="flex justify-between items-center gap-2 border-b pb-2">
                            @csrf @method('PATCH')
                            <span class="text-sm font-medium">{{ $tarifa->nombre }}</span>
                            <div class="flex items-center gap-2">
                                <input type="number" name="precio" value="{{ $tarifa->precio }}" min="1" max="9999999" class="w-28 text-sm rounded border-gray-300">
                                <button class="bg-slate-900 text-amber-400 font-bold px-3 py-1.5 rounded text-xs">Guardar</button>
                            </div>
                        </form>
                    @endforeach
                </div>
            </section>

            <section class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-lg font-bold mb-1">Agregar Nueva Locación</h3>
                <p class="text-xs text-gray-500 mb-4">Haciendas en la Sabana y salones VIP en Bogotá.</p>
                <form method="POST" action="{{ route('admin.locaciones.store') }}" autocomplete="off" class="space-y-3 mb-6">
                    @csrf
                    <input type="text" name="nombre" autocomplete="off" placeholder="Nombre de Hacienda o Salón" required class="w-full rounded-lg border-gray-300 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="capacidad_max" placeholder="Capacidad (20-1000)" min="20" max="1000" required class="rounded-lg border-gray-300 text-sm">
                        <select name="zona" class="rounded-lg border-gray-300 text-sm">
                            <option value="Sabana">Sabana</option>
                            <option value="Bogota">Bogotá</option>
                        </select>
                    </div>
                    <button class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm w-full">Agregar al Catálogo</button>
                </form>
                <ul class="text-xs space-y-1 text-gray-600 max-h-40 overflow-y-auto">
                    @foreach($locaciones as $loc)
                        <li>• <strong>{{ $loc->nombre }}</strong> ({{ $loc->zona }} - {{ $loc->capacidad_max }} pers.)</li>
                    @endforeach
                </ul>
            </section>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <section class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-lg font-bold mb-1">Equipo de Vendedoras / Asesoras</h3>
                <p class="text-xs text-gray-500 mb-4">Crea cuentas comerciales, audita su gestión o elimina el perfil si renuncia.</p>
                
                <form method="POST" action="{{ route('admin.vendedoras.store') }}" autocomplete="off" class="grid grid-cols-2 gap-2 mb-6 bg-slate-50 p-3 rounded-lg border">
                    @csrf
                    <input type="text" name="name" placeholder="Nombre completo" required class="rounded border-gray-300 text-xs">
                    <input type="email" name="email" placeholder="Correo corporativo" required class="rounded border-gray-300 text-xs">
                    <input type="text" name="celular" placeholder="Celular (3...)" required class="rounded border-gray-300 text-xs">
                    <input type="password" name="password" placeholder="Contraseña (mín. 8)" required class="rounded border-gray-300 text-xs">
                    <button class="col-span-2 bg-slate-900 text-amber-400 font-bold py-2 rounded text-xs">+ Registrar Nueva Vendedora</button>
                </form>

                <div class="divide-y text-sm">
                    @forelse($vendedoras as $vend)
                        <div class="py-3 flex justify-between items-center gap-2">
                            <div>
                                <strong>{{ $vend->name }}</strong>
                                <span class="block text-xs text-gray-500">{{ $vend->email }} · {{ $vend->celular }}</span>
                                <span class="inline-block mt-1 text-[11px] bg-amber-100 text-amber-900 px-2 py-0.5 rounded font-semibold">
                                    {{ $vend->total_gestionadas }} gestiones · ${{ number_format($vend->monto_aprobado, 0, ',', '.') }} aprobados
                                </span>
                            </div>
                            <form method="POST" action="{{ route('admin.vendedoras.destroy', $vend) }}" onsubmit="return confirm('Esta asesora tiene {{ $vend->total_gestionadas }} gestiones registradas (su historial se conservará). ¿Confirmas eliminar su perfil de acceso?');">
                                @csrf @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-bold">Eliminar Perfil</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">No hay vendedoras registradas.</p>
                    @endforelse
                </div>
            </section>

            <section class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-lg font-bold mb-1">Moderación de Cuentas de Clientes</h3>
                <p class="text-xs text-gray-500 mb-4">Veta o suspende clientes que incumplan normas o sean irrespetuosos con el equipo.</p>
                <div class="divide-y text-sm">
                    @forelse($clientes as $cli)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <strong>{{ $cli->name }}</strong>
                                <span class="ml-2 px-2 py-0.5 text-[10px] font-bold rounded-full {{ $cli->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $cli->activo ? 'ACTIVO' : 'VETADO' }}
                                </span>
                                <span class="block text-xs text-gray-500">{{ $cli->email }} · {{ $cli->celular }}</span>
                            </div>
                            <form method="POST" action="{{ route('admin.clientes.vetar', $cli) }}">
                                @csrf
                                <button class="{{ $cli->activo ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-emerald-600 text-white' }} px-3 py-1.5 rounded text-xs font-bold">
                                    {{ $cli->activo ? 'Vetar / Suspender' : 'Reactivar Cuenta' }}
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">No hay clientes registrados.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="text-lg font-bold mb-4">Auditoría Global de Solicitudes</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">Código</th>
                            <th class="px-3 py-2 text-left">Cliente</th>
                            <th class="px-3 py-2 text-left">Evento</th>
                            <th class="px-3 py-2 text-left">Total</th>
                            <th class="px-3 py-2 text-left">Estado / Trazabilidad</th>
                            <th class="px-3 py-2 text-left">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($solicitudes as $sol)
                            <tr>
                                <td class="px-3 py-3 font-mono font-bold">{{ $sol->codigo }}</td>
                                <td class="px-3 py-3"><strong>{{ $sol->nombre }}</strong><br><span class="text-xs text-gray-500">{{ $sol->correo }}</span></td>
                                <td class="px-3 py-3">
                                    {{ $sol->tipo_evento }} ({{ $sol->invitados }} inv.) · {{ $sol->fecha_evento }}
                                    @if($sol->locacion)<span class="block text-xs text-slate-500">Locación: {{ $sol->locacion->nombre }}</span>@endif
                                    @if(in_array($sol->id, $conflictosIds))
                                        <span class="block mt-1 bg-red-100 text-red-800 text-[11px] font-bold px-2 py-0.5 rounded w-fit">⚠️ Choque de fecha en esta misma locación</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 font-bold">${{ number_format($sol->total_estimado, 0, ',', '.') }}</td>
                                <td class="px-3 py-3 text-xs">
                                    <span class="font-bold uppercase">{{ $sol->estado }}</span>
                                    @if($sol->motivo_estado)<p class="text-gray-500">{{ $sol->motivo_estado }}</p>@endif
                                </td>
                                <td class="px-3 py-3">
                                    @if($sol->estado === 'pendiente')
                                        <div class="flex flex-col gap-2">
                                            <form method="POST" action="{{ route('admin.aprobar', $sol) }}">
                                                @csrf
                                                <button class="bg-emerald-600 text-white px-3 py-1 rounded text-xs font-bold">Aprobar</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.rechazar', $sol) }}" class="flex gap-1">
                                                @csrf
                                                <input type="text" name="motivo" placeholder="Motivo (mín. 10 car.)" required minlength="10" class="text-xs rounded border-gray-300 px-2 py-1">
                                                <button class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">Rechazar</button>
                                            </form>
                                        </div>
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

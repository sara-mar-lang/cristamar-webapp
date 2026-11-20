<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel Administrador CRISTAMAR</h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        @if(session('status'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">{{ $errors->first() }}</div>
        @endif

        <section class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                <h3 class="text-lg font-bold">Solicitudes de Clientes</h3>
                <form method="GET" class="flex gap-2">
                    <select name="estado" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" @selected(request('estado')=='pendiente')>Pendiente</option>
                        <option value="aprobada" @selected(request('estado')=='aprobada')>Aprobada</option>
                        <option value="rechazada" @selected(request('estado')=='rechazada')>Rechazada</option>
                    </select>
                    <select name="zona" class="rounded-lg border-gray-300 text-sm">
                        <option value="">Todas las zonas</option>
                        <option value="Bogota" @selected(request('zona')=='Bogota')>Bogotá</option>
                        <option value="Sabana" @selected(request('zona')=='Sabana')>Sabana</option>
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
                            <th class="px-3 py-2 text-left">Detalle</th>
                            <th class="px-3 py-2 text-left">Total</th>
                            <th class="px-3 py-2 text-left">Estado</th>
                            <th class="px-3 py-2 text-left">Gestión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($solicitudes as $sol)
                            <tr class="{{ $sol->estado === 'pendiente' ? 'bg-amber-50/40' : '' }}">
                                <td class="px-3 py-3 font-mono font-bold">{{ $sol->codigo }}</td>
                                <td class="px-3 py-3">
                                    <strong>{{ $sol->nombre }}</strong><br>
                                    <span class="text-xs text-gray-500">{{ $sol->correo }} · {{ $sol->celular }}</span>
                                </td>
                                <td class="px-3 py-3">{{ $sol->tipo_evento }} · {{ $sol->invitados }} inv. · {{ $sol->zona }} ({{ $sol->fecha_evento }})</td>
                                <td class="px-3 py-3 font-bold">${{ number_format($sol->total_estimado, 0, ',', '.') }}</td>
                                <td class="px-3 py-3 uppercase text-xs font-bold">{{ $sol->estado }}</td>
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

        <div class="grid md:grid-cols-2 gap-8">
            <section class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-lg font-bold mb-4">Editar Tarifas Vigentes</h3>
                <div class="space-y-3">
                    @foreach($tarifas as $tarifa)
                        <form method="POST" action="{{ route('admin.tarifas.update', $tarifa) }}" class="flex justify-between items-center gap-2 border-b pb-2">
                            @csrf @method('PATCH')
                            <span class="text-sm font-medium">{{ $tarifa->nombre }}</span>
                            <div class="flex items-center gap-2">
                                <input type="number" name="precio" value="{{ $tarifa->precio }}" min="1" max="9999999" class="w-28 text-sm rounded border-gray-300">
                                <button class="bg-slate-800 text-white px-3 py-1.5 rounded text-xs">Guardar</button>
                            </div>
                        </form>
                    @endforeach
                </div>
            </section>

            <section class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="text-lg font-bold mb-4">Agregar Nueva Locación</h3>
                <form method="POST" action="{{ route('admin.locaciones.store') }}" class="space-y-3 mb-6">
                    @csrf
                    <input type="text" name="nombre" placeholder="Nombre de Hacienda o Salón" required class="w-full rounded-lg border-gray-300 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="capacidad_max" placeholder="Capacidad (20-1000)" min="20" max="1000" required class="rounded-lg border-gray-300 text-sm">
                        <select name="zona" class="rounded-lg border-gray-300 text-sm">
                            <option value="Sabana">Sabana</option>
                            <option value="Bogota">Bogotá</option>
                        </select>
                    </div>
                    <button class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm w-full">Agregar al Catálogo</button>
                </form>
                <ul class="text-sm space-y-1 text-gray-600">
                    @foreach($locaciones as $loc)
                        <li>• <strong>{{ $loc->nombre }}</strong> ({{ $loc->zona }} - {{ $loc->capacidad_max }} pers.)</li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</x-app-layout>

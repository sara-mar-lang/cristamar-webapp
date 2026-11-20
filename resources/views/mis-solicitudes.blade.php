<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Solicitudes de Cotización</h2>
            <a href="{{ route('cotizador.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-sm">+ Nueva Cotización</a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-lg mb-6">{{ session('status') }}</div>
        @endif

        <div class="bg-white shadow-sm rounded-xl overflow-x-auto border">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Código</th>
                        <th class="px-4 py-3 text-left">Evento</th>
                        <th class="px-4 py-3 text-left">Fecha / Zona</th>
                        <th class="px-4 py-3 text-left">Estimado</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($solicitudes as $sol)
                        <tr>
                            <td class="px-4 py-4 font-mono font-bold">{{ $sol->codigo }}</td>
                            <td class="px-4 py-4">{{ $sol->tipo_evento }} ({{ $sol->invitados }} inv.)</td>
                            <td class="px-4 py-4">{{ $sol->fecha_evento }} · {{ $sol->zona }}</td>
                            <td class="px-4 py-4 font-bold">${{ number_format($sol->total_estimado, 0, ',', '.') }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase
                                    {{ $sol->estado === 'pendiente' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $sol->estado === 'aprobada' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ in_array($sol->estado, ['rechazada', 'cancelada']) ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $sol->estado }}
                                </span>
                                @if($sol->motivo_estado)
                                    <p class="text-xs text-gray-500 mt-1">{{ $sol->motivo_estado }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right space-x-2">
                                <a href="{{ route('cotizador.index', ['repetir' => $sol->id]) }}" class="text-indigo-600 hover:underline font-semibold">Repetir</a>
                                @if($sol->estado === 'pendiente')
                                    <form method="POST" action="{{ route('mis.solicitudes.cancelar', $sol) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="motivo" value="Cambio de planes del cliente">
                                        <button type="submit" onclick="return confirm('¿Seguro deseas cancelar esta solicitud?')" class="text-red-600 hover:underline font-semibold">Cancelar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aún no tienes cotizaciones registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

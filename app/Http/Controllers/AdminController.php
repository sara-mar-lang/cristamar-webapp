<?php
namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Models\Locacion;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index(Request $request) {
        $query = Solicitud::with(['locacion', 'lineas.tarifa', 'user'])
            ->orderByRaw("FIELD(estado, 'pendiente', 'aprobada', 'rechazada', 'cancelada')")
            ->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('zona')) {
            $query->where('zona', $request->zona);
        }

        $solicitudes = $query->paginate(20);
        $tarifas = Tarifa::all();
        $locaciones = Locacion::all();

        return view('admin.panel', compact('solicitudes', 'tarifas', 'locaciones'));
    }

    public function aprobar(Request $request, Solicitud $solicitud) {
        if ($solicitud->estado !== 'pendiente') return back();
        $request->validate(['comentario' => 'nullable|string|max:300']);
        $solicitud->update([
            'estado' => 'aprobada',
            'motivo_estado' => 'Aprobada por ' . auth()->user()->name . ' (' . now()->format('d/m/Y H:i') . '). ' . ($request->comentario ?? ''),
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} aprobada.");
    }

    public function rechazar(Request $request, Solicitud $solicitud) {
        if ($solicitud->estado !== 'pendiente') return back();
        $request->validate([
            'motivo' => 'required|string|min:10|max:300'
        ], [
            'motivo.min' => 'Escribe el motivo del rechazo (mínimo 10 caracteres).'
        ]);
        $solicitud->update([
            'estado' => 'rechazada',
            'motivo_estado' => 'Rechazada por ' . auth()->user()->name . ': ' . $request->motivo,
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} rechazada.");
    }

    public function actualizarTarifa(Request $request, Tarifa $tarifa) {
        $validated = $request->validate([
            'precio' => 'required|integer|min:1|max:9999999',
        ], [
            'precio.min' => 'Ingresa un precio entre 1 y 9.999.999.',
            'precio.max' => 'Ingresa un precio entre 1 y 9.999.999.',
        ]);
        $tarifa->update($validated);
        return back()->with('status', "Tarifa '{$tarifa->nombre}' actualizada.");
    }

    public function crearLocacion(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|min:3|max:80',
            'capacidad_max' => 'required|integer|min:20|max:1000',
            'zona' => 'required|in:Bogota,Sabana',
        ]);
        Locacion::create($validated);
        return back()->with('status', 'Locación agregada al catálogo.');
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tarifa;
use App\Models\Locacion;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {
    public function index(Request $request) {
        $query = Solicitud::with(['locacion', 'lineas.tarifa'])
            ->orderByRaw("FIELD(estado, 'pendiente', 'aprobada', 'rechazada', 'cancelada')")
            ->latest();

        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('zona')) $query->where('zona', $request->zona);

        $solicitudes = $query->paginate(20);
        $tarifas = Tarifa::all();
        $locaciones = Locacion::all();

        $vendedoras = User::where('rol', 'vendedor')->latest()->get()->map(function ($v) {
            $v->total_gestionadas = Solicitud::where('motivo_estado', 'like', "%{$v->name}%")
                ->orWhere('comentarios', 'like', "%{$v->name}%")
                ->count();
            $v->monto_aprobado = Solicitud::where('estado', 'aprobada')
                ->where('motivo_estado', 'like', "%{$v->name}%")
                ->sum('total_estimado');
            return $v;
        });

        $clientes = User::where('rol', 'cliente')->latest()->get();

        // Alerta estricta: ÚNICAMENTE si tienen la misma locación específica (locacion_id) Y el mismo día (fecha_evento)
        $conflictosIds = [];
        foreach ($solicitudes as $s) {
            if ($s->estado === 'pendiente' && !is_null($s->locacion_id)) {
                $existeCruce = Solicitud::where('id', '!=', $s->id)
                    ->where('locacion_id', $s->locacion_id)
                    ->where('fecha_evento', $s->fecha_evento)
                    ->whereIn('estado', ['pendiente', 'aprobada'])
                    ->exists();

                if ($existeCruce) {
                    $conflictosIds[] = $s->id;
                }
            }
        }

        return view('admin.panel', compact('solicitudes', 'tarifas', 'locaciones', 'vendedoras', 'clientes', 'conflictosIds'));
    }

    public function aprobar(Solicitud $solicitud) {
        if ($solicitud->estado !== 'pendiente') return back();
        $solicitud->update([
            'estado' => 'aprobada',
            'motivo_estado' => 'Aprobada por Admin ' . auth()->user()->name . ' (' . now()->format('d/m/Y H:i') . ')',
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} aprobada.");
    }

    public function rechazar(Request $request, Solicitud $solicitud) {
        if ($solicitud->estado !== 'pendiente') return back();
        $request->validate([
            'motivo' => ['required', 'string', 'min:10', 'max:300'],
        ], [
            'motivo.min' => 'El motivo de rechazo debe tener al menos 10 caracteres.',
        ]);
        $solicitud->update([
            'estado' => 'rechazada',
            'motivo_estado' => 'Rechazada por Admin ' . auth()->user()->name . ': ' . $request->motivo,
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} rechazada.");
    }

    public function actualizarTarifa(Request $request, Tarifa $tarifa) {
        $validated = $request->validate(['precio' => ['required', 'integer', 'min:1', 'max:9999999']]);
        $tarifa->update($validated);
        return back()->with('status', "Tarifa '{$tarifa->nombre}' actualizada a $" . number_format($validated['precio'], 0, ',', '.'));
    }

    public function crearLocacion(Request $request) {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:80'],
            'capacidad_max' => ['required', 'integer', 'min:20', 'max:1000'],
            'zona' => ['required', 'in:Bogota,Sabana'],
        ]);
        Locacion::create($validated);
        return back()->with('status', 'Locación agregada al catálogo.');
    }

    public function crearVendedora(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:60'],
            'email' => ['required', 'email', 'unique:users,email'],
            'celular' => ['required', 'regex:/^3[0-9]{9}$/'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'celular' => $validated['celular'],
            'rol' => 'vendedor',
            'activo' => true,
            'email_verified_at' => now(),
            'password' => Hash::make($validated['password']),
        ]);
        return back()->with('status', "Perfil de vendedora '{$validated['name']}' creado.");
    }

    public function eliminarVendedora(User $user) {
        if ($user->rol !== 'vendedor') abort(403);
        $nombre = $user->name;
        $user->delete();
        return back()->with('status', "El perfil de la vendedora '{$nombre}' ha sido eliminado del sistema.");
    }

    public function toggleVetarCliente(User $user) {
        if ($user->rol !== 'cliente') abort(403);
        $user->update(['activo' => !$user->activo]);
        $accion = $user->activo ? 'reactivado' : 'vetado y suspendido';
        return back()->with('status', "El cliente '{$user->name}' ha sido {$accion}.");
    }
}

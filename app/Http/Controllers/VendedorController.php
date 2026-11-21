<?php
namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Models\Locacion;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendedorController extends Controller {
    public function index(Request $request) {$query = Solicitud::with(['locacion', 'lineas.tarifa'])
            ->orderByRaw("FIELD(estado, 'pendiente', 'aprobada', 'rechazada', 'cancelada')")
            ->latest();

        if ($request->filled('estado')) $query->where('estado',$request->estado);
        if ($request->filled('zona')) $query->where('zona',$request->zona);

        $solicitudes =$query->paginate(20);
        $tarifas = Tarifa::where('activa', true)->get();$locaciones = Locacion::all();

        // Detectar conflictos: solicitudes pendientes cuya locación y fecha ya tienen otra solicitud aprobada
        $aprobadasOcupadas = Solicitud::where('estado', 'aprobada')
            ->whereNotNull('locacion_id')
            ->get()
            ->map(fn($s) => $s->locacion_id . '_' .$s->fecha_evento)
            ->toArray();

        $conflictosIds =$solicitudes->filter(function ($s) use ($aprobadasOcupadas) {
            return $s->estado === 'pendiente'
                && $s->locacion_id
                && in_array($s->locacion_id . '_' . $s->fecha_evento,$aprobadasOcupadas);
        })->pluck('id')->toArray();

        return view('vendedor.panel', compact('solicitudes', 'tarifas', 'locaciones', 'conflictosIds'));
    }

    public function crearSolicitudCliente(Request $request) {$minFecha = now()->addDays(1)->format('Y-m-d');
        $validated =$request->validate([
            'tipo_evento' => 'required|string',
            'invitados' => 'required|integer|min:20|max:1000',
            'zona' => 'required|in:Bogota,Sabana',
            'locacion_id' => 'nullable|exists:locaciones,id',
            'fecha_evento' => "required|date|after_or_equal:{$minFecha}",
            'nombre' => ['required', 'string', 'min:3', 'max:60'],
            'celular' => ['required', 'regex:/^3[0-9]{9}$/'],
            'correo' => 'required|email|max:100',
            'canal' => 'required|in:WhatsApp,Llamada Telefónica,Presencial',
            'comentarios' => 'nullable|string|max:250',
            'adicionales' => 'nullable|array',
            'adicionales.*' => 'exists:tarifas,id',
        ], [
            'celular.regex' => 'El celular del cliente debe tener 10 dígitos y empezar por 3.',
        ]);

        $tarifaEvento = Tarifa::where('nombre', $validated['tipo_evento'])->firstOrFail();$total = $validated['invitados'] *$tarifaEvento->precio;

        if ($validated['zona'] === 'Sabana') {$recargo = Tarifa::where('nombre', 'Recargo Sabana')->first();
            if ($recargo)$total += $validated['invitados'] *$recargo->precio;
        }

        $lineasParaGuardar = [];
        if (!empty($validated['adicionales'])) {
            $adicionalesDb = Tarifa::whereIn('id',$validated['adicionales'])->get();
            foreach ($adicionalesDb as $ad) {$cantidad = $ad->tipo_cobro === 'por_persona' ?$validated['invitados'] : 1;
                $subtotal = $cantidad * $ad->precio;
                $total +=$subtotal;
                $lineasParaGuardar[] = [
                    'tarifa_id' => $ad->id,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ];
            }
        }

        $clienteExistente = User::where('email',$validated['correo'])->where('rol', 'cliente')->first();

        $solicitud = DB::transaction(function () use ($validated, $total,$lineasParaGuardar, $clienteExistente) {$nextId = (Solicitud::max('id') ?? 0) + 1;
            $codigo = 'CR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $sol = Solicitud::create([
                'codigo' => $codigo,
                'user_id' => $clienteExistente?->id,
                'tipo_evento' => $validated['tipo_evento'],
                'invitados' => $validated['invitados'],
                'zona' => $validated['zona'],
                'locacion_id' => $validated['locacion_id'] ?? null,
                'fecha_evento' => $validated['fecha_evento'],
                'nombre' => $validated['nombre'],
                'celular' => $validated['celular'],
                'correo' => $validated['correo'],
                'comentarios' => "[Registrada por " . auth()->user()->name . " vía {$validated['canal']}] " . ($validated['comentarios'] ?? ''),
                'total_estimado' => $total,
                'estado' => 'pendiente',
            ]);

            foreach ($lineasParaGuardar as$linea) {
                $sol->lineas()->create($linea);
            }

            return $sol;
        });

        return back()->with('status', "Cotización {$solicitud->codigo} creada para {$solicitud->nombre} por $" . number_format($total, 0, ',', '.') . " COP.");
    }

    public function aprobar(Solicitud $solicitud) {
        if ($solicitud->estado !== 'pendiente') return back();$solicitud->update([
            'estado' => 'aprobada',
            'motivo_estado' => 'Aprobada por ' . auth()->user()->name . ' (' . now()->format('d/m/Y H:i') . ')',
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} aprobada.");
    }

    public function rechazar(Request $request, Solicitud$solicitud) {
        if ($solicitud->estado !== 'pendiente') return back();
        $request->validate(['motivo' => 'required\vert{}string\vert{}min:10\vert{}max:300']);$solicitud->update([
            'estado' => 'rechazada',
            'motivo_estado' => 'Rechazada por ' . auth()->user()->name . ': ' . $request->motivo,
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} rechazada.");
    }
}

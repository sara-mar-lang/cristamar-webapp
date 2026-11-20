<?php
namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Models\Locacion;
use App\Models\Solicitud;
use App\Models\LineaServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizadorController extends Controller {
    public function inicio() {
        $locaciones = Locacion::all();
        $tarifas = Tarifa::where('activa', true)->get();
        return view('welcome', compact('locaciones', 'tarifas'));
    }

    public function index(Request $request) {
        $tarifas = Tarifa::where('activa', true)->get();
        $locaciones = Locacion::all();
        $repetir = null;
        if ($request->has('repetir') && auth()->check()) {
            $repetir = Solicitud::with('lineas')->where('id', $request->repetir)->where('user_id', auth()->id())->first();
        }
        return view('cotizador', compact('tarifas', 'locaciones', 'repetir'));
    }

    public function store(Request $request) {
        $minFecha = now()->addDays(7)->format('Y-m-d');
        $maxFecha = now()->addYears(2)->format('Y-m-d');

        $validated = $request->validate([
            'tipo_evento' => 'required|string',
            'invitados' => 'required|integer|min:20|max:1000',
            'zona' => 'required|in:Bogota,Sabana',
            'locacion_id' => 'nullable|exists:locaciones,id',
            'fecha_evento' => "required|date|after_or_equal:{$minFecha}|before_or_equal:{$maxFecha}",
            'nombre' => ['required', 'string', 'min:3', 'max:60', 'regex:/^[\pL\s]+$/u'],
            'celular' => ['required', 'regex:/^3[0-9]{9}$/'],
            'correo' => 'required|email|max:100',
            'comentarios' => 'nullable|string|max:300',
            'adicionales' => 'nullable|array',
            'adicionales.*' => 'exists:tarifas,id',
        ], [
            'nombre.regex' => 'Ingresa tu nombre completo (solo letras y espacios, mínimo 3 caracteres).',
            'celular.regex' => 'El celular debe tener 10 dígitos y empezar por 3.',
            'fecha_evento.after_or_equal' => 'Elige una fecha con al menos 7 días de anticipación.',
            'invitados.min' => 'Ingresa entre 20 y 1.000 invitados.',
            'invitados.max' => 'Ingresa entre 20 y 1.000 invitados.',
        ]);

        // Recálculo estricto en el servidor (nunca confiar en el total del navegador)
        $tarifaEvento = Tarifa::where('nombre', $validated['tipo_evento'])->firstOrFail();
        $total = $validated['invitados'] * $tarifaEvento->precio;

        if ($validated['zona'] === 'Sabana') {
            $recargo = Tarifa::where('nombre', 'Recargo Sabana')->first();
            if ($recargo) {
                $total += $validated['invitados'] * $recargo->precio;
            }
        }

        $lineasParaGuardar = [];
        if (!empty($validated['adicionales'])) {
            $adicionalesDb = Tarifa::whereIn('id', $validated['adicionales'])->get();
            foreach ($adicionalesDb as $ad) {
                $cantidad = $ad->tipo_cobro === 'por_persona' ? $validated['invitados'] : 1;
                $subtotal = $cantidad * $ad->precio;
                $total += $subtotal;
                $lineasParaGuardar[] = [
                    'tarifa_id' => $ad->id,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ];
            }
        }

        $solicitud = DB::transaction(function () use ($validated, $total, $lineasParaGuardar) {
            $nextId = (Solicitud::max('id') ?? 0) + 1;
            $codigo = 'CR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $sol = Solicitud::create([
                'codigo' => $codigo,
                'user_id' => auth()->id(),
                'tipo_evento' => $validated['tipo_evento'],
                'invitados' => $validated['invitados'],
                'zona' => $validated['zona'],
                'locacion_id' => $validated['locacion_id'] ?? null,
                'fecha_evento' => $validated['fecha_evento'],
                'nombre' => $validated['nombre'],
                'celular' => $validated['celular'],
                'correo' => $validated['correo'],
                'comentarios' => $validated['comentarios'] ?? null,
                'total_estimado' => $total,
                'estado' => 'pendiente',
            ]);

            foreach ($lineasParaGuardar as $linea) {
                $sol->lineas()->create($linea);
            }

            return $sol;
        });

        return redirect()->route('cotizador.index')->with('exito_solicitud', [
            'codigo' => $solicitud->codigo,
            'total' => number_format($solicitud->total_estimado, 0, ',', '.'),
            'nombre' => $solicitud->nombre,
            'correo' => $solicitud->correo,
            'celular' => $solicitud->celular,
        ]);
    }

    public function misSolicitudes() {
        $solicitudes = Solicitud::with(['locacion', 'lineas.tarifa'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('mis-solicitudes', compact('solicitudes'));
    }

    public function cancelar(Request $request, Solicitud $solicitud) {
        if ($solicitud->user_id !== auth()->id() || $solicitud->estado !== 'pendiente') {
            abort(403);
        }
        $request->validate(['motivo' => 'required|string|min:5|max:250']);
        $solicitud->update([
            'estado' => 'cancelada',
            'motivo_estado' => 'Cancelada por cliente: ' . $request->motivo,
        ]);
        return back()->with('status', "Solicitud {$solicitud->codigo} cancelada correctamente.");
    }
}

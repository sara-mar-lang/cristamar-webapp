<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CotizadorController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CotizadorController::class, 'inicio'])->name('inicio');
Route::get('/cotizador', [CotizadorController::class, 'index'])->name('cotizador.index');
Route::post('/cotizador', [CotizadorController::class, 'store'])->name('cotizador.store');

Route::get('/dashboard', function () {
    if (auth()->user()->rol === 'administrador') {
        return redirect()->route('admin.panel');
    }
    return redirect()->route('mis.solicitudes');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/mis-solicitudes', [CotizadorController::class, 'misSolicitudes'])->name('mis.solicitudes');
    Route::post('/mis-solicitudes/{solicitud}/cancelar', [CotizadorController::class, 'cancelar'])->name('mis.solicitudes.cancelar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/panel', [AdminController::class, 'index'])->name('admin.panel');
    Route::post('/solicitudes/{solicitud}/aprobar', [AdminController::class, 'aprobar'])->name('admin.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [AdminController::class, 'rechazar'])->name('admin.rechazar');
    Route::patch('/tarifas/{tarifa}', [AdminController::class, 'actualizarTarifa'])->name('admin.tarifas.update');
    Route::post('/locaciones', [AdminController::class, 'crearLocacion'])->name('admin.locaciones.store');
});

require __DIR__.'/auth.php';

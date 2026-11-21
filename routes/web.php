<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CotizadorController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CotizadorController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [CotizadorController::class, 'nosotros'])->name('nosotros');
Route::get('/servicios', [CotizadorController::class, 'servicios'])->name('servicios');
Route::get('/locaciones', [CotizadorController::class, 'locaciones'])->name('locaciones');
Route::get('/cotizador', [CotizadorController::class, 'index'])->name('cotizador.index');
Route::post('/cotizador', [CotizadorController::class, 'store'])->name('cotizador.store');

Route::get('/dashboard', function () {
    if (auth()->user()->rol === 'administrador') return redirect()->route('admin.panel');
    if (auth()->user()->rol === 'vendedor') return redirect()->route('vendedor.panel');
    return redirect()->route('mis.solicitudes');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/mis-solicitudes', [CotizadorController::class, 'misSolicitudes'])->name('mis.solicitudes');
    Route::post('/mis-solicitudes/{solicitud}/cancelar', [CotizadorController::class, 'cancelar'])->name('mis.solicitudes.cancelar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas Exclusivas de Vendedora
Route::middleware(['auth', 'vendedor'])->prefix('vendedor')->group(function () {
    Route::get('/panel', [VendedorController::class, 'index'])->name('vendedor.panel');
    Route::post('/solicitudes/crear', [VendedorController::class, 'crearSolicitudCliente'])->name('vendedor.solicitudes.store');
    Route::post('/solicitudes/{solicitud}/aprobar', [VendedorController::class, 'aprobar'])->name('vendedor.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [VendedorController::class, 'rechazar'])->name('vendedor.rechazar');
});

// Rutas Exclusivas de Administrador
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/panel', [AdminController::class, 'index'])->name('admin.panel');
    Route::post('/solicitudes/{solicitud}/aprobar', [AdminController::class, 'aprobar'])->name('admin.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [AdminController::class, 'rechazar'])->name('admin.rechazar');
    Route::patch('/tarifas/{tarifa}', [AdminController::class, 'actualizarTarifa'])->name('admin.tarifas.update');
    Route::post('/locaciones', [AdminController::class, 'crearLocacion'])->name('admin.locaciones.store');
    Route::post('/vendedoras', [AdminController::class, 'crearVendedora'])->name('admin.vendedoras.store');
    Route::delete('/vendedoras/{user}', [AdminController::class, 'eliminarVendedora'])->name('admin.vendedoras.destroy');
    Route::post('/clientes/{user}/vetar', [AdminController::class, 'toggleVetarCliente'])->name('admin.clientes.vetar');
});

require __DIR__.'/auth.php';

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('linea_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->foreignId('tarifa_id')->constrained('tarifas');
            $table->integer('cantidad');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('linea_servicios');
    }
};

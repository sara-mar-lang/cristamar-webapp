<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo_evento');
            $table->integer('invitados');
            $table->enum('zona', ['Bogota', 'Sabana']);
            $table->foreignId('locacion_id')->nullable()->constrained('locaciones')->nullOnDelete();
            $table->date('fecha_evento');
            $table->string('nombre', 60);
            $table->string('celular', 10);
            $table->string('correo', 100);
            $table->string('comentarios', 300)->nullable();
            $table->bigInteger('total_estimado');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'cancelada'])->default('pendiente');
            $table->text('motivo_estado')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('solicitudes');
    }
};

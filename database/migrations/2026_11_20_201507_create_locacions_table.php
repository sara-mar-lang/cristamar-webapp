<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('locaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->string('imagen')->nullable();
            $table->integer('capacidad_max');
            $table->enum('zona', ['Bogota', 'Sabana']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('locaciones');
    }
};

<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Tarifa;
use App\Models\Locacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Administrador Cristamar',
            'email' => 'admin@cristamar.com',
            'celular' => '3101234567',
            'rol' => 'administrador',
            'email_verified_at' => now(),
            'password' => Hash::make('AdminCristamar2026*'),
        ]);

        User::create([
            'name' => 'Camila Mendoza',
            'email' => 'camila@empresa.com',
            'celular' => '3152292638',
            'rol' => 'cliente',
            'email_verified_at' => now(),
            'password' => Hash::make('Cliente2026*'),
        ]);

        $tarifas = [
            ['nombre' => 'Corporativo / BTL', 'tipo_cobro' => 'por_persona', 'precio' => 75000],
            ['nombre' => 'Boda de autor', 'tipo_cobro' => 'por_persona', 'precio' => 120000],
            ['nombre' => 'Fiesta social o 15 años', 'tipo_cobro' => 'por_persona', 'precio' => 90000],
            ['nombre' => 'Recargo Sabana', 'tipo_cobro' => 'por_persona', 'precio' => 12000],
            ['nombre' => 'Catering', 'tipo_cobro' => 'por_persona', 'precio' => 28000],
            ['nombre' => 'Sonido e iluminación', 'tipo_cobro' => 'fijo', 'precio' => 1200000],
            ['nombre' => 'Decoración', 'tipo_cobro' => 'por_persona', 'precio' => 20000],
        ];
        foreach ($tarifas as $t) { Tarifa::create($t); }

        Locacion::create(['nombre' => 'Hacienda San José (Chía - Sabana)', 'capacidad_max' => 450, 'zona' => 'Sabana']);
        Locacion::create(['nombre' => 'Castillo Marroquín (Chía - Sabana)', 'capacidad_max' => 800, 'zona' => 'Sabana']);
        Locacion::create(['nombre' => 'Salón Gran Reserva (Chapinero - Bogotá)', 'capacidad_max' => 250, 'zona' => 'Bogota']);
        Locacion::create(['nombre' => 'Club El Nogal (Usaquén - Bogotá)', 'capacidad_max' => 350, 'zona' => 'Bogota']);
    }
}

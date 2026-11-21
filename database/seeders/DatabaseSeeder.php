<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Tarifa;
use App\Models\Locacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Perfil 1: Administrador (Marlon Romero / Sara Vargas)
        User::create([
            'name' => 'Marlon Romero (Admin)',
            'email' => 'admin@cristamar.com',
            'celular' => '3152292638',
            'rol' => 'administrador',
            'email_verified_at' => now(),
            'password' => Hash::make('AdminCristamar2026*'),
        ]);

        // Perfil 2: Vendedor / Asesor VIP
        User::create([
            'name' => 'Sara Vargas (Asesora VIP)',
            'email' => 'vendedor@cristamar.com',
            'celular' => '3109876543',
            'rol' => 'vendedor',
            'email_verified_at' => now(),
            'password' => Hash::make('Vendedor2026*'),
        ]);

        // Perfil 3: Cliente Corporativo / Social
        User::create([
            'name' => 'Camila Mendoza',
            'email' => 'camila@empresa.com',
            'celular' => '3152292638',
            'rol' => 'cliente',
            'email_verified_at' => now(),
            'password' => Hash::make('Cliente2026*'),
        ]);

        // Tarifas oficiales mostradas en el video
        $tarifas = [
            ['nombre' => 'Corporativo / BTL / Congreso', 'tipo_cobro' => 'por_persona', 'precio' => 75000],
            ['nombre' => 'Boda de Autor Campestre', 'tipo_cobro' => 'por_persona', 'precio' => 120000],
            ['nombre' => 'Fiesta Social VIP / 15 Años', 'tipo_cobro' => 'por_persona', 'precio' => 90000],
            ['nombre' => 'Recargo Sabana', 'tipo_cobro' => 'por_persona', 'precio' => 12000],
            ['nombre' => 'Banquetes & Catering', 'tipo_cobro' => 'por_persona', 'precio' => 28000],
            ['nombre' => 'Iluminación & Sonido Profesional', 'tipo_cobro' => 'fijo', 'precio' => 1200000],
            ['nombre' => 'Decoración & Escenografía Temática', 'tipo_cobro' => 'por_persona', 'precio' => 28000],
        ];
        foreach ($tarifas as $t) { Tarifa::create($t); }

        // Las 6 locaciones exactas del video
        Locacion::create(['nombre' => 'Hacienda Colonial San Agustín (Sabana)', 'capacidad_max' => 450, 'zona' => 'Sabana']);
        Locacion::create(['nombre' => 'Palacio de Cristal Los Cerros (Sabana)', 'capacidad_max' => 600, 'zona' => 'Sabana']);
        Locacion::create(['nombre' => 'Reserva Campestre El Molino (Sabana)', 'capacidad_max' => 800, 'zona' => 'Sabana']);
        Locacion::create(['nombre' => 'Hacienda Rincón Campestre Cajicá (Sabana)', 'capacidad_max' => 350, 'zona' => 'Sabana']);
        Locacion::create(['nombre' => 'Gran Salón Real Chicó (Bogotá VIP)', 'capacidad_max' => 300, 'zona' => 'Bogota']);
        Locacion::create(['nombre' => 'Rooftop Glasshouse Usaquén (Bogotá VIP)', 'capacidad_max' => 180, 'zona' => 'Bogota']);
    }
}

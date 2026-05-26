<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Barbershop;

class IsolateSuperadminSeeder extends Seeder
{
    public function run()
    {
        // 1. Quitar los poderes de local al Súper Administrador
        $superadmin = User::where('email', 'admin@rdbarberia.com')->first();
        if ($superadmin) {
            $superadmin->update([
                'is_barber' => false,
                'barbershop_id' => null, // Completamente aislado
                'name' => 'Codex Admin'
            ]);
        }

        // 2. Crear un nuevo dueño REAL para RD Barbería
        $shop = Barbershop::where('slug', 'rdbarber')->first();
        if ($shop) {
            User::updateOrCreate(
                ['email' => 'luis@rdbarberia.com'],
                [
                    'name' => 'Luis (Dueño RD Barber)',
                    'password' => Hash::make('password'),
                    'phone' => '12345678',
                    'is_barber' => true,
                    'is_superadmin' => false,
                    'barbershop_id' => $shop->id
                ]
            );
        }
    }
}

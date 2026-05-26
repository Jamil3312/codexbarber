<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barbershop;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Setting;

class SaaSSeeder extends Seeder
{
    public function run()
    {
        $shop = Barbershop::create([
            'name' => 'RD Barbería',
            'slug' => 'rdbarber',
            'primary_color' => 'yellow-500'
        ]);

        $admin = User::where('email', 'admin@rdbarberia.com')->first();
        if ($admin) {
            $admin->update(['is_superadmin' => true, 'barbershop_id' => $shop->id]);
        }

        Service::query()->update(['barbershop_id' => $shop->id]);
        Appointment::query()->update(['barbershop_id' => $shop->id]);
        Setting::query()->update(['barbershop_id' => $shop->id]);
    }
}

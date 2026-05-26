<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Barbero RD',
            'email' => 'admin@rdbarberia.com',
            'password' => Hash::make('password'),
            'is_barber' => true,
        ]);

        Setting::create([
            'slot_duration' => 45,
            'start_time_1' => '09:00:00',
            'end_time_1' => '13:00:00',
            'start_time_2' => '15:00:00',
            'end_time_2' => '19:00:00',
            'cancellation_notice' => 2,
        ]);
    }
}

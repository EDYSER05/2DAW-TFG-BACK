<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            ['name' => 'Turno Mañana',    'start_time' => '07:00:00', 'end_time' => '15:00:00'],
            ['name' => 'Turno Tarde',     'start_time' => '15:00:00', 'end_time' => '23:00:00'],
            ['name' => 'Turno Noche',     'start_time' => '23:00:00', 'end_time' => '07:00:00'],
            ['name' => 'Jornada Partida', 'start_time' => '09:00:00', 'end_time' => '18:00:00'],
            ['name' => 'Media Jornada',   'start_time' => '09:00:00', 'end_time' => '13:00:00'],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserShiftSeeder extends Seeder
{
    public function run(): void
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $users = User::where('role_id', '!=', $adminRoleId)->get();
        $shifts = Shift::all();
        $workDays = Day::whereIn('name', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'])->get();

        $rows = [];
        $now = now();

        foreach ($users as $user) {
            $shift = $shifts->random();

            foreach ($workDays as $day) {
                $rows[] = [
                    'user_id' => $user->id,
                    'shift_id' => $shift->id,
                    'day_id' => $day->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('user_shifts')->insert($chunk);
        }
    }
}

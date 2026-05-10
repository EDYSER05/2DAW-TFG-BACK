<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Seeders de base
            RoleSeeder::class,
            AbsenceTypeSeeder::class,
            IssueTypeSeeder::class,
            DaySeeder::class,
            ShiftSeeder::class,

            // 2. Festivos nacionales
            HolidaySeeder::class,

            // 3. Creación admin del sistema
            AdminSeeder::class,

            // 4. Empresas con sus owners -> departamentos -> managers/hr -> empleados
            CompanySeeder::class,

            // 5. Turnos semanales para todos los usuarios
            UserShiftSeeder::class,
        ]);
    }
}

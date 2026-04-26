<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Catálogos sin dependencias
            PermissionRoleSeeder::class,
            AbsenceTypeSeeder::class,
            IssueTypeSeeder::class,
            DaySeeder::class,
            ShiftSeeder::class,

            // Festivos nacionales — debe ejecutarse ANTES de CompanySeeder
            // para que el CompanyObserver pueda enlazarlos al crear cada empresa
            HolidaySeeder::class,

            // Usuario administrador del sistema (sin departamento)
            AdminSeeder::class,

            // Empresas → departamentos → managers → empleados
            // El CompanyObserver inserta automáticamente los festivos nacionales
            // en company_holiday al crear cada empresa
            CompanySeeder::class,

            // Asignación de turnos a todos los usuarios
            UserShiftSeeder::class,
        ]);
    }
}

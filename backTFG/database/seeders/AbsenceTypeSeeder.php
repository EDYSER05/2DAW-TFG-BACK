<?php

namespace Database\Seeders;

use App\Models\AbsenceType;
use Illuminate\Database\Seeder;

class AbsenceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Vacaciones',
            'Baja por enfermedad',
            'Permiso de maternidad/paternidad',
            'Permiso por fallecimiento familiar',
            'Asuntos propios',
            'Permiso médico',
            'Formación',
        ];

        foreach ($types as $type) {
            AbsenceType::create(['name' => $type]);
        }
    }
}

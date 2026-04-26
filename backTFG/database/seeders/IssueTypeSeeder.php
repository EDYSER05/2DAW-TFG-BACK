<?php

namespace Database\Seeders;

use App\Models\IssueType;
use Illuminate\Database\Seeder;

class IssueTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Olvido de fichaje de entrada',
            'Olvido de fichaje de salida',
            'Fichaje duplicado',
            'Fichaje fuera de horario',
            'Discrepancia horaria',
            'Error de sistema',
            'Retraso en la entrada',
            'Salida anticipada no autorizada',
            'Horas extra no registradas',
            'Corrección administrativa',
        ];

        foreach ($types as $type) {
            IssueType::create(['name' => $type]);
        }
    }
}

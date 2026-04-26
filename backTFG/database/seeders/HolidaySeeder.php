<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = json_decode(
            file_get_contents(database_path('seeders/data/holidays.json')),
            true
        );

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}

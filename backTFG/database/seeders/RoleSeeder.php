<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'owner', 'manager', 'hr', 'employee'] as $name) {
            Role::create(['name' => $name]);
        }
    }
}

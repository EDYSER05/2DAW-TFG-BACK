<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'department_id'     => null,
            'role_id'           => Role::where('name', 'admin')->value('id'),
            'name'              => 'Admin',
            'last_name'         => 'Sistema',
            'email'             => 'admin@sistema.com',
            'password'          => Hash::make('password'),
            'hire_date'         => now(),
            'active'            => true,
            'email_verified_at' => now(),
            'last_login_at'     => now(),
        ]);
    }
}

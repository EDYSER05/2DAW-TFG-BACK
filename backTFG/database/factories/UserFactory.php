<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'department_id' => null,
            'role_id' => Role::where('name', 'employee')->value('id'),
            'name' => fake('es_ES')->firstName(),
            'last_name' => fake('es_ES')->lastName() . ' ' . fake('es_ES')->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'hire_date' => now(),
            'active' => true,
            'must_change_password' => false,
            'email_verified_at' => now(),
            'last_login_at' => now(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn() => ['role_id' => fn() => Role::where('name', 'admin')->value('id')]);
    }

    public function owner(): static
    {
        return $this->state(fn() => ['role_id' => fn() => Role::where('name', 'owner')->value('id')]);
    }

    public function manager(): static
    {
        return $this->state(fn() => ['role_id' => fn() => Role::where('name', 'manager')->value('id')]);
    }

    public function hr(): static
    {
        return $this->state(fn() => ['role_id' => fn() => Role::where('name', 'hr')->value('id')]);
    }

    public function inactive(): static
    {
        return $this->state(fn() => ['active' => false]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            // department_id y role_id los sobreescribe UserSeeder
            'department_id'       => 1,
            'role_id'             => 3,
            'name'                => fake('es_ES')->firstName(),
            'last_name'           => fake('es_ES')->lastName() . ' ' . fake('es_ES')->lastName(),
            'email'               => fake()->unique()->safeEmail(),
            'password'            => Hash::make('password'),
            'hire_date'           => fake()->dateTimeBetween('-8 years', '-6 months')->format('Y-m-d'),
            'active'              => fake()->boolean(90), // 90 % activos
            'email_verified_at'   => now(),
            'last_login_at'       => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /** Estado para usuarios inactivos */
    public function inactive(): static
    {
        return $this->state(fn () => ['active' => false]);
    }
}

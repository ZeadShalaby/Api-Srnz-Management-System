<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password', // password
            'role' => Role::CUSTOMER,
            'gmail'=>fake()->unique()->safeEmail(),
            'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
            'profile_photo'=>fake()->imageUrl($width=400, $height=400),

        ];
    }

    public function admin()
        {
        return $this->state(function (array $attributes) {
            return [
                
                'role' => Role::ADMIN,
            ];
        });
        }

    public function CUSTOMER()
        {
        return $this->state(function (array $attributes) {
            return [
                'role' => Role::CUSTOMER,
            ];
        });
        }

    /**
     * Indicate that the model's email address should be unverified.
     */
   
}


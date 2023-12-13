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
        $img = array("dep.jpg","ord.jpg","user.jpg") ;
        $increment = random_int(0,2);
        $destination_path = '/api/v1/images/imageusers/';
        $http_address = env('APP_URL');
        $path = $http_address.$destination_path.$img[$increment];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password', // password
            'role' => Role::CUSTOMER,
            'gmail'=>fake()->unique()->safeEmail(),
            'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
            'profile_photo'=>$path,

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


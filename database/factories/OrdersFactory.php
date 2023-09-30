<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Departments;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orders>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departmentIds = Departments::pluck('id')->toArray();
        $userids = User::where('role', '2')->pluck('id')->toArray();
 
        return [
            'name_ar' => 'عربي',
            'name_en' => $this->faker->unique()->regexify('[A-Z]{10}'),
            'user_id' => $this->faker->randomElement($userids),
            'department_id' => $this->faker->randomElement($departmentIds),          
            'gmail'=>fake()->unique()->safeEmail(),
            'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
            'description'=>fake()->text(),
            'price'=>fake()->numberBetween($min = 1000, $max = 100000),
            'path'=>fake()->imageUrl($width=400, $height=400),
            'view'=>null,

        ];
    }
}

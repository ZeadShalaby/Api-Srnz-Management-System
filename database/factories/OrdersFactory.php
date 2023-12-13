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
        $img = array("dep.jpg","ord.jpg","user.jpg") ;
        $increment = random_int(0,2);
        $destination_path = '/api/v1/images/imageord/';
        $http_address = env('APP_URL');
        $path = $http_address.$destination_path.$img[$increment];

        return [
            'name_ar' => 'عربي',
            'name_en' => $this->faker->unique()->regexify('[A-Z]{10}'),
            'user_id' => $this->faker->randomElement($userids),
            'department_id' => $this->faker->randomElement($departmentIds),          
            'gmail'=>fake()->unique()->safeEmail(),
            'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
            'description'=>fake()->text(),
            'price'=>fake()->numberBetween($min = 1000, $max = 100000),
            'path'=>$path,
            'view'=>null,

        ];
    }
}

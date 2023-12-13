<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Departments>
 */
class DepartmentsFactory extends Factory
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
        $destination_path = '/api/v1/images/imagedep/';
        $http_address = env('APP_URL');
        $path = $http_address.$destination_path.$img[$increment];
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->unique()->regexify('[A-Z]{3}'),
            'img' => $path /*fake()->imageUrl($width=400, $height=400)*/,
        ];
    }
}

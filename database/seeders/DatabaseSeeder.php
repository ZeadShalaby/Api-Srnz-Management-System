<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Cards;
use App\Models\Orders;
use App\Models\Favourite;
use App\Models\Departments;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

       //// todo add departments ////
       $departments = Departments::factory()->count(5)->create();

       //// todo add users admin ////
       User::create([
        'name' => 'Admin',
        'email' => 'admin@admin.srnz',
        'password' => Hash::make('admin'), 
        'gmail'=>fake()->unique()->safeEmail(),
        'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
        'role' =>'1',
        'profile_photo'=>fake()->imageUrl($width=400, $height=400),
        'remember_token' => Str::random(10),
      
    ]); 

    //// todo add users customer ////
    User::create([
        'name' => 'Customer',
        'email' => 'customer@customer.srnz',
        'password' => Hash::make('customer'), 
        'gmail'=>fake()->unique()->safeEmail(),
        'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
        'role' =>'2',
        'profile_photo'=>fake()->imageUrl($width=400, $height=400),      
    ]);

        //// todo add one admin ////
        $defAdmin = User::factory()->create([
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'gmail'=>fake()->unique()->safeEmail(),
        'password' => Hash::make('admin'),
        'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
        'role' =>'1',
        ]);
    
        //// todo add one customer ////
        $defCustomer = User::factory()->create([  
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'gmail'=>fake()->unique()->safeEmail(),
            'password' => Hash::make('admin'),
            'phone'=>fake()->numberBetween($min = 123456789, $max = 98561237894),
            'role' =>'2',
      
        ]);
    
        //// todo add user admin ////
        $admins = User::factory()
        ->admin()
        ->count(4)
        ->create();
        $admins->push($defAdmin);

        //// todo add user customer ////
        $customer = User::factory()
        ->customer()
        ->count(4)
        ->create();
        $customer->push($defCustomer);
        
        //// todo add orders ////
        $order = Orders::factory()
        ->count(30)
        ->state(function (array $attributes) use ($departments, $customer) {
            return [
                'department_id' => $departments->random()->id,
                'user_id' => $customer->random()->id,
            ];
        })->create();

        //// todo add favpurite ////
        $favourite = Favourite::factory()
        ->count(10)
        ->state(function (array $attributes) use ($order, $customer) {
            return [
                'orders_id' => $order->random()->id,
                'user_id' => $customer->random()->id,
            ];
        })->create();

        //// todo add cards ////
        $card = Cards::factory()
        ->count(20)
        ->state(function (array $attributes) use ($order, $customer) {
            return [
                'orders_id' => $order->random()->id,
                'department_id' => $order->random()->department_id,
                'price' => $order->random()->price,
                'user_id' => $customer->random()->id,
            ];
        })->create();
        
    }
}

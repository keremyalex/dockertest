<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $faker = Faker::create();

        User::factory()->create([
             'name' => 'Alex Paco Crispin',
             'email' => 'admin@test.com',
             'password' => Hash::make('password'),
             'user_type' => 'admin',
             'foto1_url' => 'https://i.imgur.com/1Z1Z1Z1.jpg',
             'foto2_url' => 'https://i.imgur.com/2Z2Z2Z2.jpg',
        ]);

        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test1@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'organizador',
            'foto1_url' => 'https://i.imgur.com/1Z1Z1Z1.jpg',
            'foto2_url' => 'https://i.imgur.com/2Z2Z2Z2.jpg',
        ]);

        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test2@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'estudio',
            'foto1_url' => 'https://i.imgur.com/1Z1Z1Z1.jpg',
            'foto2_url' => 'https://i.imgur.com/2Z2Z2Z2.jpg',
        ]);


        $this->call(RoleSeeder::class);
    }
}

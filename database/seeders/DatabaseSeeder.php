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
             'foto1_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
             'foto2_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
             'face_id' => '123456789',
        ]);

        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test1@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'organizador',
            'foto1_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'foto2_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'face_id' => '123456789',
        ]);

        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test2@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'estudio',
            'foto1_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'foto2_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'face_id' => '123456789',
        ]);

        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test3@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'estudio',
            'foto1_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'foto2_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'face_id' => '123456789',
        ]);
        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test4@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'estudio',
            'foto1_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'foto2_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'face_id' => '123456789',
        ]);
        User::factory()->create([
            'name' => $faker->name,
            'email' => 'test5@test.com',
            'password' => Hash::make('password'),
            'user_type' => 'estudio',
            'foto1_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'foto2_url' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
            'face_id' => '123456789',
        ]);


        $this->call(RoleSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Hash;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'gender' => $faker->randomElement(['male', 'female']),
                'dob' => $faker->date(),
                'email_verified_at' => now(),
                'password' => Hash::make('@Demo123'),
            ]);

        }

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Using Faker to generate dummy data
        $faker = Faker::create();

        // Get user IDs from the 'users' table
        

        // Insert 100 dummy events for each user
        $userId = 1
            for ($i = 0; $i < 100; $i++) {
                DB::table('events')->insert([
                    'user_id' => $userId,
                    'title' => $faker->company,
                    'entry_price' => $faker->randomFloat(2, 0, 100),
                    'exit_price' => $faker->randomFloat(2, 0, 100),
                    'start_date' => $faker->dateTimeBetween('-2 month', '+2 month'),
                    'end_date' => $faker->dateTimeBetween('-2 month', '+2 months'),
                    'comment' => $faker->paragraph,
                ]);
            }
    }
}


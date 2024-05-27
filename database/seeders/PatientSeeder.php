<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $uniqueDates = [];

        for ($i = 0; $i < 100; $i++) {
            // Generate unique date of birth
            do {
                $dob = $faker->date();
            } while (in_array($dob, $uniqueDates));

            $uniqueDates[] = $dob;

            DB::table('patients')->insert([
                'nik' => $faker->unique()->numerify('##########'),
                'name' => $faker->unique()->name,
                'gender' => $faker->randomElement(['male', 'female']),
                'dob' => $dob,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

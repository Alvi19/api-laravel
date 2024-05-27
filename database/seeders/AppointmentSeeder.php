<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $patients = \App\Models\Patient::pluck('id')->toArray();
        $doctors = \App\Models\Doctor::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            $patientId = $faker->randomElement($patients);
            $doctorId = $faker->randomElement($doctors);

            $date = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d');
            $time = $faker->time('H:i:s');

            DB::table('appointments')->insert([
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'date' => $date,
                'time' => $time,
                'note' => $faker->text,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $patients = array_diff($patients, [$patientId]);
            $doctors = array_diff($doctors, [$doctorId]);
        }
    }
}

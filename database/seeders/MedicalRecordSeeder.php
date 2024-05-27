<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $patients = \App\Models\Patient::pluck('id')->toArray();
        $doctors = \App\Models\Doctor::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            $patientId = $faker->randomElement($patients);
            $doctorId = $faker->randomElement($doctors);

            DB::table('medical_records')->insert([
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'diagnosis' => $faker->text,
                'prescription' => $faker->text,
                'notes' => $faker->text,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Remove the used patient and doctor ID from the arrays to prevent duplicate records
            $patients = array_diff($patients, [$patientId]);
            $doctors = array_diff($doctors, [$doctorId]);
        }
    }
}

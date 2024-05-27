<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            DB::table('products')->insert([
                'name' => 'Product ' . $i,
                'description' => 'Description for Product ' . $i,
                'price' => rand(100, 10000) / 100, // Random price between 1 and 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

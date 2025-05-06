<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First get company IDs
        $companies = DB::table('companies')->pluck('id')->toArray();
        
        if (empty($companies)) {
            throw new \Exception('Please run CompanySeeder first!');
        }

        DB::table('items')->insert([
            [
                'company_id' => $companies[0],
                'name' => 'Basic Car Service',
                'icon' => 'wrench',
                'description' => 'Regular maintenance service including oil change, filter replacement, and basic inspection',
                'type' => 'service',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companies[0],
                'name' => 'Premium Car Wash',
                'icon' => 'car-wash',
                'description' => 'Complete exterior and interior cleaning with premium products and waxing',
                'type' => 'service',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

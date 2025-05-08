<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bookings')->insert([
            [
                'user_id' => 1,
                'company_id' => 1,
                'resource_id' => 1,
                'start_time' => now()->addDay(),
                'end_time' => now()->addDays(2),
                'status' => 'pending',
                'description' => 'Booking for projector',
                'created_at' => now(),
                'updated_at' => now(),
                'approved_by' => null,
                'approved_at' => null,
            ],
            [
                'user_id' => 2,
                'company_id' => 1,
                'resource_id' => 1,
                'start_time' => now()->addDays(3),
                'end_time' => now()->addDays(4),
                'status' => 'approved',
                'description' => 'Admin booking',
                'created_at' => now(),
                'updated_at' => now(),
                'approved_by' => 3,
                'approved_at' => now(),
            ],
        ]);
    }
}

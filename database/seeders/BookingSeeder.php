<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \DB::table('users')->first();
        $item = \DB::table('items')->first();

        if (!$user || !$item) {
            throw new \Exception('Users and Items must be seeded before Bookings!');
        }

        DB::table('bookings')->insert([
            [
                'user_id' => $user->id,
                'start_time' => '2025-01-01 00:00:00',
                'end_time' => '2025-01-02 22:00:00',
                'status' => 'approved',
                'created_at' => '2024-12-31 20:06:09',
                'updated_at' => '2024-12-31 20:06:18',
                'item_id' => $item->id,
                'description' => null,
                'approved_by' => null,
                'approved_at' => null,
            ],
        ]);
    }
}

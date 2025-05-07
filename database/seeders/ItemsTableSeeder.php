<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'company_id' => 1,
                'name' => 'Projector',
                'icon' => null,
                'description' => 'HD projector',
                'type' => 'equipment',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'Conference Room',
                'icon' => null,
                'description' => 'Main conference room',
                'type' => 'room',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

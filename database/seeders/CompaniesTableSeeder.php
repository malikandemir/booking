<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->insert([
            ['name' => 'Acme Corp', 'description' => 'A sample company', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beta LLC', 'description' => 'Another company', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

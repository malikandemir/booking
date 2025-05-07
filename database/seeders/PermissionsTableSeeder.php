<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                'name' => 'Manage Companies',
                'slug' => 'manage_companies',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manage All Users',
                'slug' => 'manage_all_users',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manage Company Users',
                'slug' => 'manage_company_users',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manage Items',
                'slug' => 'manage_items',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Approve',
                'slug' => 'approve',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Book',
                'slug' => 'book',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}

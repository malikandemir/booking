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
                'name' => 'Manage Resources',
                'slug' => 'manage_resources',
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
            [
                'name' => 'Manage Roles',
                'slug' => 'manage_roles',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manage Bookings',
                'slug' => 'manage_bookings',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}

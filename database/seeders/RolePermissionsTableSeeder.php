<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_permissions')->insert([
            // Super Admin (role_id: 1) - super admin permissions
            ['role_id' => 1, 'permission_id' => 1, 'created_at' => now(), 'updated_at' => now()], // Manage Companies
            ['role_id' => 1, 'permission_id' => 2, 'created_at' => now(), 'updated_at' => now()], // Manage All Users
            ['role_id' => 1, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()], // Manage Company Users
            ['role_id' => 1, 'permission_id' => 7, 'created_at' => now(), 'updated_at' => now()], // Manage Roles

            // Admin (role_id: 2) - company/user/resource/book/approve
            ['role_id' => 2, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()], // Manage Company Users
            ['role_id' => 2, 'permission_id' => 4, 'created_at' => now(), 'updated_at' => now()], // Manage Resources
            ['role_id' => 2, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()], // Approve
            ['role_id' => 2, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()], // Book
            ['role_id' => 2, 'permission_id' => 8, 'created_at' => now(), 'updated_at' => now()], // Manage Bookings

            // User (role_id: 3) - only approve/book
            ['role_id' => 3, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()], // Approve
            ['role_id' => 3, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()], // Book
            ['role_id' => 3, 'permission_id' => 8, 'created_at' => now(), 'updated_at' => now()], // Manage Bookings

        ]);
    }
}

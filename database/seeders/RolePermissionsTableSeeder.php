<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_permissions')->insert([
            // Super Admin (role_id: 1) - all permissions
            ['role_id' => 1, 'permission_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 1, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()],

            // Admin (role_id: 2) - company/user/item/book/approve
            ['role_id' => 2, 'permission_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()],

            // User (role_id: 3) - only approve/book
            ['role_id' => 3, 'permission_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'permission_id' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

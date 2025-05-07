<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersRolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users_roles')->insert([
            // Assign User role to user@booking.com (user_id: 1)
            ['user_id' => 1, 'role_id' => 3, 'created_at' => now(), 'updated_at' => now()], // User

            // Assign Admin role to admin@booking.com (user_id: 2)
            ['user_id' => 2, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()], // Admin

            // Assign Super Admin role to sadmin@booking.com (user_id: 3)
            ['user_id' => 3, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // Super Admin
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserItemRolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_item_roles')->insert([
            [
                'user_id' => 1,
                'item_id' => 1,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_id' => 2,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'item_id' => 2,
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

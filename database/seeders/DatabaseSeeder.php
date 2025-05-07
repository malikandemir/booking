<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompaniesTableSeeder::class,
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            UsersTableSeeder::class,
            ItemsTableSeeder::class,
            UserItemRolesTableSeeder::class,
            BookingsTableSeeder::class,
            UsersRolesTableSeeder::class,
            RolePermissionsTableSeeder::class,
        ]);
    }
}

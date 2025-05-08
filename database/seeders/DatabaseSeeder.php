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
            ResourceTableSeeder::class,
            UserResourceRolesTableSeeder::class,
            BookingsTableSeeder::class,
            UsersRolesTableSeeder::class,
            RolePermissionsTableSeeder::class,
        ]);
    }
}

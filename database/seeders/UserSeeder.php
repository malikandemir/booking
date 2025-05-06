<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get companies and their user types
        $companies = DB::table('companies')->get();
        
        foreach ($companies as $company) {
            // Get user types for this company
            $superAdminType = DB::table('user_types')
                ->where('company_id', $company->id)
                ->where('name', 'Super Admin')
                ->first();

            $adminType = DB::table('user_types')
                ->where('company_id', $company->id)
                ->where('name', 'Admin')
                ->first();

            $userType = DB::table('user_types')
                ->where('company_id', $company->id)
                ->where('name', 'Regular User')
                ->first();

            // Create Super Admin
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'superadmin' . $company->id . '@' . strtolower(str_replace(' ', '', $company->name)) . '.com',
                'password' => Hash::make('password123'),
                'company_id' => $company->id,
                'user_type_id' => $superAdminType->id,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Admin
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin' . $company->id . '@' . strtolower(str_replace(' ', '', $company->name)) . '.com',
                'password' => Hash::make('password123'),
                'company_id' => $company->id,
                'user_type_id' => $adminType->id,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Regular User
            DB::table('users')->insert([
                'name' => 'Regular User',
                'email' => 'user' . $company->id . '@' . strtolower(str_replace(' ', '', $company->name)) . '.com',
                'password' => Hash::make('password123'),
                'company_id' => $company->id,
                'user_type_id' => $userType->id,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

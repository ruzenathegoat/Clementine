<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super_admin' => 'superadmin@clementine.test',
            'inventory_manager' => 'inventory@clementine.test',
            'ops_staff' => 'ops@clementine.test',
            'customer_success' => 'cs@clementine.test',
            'finance_manager' => 'finance@clementine.test',
        ];

        foreach ($roles as $role => $email) {
            \App\Models\User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => ucwords(str_replace('_', ' ', $role)),
                    'password' => bcrypt('password123'),
                    'role' => $role,
                    'is_vip' => false,
                ]
            );
        }
    }
}

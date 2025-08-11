<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create administrator user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'permissions' => [], // Admins don't need explicit permissions
            'email_verified_at' => now(),
        ]);

        // Create sales manager with comprehensive sales and storage unit permissions
        $salesManager = User::create([
            'name' => 'Sales Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager123'),
            'is_admin' => false,
            'permissions' => [
                'create_sales',
                'edit_sales',
                'delete_sales',
                'create_storage_units',
                'edit_storage_units',
                'create_categories',
                'edit_categories',
                'create_platforms',
                'edit_platforms',
            ],
            'email_verified_at' => now(),
        ]);

        // Create data entry clerk with limited permissions
        $clerk = User::create([
            'name' => 'Data Entry Clerk',
            'email' => 'clerk@example.com',
            'password' => Hash::make('clerk123'),
            'is_admin' => false,
            'permissions' => [
                'create_sales',
                'edit_sales', // Can edit their own sales
            ],
            'email_verified_at' => now(),
        ]);

        // Create category manager with category and platform permissions
        $categoryManager = User::create([
            'name' => 'Category Manager',
            'email' => 'categories@example.com',
            'password' => Hash::make('category123'),
            'is_admin' => false,
            'permissions' => [
                'create_categories',
                'edit_categories',
                'delete_categories',
                'create_platforms',
                'edit_platforms',
                'delete_platforms',
            ],
            'email_verified_at' => now(),
        ]);

        // Create viewer with no permissions (view only)
        $viewer = User::create([
            'name' => 'Read Only User',
            'email' => 'viewer@example.com',
            'password' => Hash::make('viewer123'),
            'is_admin' => false,
            'permissions' => [], // No permissions - read only
            'email_verified_at' => now(),
        ]);

        $this->command->info('Created test users with varying permission levels:');
        $this->command->info('- Administrator (admin@example.com / admin123) - All permissions');
        $this->command->info('- Sales Manager (manager@example.com / manager123) - Sales & storage permissions');
        $this->command->info('- Data Entry Clerk (clerk@example.com / clerk123) - Limited sales permissions');
        $this->command->info('- Category Manager (categories@example.com / category123) - Categories & platforms');
        $this->command->info('- Read Only User (viewer@example.com / viewer123) - No permissions');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_user')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('role_user')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $rolesStructure = [
            'orionadmin' => [
                'users' => ['create', 'read', 'update', 'delete'],
                'payments' => ['create', 'read', 'update', 'delete'],
                'profile' => ['read', 'update'],
            ],
            'manager' => [
                'users' => ['create', 'read', 'update', 'delete'],
                'profile' => ['read', 'update'],
            ],
            'employee' => [
                'profile' => ['read', 'update'],
            ],
            'dc' => [
                'data' => ['create', 'read', 'update', 'delete'],
            ],
        ];

        foreach ($rolesStructure as $roleName => $modules) {
            // Create role
            $role = Role::create([
                'name' => $roleName,
                'display_name' => ucwords(str_replace('_', ' ', $roleName)),
                'description' => ucwords(str_replace('_', ' ', $roleName)),
            ]);

            $this->command->info('Creating Role ' . strtoupper($roleName));

            // Create permissions for each module
            foreach ($modules as $module => $actions) {
                foreach ($actions as $action) {
                    $permission = Permission::firstOrCreate([
                        'name' => $module . '-' . $action,
                        'display_name' => ucfirst($action) . ' ' . ucfirst($module),
                        'description' => ucfirst($action) . ' ' . ucfirst($module),
                    ]);

                    $role->givePermissionTo($permission);
                    $this->command->info("Creating Permission to {$action} for {$module}");
                }
            }

            // Create user for role
            $user = User::create([
                'name' => ucwords(str_replace('_', ' ', $roleName)),
                'email' => $roleName . '@orionlogistics.com',
                'password' => bcrypt('Orion@2025'),
            ]);

            $this->command->info("Creating '{$roleName}' user");

            // Add role to user
            $user->addRole($role);
            $this->command->info("Role {$role->name} assigned to user {$user->name}");
        }
    }
}

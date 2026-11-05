<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $role = Role::updateOrCreate(
            [
                'slug' => 'super-admin',
            ],
            [
                'name' => 'Super Admin',
                'description' => 'Memiliki akses penuh ke seluruh sistem LMS.',
                'guard_name' => 'web',
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Assign All Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = Permission::where('guard_name', 'web')->get();

        $role->syncPermissions($permissions);

        /*
        |--------------------------------------------------------------------------
        | Assign Role to User
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', 'user@gmail.com')->firstOrFail();

        $user->syncRoles([$role]);
    }
}

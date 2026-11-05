<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;


class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Training
            |--------------------------------------------------------------------------
            */
            'training' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            /*
            |--------------------------------------------------------------------------
            | Training Type
            |--------------------------------------------------------------------------
            */
            'training-type' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            /*
            |--------------------------------------------------------------------------
            | Training Category
            |--------------------------------------------------------------------------
            */
            'training-category' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            /*
            |--------------------------------------------------------------------------
            | Future Modules
            |--------------------------------------------------------------------------
            |
            | Tambahkan module baru di sini ketika module LMS dikembangkan.
            |
            | Contoh:
            |
            | 'certificate' => [
            |     'view',
            |     'create',
            |     'update',
            |     'delete',
            | ],
            |
            */
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}

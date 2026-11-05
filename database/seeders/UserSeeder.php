<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::where('auth_id', 100)->firstOrFail();

        User::updateOrCreate(
            [
                'email' => 'user@gmail.com',
            ],
            [
                'employee_id' => $employee->id,
                'name' => $employee->name,
                'password' => Hash::make('password'),
            ]
        );
    }
}

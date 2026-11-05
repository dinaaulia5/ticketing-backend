<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::updateOrCreate(
            [
                'auth_id' => 100,
            ],
            [
                'name' => 'Dina Nur Auliana',
                'nup' => 51017,
                'division' => 'Human Capital dan Teknologi Informasi',
                'position' => 'Staf Sistem Sentralisasi Keuangan',
                'departement_code' => 'HCTI',
                'employee_status' => 'active',
            ]
        );
    }
}

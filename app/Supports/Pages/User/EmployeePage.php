<?php

namespace App\Supports\Pages\User;

class EmployeePage
{
    public static function index(): array
    {
        return [
            'title' => 'Data Karyawan',
            'subtitle' => 'Kelola daftar karyawan dalam sistem.',
            'banner' => [
                'title' => 'Master Karyawan',
                'subtitle' => 'Data karyawan digunakan untuk mengelola informasi karyawan dalam sistem.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Karyawan',
            'subtitle' => 'Tambahkan data karyawan baru ke dalam sistem.',
            'method' => 'POST',
            'action' => route('employees.store'),
        ];
    }

    public static function edit($employee): array
    {
        return [
            'title' => 'Perbarui Karyawan',
            'subtitle' => 'Perbarui informasi karyawan yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('employees.update', $employee),
        ];
    }

    public static function breadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Karyawan',
            ],
        ];
    }

    public static function createBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Karyawan',
                'href' => route('employees.index'),
            ],
            [
                'label' => 'Tambah Karyawan',
            ],
        ];
    }

    public static function editBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Karyawan',
                'href' => route('employees.index'),
            ],
            [
                'label' => 'Perbarui Karyawan',
            ],
        ];
    }
}

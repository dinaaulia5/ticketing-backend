<?php

namespace App\Supports\Pages\User;

class RolePage
{
    public static function index(): array
    {
        return [
            'title' => 'Data Peran',
            'subtitle' => 'Kelola daftar peran dalam sistem.',
            'banner' => [
                'title' => 'Master Peran',
                'subtitle' => 'Data peran digunakan untuk mengatur hak akses pengguna dalam sistem.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Peran',
            'subtitle' => 'Tambahkan peran baru ke dalam sistem.',
            'method' => 'POST',
            'action' => route('roles.store'),
        ];
    }

    public static function edit($role): array
    {
        return [
            'title' => 'Perbarui Peran',
            'subtitle' => 'Perbarui informasi peran yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('roles.update', $role),
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
                'label' => 'Peran',
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
                'label' => 'Peran',
                'href' => route('roles.index'),
            ],
            [
                'label' => 'Tambah Peran',
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
                'label' => 'Peran',
                'href' => route('roles.index'),
            ],
            [
                'label' => 'Perbarui Peran',
            ],
        ];
    }
}

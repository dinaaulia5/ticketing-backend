<?php

namespace App\Supports\Pages\User;

class PermissionPage
{
    public static function index(): array
    {
        return [
            'title' => 'Data Izin',
            'subtitle' => 'Kelola daftar Izin dalam sistem.',
            'banner' => [
                'title' => 'Master Izin',
                'subtitle' => 'Data Izin digunakan untuk mengatur hak akses pengguna dalam sistem.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Izin',
            'subtitle' => 'Tambahkan Izin baru ke dalam sistem.',
            'method' => 'POST',
            'action' => route('roles.store'),
        ];
    }

    public static function edit($role): array
    {
        return [
            'title' => 'Perbarui Izin',
            'subtitle' => 'Perbarui informasi Izin yang sudah terdaftar.',
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
                'label' => 'Izin',
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
                'label' => 'Izin',
                'href' => route('roles.index'),
            ],
            [
                'label' => 'Tambah Izin',
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
                'label' => 'Izin',
                'href' => route('roles.index'),
            ],
            [
                'label' => 'Perbarui Izin',
            ],
        ];
    }
}

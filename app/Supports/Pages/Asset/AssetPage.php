<?php

namespace App\Supports\Pages\Asset;

class AssetPage
{
    public static function index(): array
    {
        return [
            'title' => 'Asset Management',
            'subtitle' => 'Kelola dan pantau seluruh aset IT perusahaan.',
            'create' => 'Tambah Aset',
            'banner' => [
                'title' => 'Asset Management',
                'subtitle' => 'Kelola informasi, kondisi, lokasi, dan status seluruh aset IT perusahaan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Aset',
            'subtitle' => 'Tambahkan data aset IT baru ke dalam sistem.',
            'method' => 'POST',
            'action' => route('assets.store'),
        ];
    }

    public static function show($asset): array
    {
        return [
            'title' => 'Detail Aset',
            'subtitle' => 'Lihat informasi lengkap dan riwayat pengelolaan aset IT.',
        ];
    }

    public static function edit($asset): array
    {
        return [
            'title' => 'Perbarui Aset',
            'subtitle' => 'Perbarui informasi aset IT yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('assets.update', $asset),
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
                'label' => 'Asset Management',
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
                'label' => 'Asset Management',
                'href' => route('assets.index'),
            ],
            [
                'label' => 'Tambah Aset',
            ],
        ];
    }

    public static function showBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Asset Management',
                'href' => route('assets.index'),
            ],
            [
                'label' => 'Detail Aset',
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
                'label' => 'Asset Management',
                'href' => route('assets.index'),
            ],
            [
                'label' => 'Perbarui Aset',
            ],
        ];
    }
}

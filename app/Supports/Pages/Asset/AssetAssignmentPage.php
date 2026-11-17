<?php

namespace App\Supports\Pages\Asset;

class AssetAssignmentPage
{
    public static function index(): array
    {
        return [
            'title' => 'Asset Assignment',
            'subtitle' => 'Kelola penyerahan dan pengembalian aset IT kepada karyawan.',
            'create' => 'Buat Penyerahan Aset',
            'banner' => [
                'title' => 'Asset Assignment',
                'subtitle' => 'Pantau penyerahan, pengembalian, dan riwayat penggunaan aset IT oleh karyawan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Penyerahan Aset',
            'subtitle' => 'Catat penyerahan aset IT kepada karyawan.',
            'method' => 'POST',
            'action' => route('asset-assignments.store'),
        ];
    }

    public static function show($assignment): array
    {
        return [
            'title' => 'Detail Penyerahan Aset',
            'subtitle' => 'Lihat informasi penyerahan dan riwayat penggunaan aset oleh karyawan.',
        ];
    }

    public static function edit($assignment): array
    {
        return [
            'title' => 'Perbarui Penyerahan Aset',
            'subtitle' => 'Catat informasi pengembalian aset atau perbarui catatan penyerahan.',
            'method' => 'PUT',
            'action' => route('asset-assignments.update', $assignment),
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
            [
                'label' => 'Asset Assignment',
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
            ],
            [
                'label' => 'Asset Assignment',
                'href' => route('asset-assignments.index'),
            ],
            [
                'label' => 'Penyerahan Aset',
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
            ],
            [
                'label' => 'Asset Assignment',
                'href' => route('asset-assignments.index'),
            ],
            [
                'label' => 'Detail Penyerahan Aset',
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
            ],
            [
                'label' => 'Asset Assignment',
                'href' => route('asset-assignments.index'),
            ],
            [
                'label' => 'Perbarui Penyerahan Aset',
            ],
        ];
    }
}

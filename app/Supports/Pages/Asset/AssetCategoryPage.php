<?php

namespace App\Supports\Pages\Asset;

class AssetCategoryPage
{
    public static function index(): array
    {
        return [
            'title' => 'Asset Category',
            'subtitle' => 'Kelola kategori aset IT yang terdaftar di dalam sistem.',
            'create' => 'Tambah Kategori',
            'banner' => [
                'title' => 'Asset Category',
                'subtitle' => 'Kelola dan atur kategori aset IT untuk memudahkan pengelompokan serta pengelolaan inventaris aset.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Kategori Aset',
            'subtitle' => 'Tambahkan kategori baru untuk mengelompokkan aset IT.',
            'method' => 'POST',
            'action' => route('asset-categories.store'),
        ];
    }

    public static function edit($category): array
    {
        return [
            'title' => 'Perbarui Kategori Aset',
            'subtitle' => 'Perbarui informasi kategori aset yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('asset-categories.update', $category),
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
                'label' => 'Asset Category',
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
                'label' => 'Asset Category',
                'href' => route('asset-categories.index'),
            ],
            [
                'label' => 'Tambah Kategori',
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
                'label' => 'Asset Category',
                'href' => route('asset-categories.index'),
            ],
            [
                'label' => 'Perbarui Kategori',
            ],
        ];
    }
}

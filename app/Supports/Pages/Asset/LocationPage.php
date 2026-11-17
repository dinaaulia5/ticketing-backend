<?php

namespace App\Supports\Pages\Asset;

class LocationPage
{
    public static function index(): array
    {
        return [
            'title' => 'Manajemen Lokasi',
            'description' => 'Kelola data lokasi gedung, lantai, dan ruangan untuk penempatan aset IT.',
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
                'label' => 'Manajemen Aset',
            ],
            [
                'label' => 'Lokasi',
                'href' => route('locations.index'),
            ],
        ];
    }
}

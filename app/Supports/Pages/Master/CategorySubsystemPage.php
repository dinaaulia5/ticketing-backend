<?php

namespace App\Supports\Pages\Master;

class CategorySubsystemPage
{
    public static function index(): array
    {
        return [
            'title' => 'Kategori & Subsystem',
            'subtitle' => 'Kelola kategori dan subsystem layanan IT dalam sistem.',

            'banner' => [
                'title' => 'Master Kategori & Subsystem',
                'subtitle' => 'Kelola kategori layanan IT beserta subsystem yang menjadi bagian dari setiap kategori.',
            ],
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
                'label' => 'Kategori & Subsystem',
            ],
        ];
    }
}

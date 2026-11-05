<?php

namespace App\Supports\Pages;

class DashboardPage
{
    public static function index(): array
    {
        return [
            'title' => 'Dashboard',
            'subtitle' => 'Pantau aktivitas pelatihan, perkembangan peserta, dan statistik pembelajaran secara real-time.',
            'banner' => [
                'title' => 'Selamat Datang di Smart Learning Hub',
                'subtitle' => 'Kelola program pelatihan, peserta, materi pembelajaran, serta pantau progres pembelajaran untuk meningkatkan kompetensi SDM.',
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
                'label' => 'Dashboard',
            ],
        ];
    }
}

<?php

namespace App\Supports\Pages\Ticket;

class TicketPage
{
    public static function index(): array
    {
        return [
            'title' => 'Ticket Management',
            'subtitle' => 'Kelola dan pantau seluruh laporan layanan IT.',
            'create' => 'Buat Tiket',
            'banner' => [
                'title' => 'Ticket Management',
                'subtitle' => 'Kelola dan pantau seluruh laporan layanan IT di dalam sistem.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Tiket',
            'subtitle' => 'Buat tiket baru untuk melaporkan gangguan atau permintaan layanan IT.',
            'method' => 'POST',
            'action' => route('tickets.store'),
        ];
    }

    public static function show($ticket): array
    {
        return [
            'title' => 'Detail Tiket',
            'subtitle' => 'Lihat informasi dan perkembangan penanganan tiket.',
        ];
    }

    public static function edit($ticket): array
    {
        return [
            'title' => 'Perbarui Tiket',
            'subtitle' => 'Perbarui informasi tiket yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('tickets.update', $ticket),
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
                'label' => 'Ticket Management',
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
                'label' => 'Ticket Management',
                'href' => route('tickets.index'),
            ],
            [
                'label' => 'Tambah Tiket',
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
                'label' => 'Ticket Management',
                'href' => route('tickets.index'),
            ],
            [
                'label' => 'Detail Tiket',
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
                'label' => 'Ticket Management',
                'href' => route('tickets.index'),
            ],
            [
                'label' => 'Perbarui Tiket',
            ],
        ];
    }
}

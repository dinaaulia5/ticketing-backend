<?php

namespace App\Supports\Pages\Asset;

class AssetAttachmentPage
{
    public static function index(): array
    {
        return [
            'title' => 'Asset Attachments',
            'subtitle' => 'Kelola dokumen dan file pendukung dari setiap aset IT.',
            'create' => 'Tambah Lampiran',
            'banner' => [
                'title' => 'Asset Attachments',
                'subtitle' => 'Kelola foto, invoice, dokumen garansi, dan dokumen pendukung aset IT perusahaan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Lampiran Aset',
            'subtitle' => 'Unggah dokumen atau file pendukung untuk aset IT.',
            'method' => 'POST',
            'action' => route('asset-attachments.store'),
        ];
    }

    public static function show($attachment): array
    {
        return [
            'title' => 'Detail Lampiran Aset',
            'subtitle' => 'Lihat informasi dan detail dokumen pendukung aset.',
        ];
    }

    public static function edit($attachment): array
    {
        return [
            'title' => 'Perbarui Lampiran Aset',
            'subtitle' => 'Perbarui informasi atau ganti file lampiran aset yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('asset-attachments.update', $attachment),
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
                'label' => 'Asset Attachments',
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
                'label' => 'Asset Attachments',
                'href' => route('asset-attachments.index'),
            ],
            [
                'label' => 'Tambah Lampiran',
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
                'label' => 'Asset Attachments',
                'href' => route('asset-attachments.index'),
            ],
            [
                'label' => 'Detail Lampiran',
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
                'label' => 'Asset Attachments',
                'href' => route('asset-attachments.index'),
            ],
            [
                'label' => 'Perbarui Lampiran',
            ],
        ];
    }
}

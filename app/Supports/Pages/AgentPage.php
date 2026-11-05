<?php

namespace App\Supports\Pages;

class AgentPage
{
    public static function agentLogs(): array
    {
        return [
            'title' => 'Data Riwayat Aktivitas Agen',
            'subtitle' => 'Kelola daftar aktivitas agen untuk kebutuhan penerbangan',
            'banner' => [
                'title' => 'Master Riwayat Aktivitas Agen',
                'subtitle' => 'Data riwayat aktivitas agen digunakan untuk mengatur informasi penerbangan.',
            ],
        ];
    }

    public static function agentRecommendation(): array
    {
        return [
            'title' => 'Data Rekomendasi Agen',
            'subtitle' => 'Kelola daftar Rekomendasi Agen untuk kebutuhan penerbangan',
            'banner' => [
                'title' => 'Master Rekomendasi Agen',
                'subtitle' => 'Data Rekomendasi Agen digunakan untuk mengatur informasi penerbangan.',
            ],
        ];
    }

    public static function breadcrumbsAgentLogs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Agent Logs',
            ],
        ];
    }

    public static function breadcrumbsAgentRecommendation(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Agent Recommendation',
            ],
        ];
    }
}

<?php

namespace App\Services\Ticket;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketAttachment;
use App\Models\Ticket\TicketHistory;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentService
{
    public function store(
        Ticket $ticket,
        array $files,
        User $user
    ): array {
        $uploadedFiles = [];

        try {
            foreach ($files as $file) {

                // Upload file ke storage
                $filePath = $file->store(
                    'ticket-attachments/' . $ticket->id,
                    'public'
                );

                if (!$filePath) {
                    throw new \RuntimeException(
                        'Gagal mengunggah lampiran tiket.'
                    );
                }

                $uploadedFiles[] = $filePath;

                // Simpan informasi lampiran
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'uploaded_by' => $user->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                // Catat aktivitas upload
                TicketHistory::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'activity' => 'attachment_uploaded',
                    'field' => 'attachment',
                    'description' => 'Lampiran diunggah: '
                        . $file->getClientOriginalName(),
                ]);
            }

            return $uploadedFiles;
        } catch (\Throwable $e) {

            // Hapus file yang sudah terlanjur diunggah
            $this->deleteFiles($uploadedFiles);

            throw $e;
        }
    }

    public function deleteFiles(array $filePaths): void
    {
        if (!empty($filePaths)) {
            Storage::disk('public')->delete($filePaths);
        }
    }
}

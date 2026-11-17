<?php

namespace App\Services\Asset;

use App\Models\Asset\Asset;
use App\Models\Asset\AssetAttachment;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class AssetAttachmentService
{
    public function store(Asset $asset, array $files, User $user): array
    {
        $uploadedFiles = [];

        try {
            foreach ($files as $file) {
                $filePath = $file->store('asset-attachments/' . $asset->id, 'public');

                if (!$filePath) {
                    throw new RuntimeException('Gagal mengunggah lampiran aset.');
                }

                $uploadedFiles[] = $filePath;
                AssetAttachment::create([
                    'asset_id' => $asset->id,
                    'uploaded_by' => $user->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }

            return $uploadedFiles;
        } catch (Throwable $e) {
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

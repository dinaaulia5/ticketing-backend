<?php

namespace App\Http\Controllers\Asset;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\AssetAttachmentRequest;
use App\Http\Resources\Asset\AssetAttachmentResource;
use App\Models\Asset\AssetAttachment;
use App\Supports\Pages\Asset\AssetAttachmentPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Throwable;

class AssetAttachmentController extends Controller
{
    public function index(): Response
    {
        $attachments = AssetAttachment::query()
            ->select([
                'id',
                'asset_id',
                'file_name',
                'file_path',
                'file_type',
                'file_size',
                'attachment_type',
                'description',
                'uploaded_by',
                'created_at',
            ])
            ->with([
                'asset:id,asset_code,name',
                'uploader:id,name',
            ])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Asset/Attachment/Index', [
            'pageSettings' => fn() => AssetAttachmentPage::index(),

            'attachments' => fn() => AssetAttachmentResource::collection($attachments)
                ->additional([
                    'meta' => [
                        'has_pages' => $attachments->hasPages(),
                    ],
                ]),

            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => request()->load ?? 10,
                'field' => request()->field ?? 'created_at',
                'direction' => request()->direction ?? 'desc',
            ],

            'items' => fn() => AssetAttachmentPage::breadcrumbs(),
        ]);
    }

    public function store(AssetAttachmentRequest $request): RedirectResponse
    {
        $filePath = null;

        try {
            DB::transaction(function () use ($request, &$filePath) {
                $file = $request->file('file');

                $filePath = $file->store('asset-attachments', 'public');

                AssetAttachment::create([
                    'asset_id' => $request->asset_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'attachment_type' => $request->attachment_type,
                    'description' => $request->description,
                    'uploaded_by' => auth()->id(),
                ]);
            });

            flashMessage(MessageType::CREATED->message('Lampiran Aset'));

            return to_route('asset-attachments.index');
        } catch (Throwable $e) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }

            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-attachments.index');
        }
    }

    public function update(
        AssetAttachmentRequest $request,
        string $id
    ): RedirectResponse {
        $newFilePath = null;
        $oldFilePath = null;

        try {
            $attachment = AssetAttachment::query()->findOrFail($id);

            DB::transaction(function () use (
                $request,
                $attachment,
                &$newFilePath,
                &$oldFilePath
            ) {
                $data = [
                    'asset_id' => $request->asset_id,
                    'attachment_type' => $request->attachment_type,
                    'description' => $request->description,
                ];

                if ($request->hasFile('file')) {
                    $file = $request->file('file');

                    $newFilePath = $file->store('asset-attachments', 'public');

                    $oldFilePath = $attachment->file_path;

                    $data['file_name'] = $file->getClientOriginalName();
                    $data['file_path'] = $newFilePath;
                    $data['file_type'] = $file->getMimeType();
                    $data['file_size'] = $file->getSize();
                }

                $attachment->update($data);
            });

            if ($oldFilePath) {
                Storage::disk('public')->delete($oldFilePath);
            }

            flashMessage(MessageType::UPDATED->message('Lampiran Aset'));

            return to_route('asset-attachments.index');
        } catch (Throwable $e) {
            if ($newFilePath) {
                Storage::disk('public')->delete($newFilePath);
            }

            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-attachments.index');
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $attachment = AssetAttachment::query()->findOrFail($id);

            $filePath = $attachment->file_path;

            $attachment->delete();

            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }

            flashMessage(MessageType::DELETE->message('Lampiran Aset'));

            return to_route('asset-attachments.index');
        } catch (Throwable $e) {
            flashMessage(
                MessageType::ERROR->message(error: $e->getMessage()),
                'error'
            );

            return to_route('asset-attachments.index');
        }
    }
}

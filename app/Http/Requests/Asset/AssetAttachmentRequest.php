<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            // Asset
            'asset_id' => [
                'required',
                'uuid',
                'exists:assets,id',
            ],

            // File Attachment
            'file' => [
                $isUpdate ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx',
                'max:10240',
            ],

            // Attachment Type
            'attachment_type' => [
                'required',
                Rule::in([
                    'photo',
                    'invoice',
                    'warranty',
                    'handover_document',
                    'other',
                ]),
            ],

            // Description
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required' => 'Aset wajib dipilih.',
            'asset_id.exists' => 'Aset tidak ditemukan.',

            'file.required' => 'File lampiran wajib diunggah.',
            'file.file' => 'Lampiran harus berupa file.',
            'file.mimes' => 'Format file harus JPG, PNG, WEBP, PDF, DOC, DOCX, XLS, atau XLSX.',
            'file.max' => 'Ukuran file maksimal 10 MB.',

            'attachment_type.required' => 'Jenis lampiran wajib dipilih.',
            'attachment_type.in' => 'Jenis lampiran tidak valid.',

            'description.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}

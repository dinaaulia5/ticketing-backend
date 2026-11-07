<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->input('category_id');

        return [
            'ticket_type' => [
                'required',
                'string',
                Rule::in(['incident', 'service_request']),
            ],

            'category_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')
                    ->whereNull('deleted_at'),
            ],

            'subsystem_id' => [
                'nullable',
                'uuid',
                Rule::exists('subsystems', 'id')
                    ->where('category_id', $categoryId)
                    ->whereNull('deleted_at'),
            ],

            'title' => [
                'required',
                'string',
                'max:150',
            ],

            'description' => [
                'required',
                'string',
            ],

            'impact' => [
                'nullable',
                'string',
                Rule::in(['low', 'medium', 'high']),
            ],

            'urgency' => [
                'nullable',
                'string',
                Rule::in(['low', 'medium', 'high']),
            ],


            'attachments' => [
                'nullable',
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:5120',
            ],

        ];
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $files = $this->file('attachments', []);

            $totalSize = collect($files)
                ->sum(fn($file) => $file->getSize());

            if ($totalSize > 25 * 1024 * 1024) {
                $validator->errors()->add(
                    'attachments',
                    'Total ukuran lampiran maksimal 25 MB.'
                );
            }
        });
    }


    public function messages(): array
    {
        return [
            'ticket_type.required' => 'Jenis tiket wajib dipilih.',
            'ticket_type.in' => 'Jenis tiket tidak valid.',

            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan atau tidak aktif.',

            'subsystem_id.exists' => 'Subsystem tidak sesuai dengan kategori yang dipilih.',

            'title.required' => 'Judul tiket wajib diisi.',
            'title.max' => 'Judul tiket maksimal 150 karakter.',

            'description.required' => 'Deskripsi tiket wajib diisi.',

            'impact.in' => 'Nilai impact tidak valid.',
            'urgency.in' => 'Nilai urgency tidak valid.',
        ];
    }
}

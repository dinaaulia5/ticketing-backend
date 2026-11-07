<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => [
                'required',
                'string',
                'min:2',
                'max:5000',
            ],

            'is_internal' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Komentar tidak boleh kosong.',
            'comment.string' => 'Komentar harus berupa teks.',
            'comment.min' => 'Komentar minimal 2 karakter.',
            'comment.max' => 'Komentar maksimal 5000 karakter.',

            'is_internal.required' => 'Jenis komentar harus ditentukan.',
            'is_internal.boolean' => 'Jenis komentar tidak valid.',
        ];
    }
}

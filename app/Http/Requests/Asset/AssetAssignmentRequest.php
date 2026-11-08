<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assignment = $this->route('asset_assignment');

        $assignmentId = is_object($assignment)
            ? $assignment->id
            : $assignment;

        return [
            // Asset
            'asset_id' => [
                'required',
                'uuid',
                'exists:assets,id',

                Rule::unique('asset_assignments', 'asset_id')
                    ->whereNull('returned_at')
                    ->ignore($assignmentId),
            ],

            // Employee
            'employee_id' => [
                'required',
                'uuid',
                'exists:employees,id',
            ],

            // Assignment Date
            'assigned_at' => [
                'required',
                'date',
            ],

            // Return Date
            'returned_at' => [
                'nullable',
                'date',
                'after_or_equal:assigned_at',
            ],

            // Notes
            'notes' => [
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
            'asset_id.unique' => 'Aset sedang digunakan oleh karyawan lain.',

            'employee_id.required' => 'Karyawan penerima wajib dipilih.',
            'employee_id.exists' => 'Data karyawan tidak ditemukan.',

            'assigned_at.required' => 'Tanggal penyerahan aset wajib diisi.',
            'assigned_at.date' => 'Format tanggal penyerahan tidak valid.',

            'returned_at.date' => 'Format tanggal pengembalian tidak valid.',
            'returned_at.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal penyerahan.',

            'notes.string' => 'Catatan harus berupa teks.',
        ];
    }
}

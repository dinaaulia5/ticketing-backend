<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubsystemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subsystemId = $this->route('subsystem');

        return [
            'category_id' => [
                'required',
                'uuid',
                Rule::exists('categories', 'id')
                    ->whereNull('deleted_at'),
            ],

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('subsystems', 'code')
                    ->ignore($subsystemId),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.uuid' => 'Format kategori tidak valid.',
            'category_id.exists' => 'Kategori tidak ditemukan atau sudah tidak aktif.',

            'code.required' => 'Kode subsystem wajib diisi.',
            'code.unique' => 'Kode subsystem sudah digunakan.',
            'code.max' => 'Kode subsystem maksimal 20 karakter.',

            'name.required' => 'Nama subsystem wajib diisi.',
            'name.max' => 'Nama subsystem maksimal 100 karakter.',
        ];
    }
}

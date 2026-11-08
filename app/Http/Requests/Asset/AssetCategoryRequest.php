<?php

namespace App\Http\Requests\Asset;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('asset_category');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('asset_categories', 'code')
                    ->ignore($category?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('asset_categories', 'name')
                    ->ignore($category?->id),
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
            'code.required' => 'Kode kategori wajib diisi.',
            'code.unique' => 'Kode kategori sudah digunakan.',
            'code.max' => 'Kode kategori maksimal 20 karakter.',

            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',

            'description.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}

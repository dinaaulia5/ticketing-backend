<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('asset_category');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('asset_categories', 'code')
                    ->ignore($categoryId),
            ],

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('asset_categories', 'name')
                    ->ignore($categoryId),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode kategori wajib diisi.',
            'code.string' => 'Kode kategori harus berupa teks.',
            'code.max' => 'Kode kategori maksimal 20 karakter.',
            'code.unique' => 'Kode kategori sudah digunakan.',

            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Nama kategori sudah digunakan.',

            'description.string' => 'Deskripsi kategori harus berupa teks.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'code' => 'kode kategori',
            'name' => 'nama kategori',
            'description' => 'deskripsi',
        ];
    }
}

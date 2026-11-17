<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $location = $this->route('location');

        // Mendukung route parameter berupa ID maupun model.
        $locationId = is_object($location)
            ? $location->id
            : $location;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('locations', 'code')->ignore($locationId),
            ],

            'location_name' => [
                'required',
                'string',
                'max:100',
            ],

            'building' => [
                'nullable',
                'string',
                'max:100',
            ],

            'floor' => [
                'nullable',
                'string',
                'max:50',
            ],

            'room' => [
                'nullable',
                'string',
                'max:50',
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
            'code.required' => 'Kode lokasi wajib diisi.',
            'code.unique' => 'Kode lokasi sudah digunakan.',
            'location_name.required' => 'Nama lokasi wajib diisi.',
        ];
    }
}

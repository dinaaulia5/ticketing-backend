<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $asset = $this->route('asset');

        return [
            // Asset Identity
            'asset_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('assets', 'asset_code')->ignore($asset?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'asset_category_id' => [
                'required',
                'uuid',
                'exists:asset_categories,id',
            ],

            // Asset Information
            'brand' => [
                'nullable',
                'string',
                'max:100',
            ],

            'model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('assets', 'serial_number')->ignore($asset?->id),
            ],

            // Technical Specifications
            'processor' => [
                'nullable',
                'string',
                'max:150',
            ],

            'ram' => [
                'nullable',
                'string',
                'max:50',
            ],

            'storage' => [
                'nullable',
                'string',
                'max:100',
            ],

            'os' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ip_address' => [
                'nullable',
                'ip',
            ],

            'mac_address' => [
                'nullable',
                'string',
                'max:50',
            ],

            // Location
            'location_id' => [
                'nullable',
                'uuid',
                'exists:locations,id',
            ],

            // Purchase Information
            'purchase_date' => [
                'nullable',
                'date',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'warranty_expiry' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date',
            ],

            'end_of_life' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date',
            ],

            // Condition & Status
            'condition' => [
                'required',
                Rule::in(['good', 'fair', 'damaged']),
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'assigned',
                    'maintenance',
                    'retired',
                ]),
            ],

            // Additional Information
            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_code.required' => 'Kode aset wajib diisi.',
            'asset_code.unique' => 'Kode aset sudah digunakan.',

            'name.required' => 'Nama aset wajib diisi.',

            'asset_category_id.required' => 'Kategori aset wajib dipilih.',
            'asset_category_id.exists' => 'Kategori aset tidak ditemukan.',

            'serial_number.unique' => 'Nomor seri aset sudah terdaftar.',

            'ip_address.ip' => 'Format IP Address tidak valid.',

            'location_id.exists' => 'Lokasi aset tidak ditemukan.',

            'purchase_price.numeric' => 'Harga pembelian harus berupa angka.',
            'purchase_price.min' => 'Harga pembelian tidak boleh negatif.',

            'warranty_expiry.after_or_equal' => 'Tanggal garansi tidak boleh sebelum tanggal pembelian.',
            'end_of_life.after_or_equal' => 'Tanggal akhir masa pakai tidak boleh sebelum tanggal pembelian.',

            'condition.required' => 'Kondisi aset wajib dipilih.',
            'condition.in' => 'Kondisi aset tidak valid.',

            'status.required' => 'Status aset wajib dipilih.',
            'status.in' => 'Status aset tidak valid.',
        ];
    }
}

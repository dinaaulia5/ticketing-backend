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
        $assetId = $this->route('asset');

        return [
            // Asset Identity
            'asset_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('assets', 'asset_code')->ignore($assetId),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'asset_category_id' => [
                'required',
                'uuid',
                Rule::exists('asset_categories', 'id')
                    ->whereNull('deleted_at'),
            ],

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
                Rule::unique('assets', 'serial_number')->ignore($assetId),
            ],

            // Technical Specification
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
                Rule::exists('locations', 'id')
                    ->whereNull('deleted_at'),
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
                'string',
                Rule::in(['good', 'fair', 'damaged']),
            ],

            'status' => [
                'required',
                'string',
                Rule::in(['available', 'assigned', 'maintenance', 'retired']),
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            // Attachments
            'attachments' => [
                'nullable',
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
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
            'asset_code.required' => 'Kode aset wajib diisi.',
            'asset_code.unique' => 'Kode aset sudah digunakan.',
            'asset_code.max' => 'Kode aset maksimal 50 karakter.',

            'name.required' => 'Nama aset wajib diisi.',
            'name.max' => 'Nama aset maksimal 150 karakter.',

            'asset_category_id.required' => 'Kategori aset wajib dipilih.',
            'asset_category_id.exists' => 'Kategori aset tidak ditemukan atau tidak aktif.',

            'serial_number.unique' => 'Nomor serial aset sudah digunakan.',

            'ip_address.ip' => 'Format IP Address tidak valid.',

            'location_id.exists' => 'Lokasi tidak ditemukan atau tidak aktif.',

            'purchase_price.numeric' => 'Harga pembelian harus berupa angka.',
            'purchase_price.min' => 'Harga pembelian tidak boleh kurang dari 0.',

            'warranty_expiry.after_or_equal' => 'Tanggal garansi tidak boleh sebelum tanggal pembelian.',
            'end_of_life.after_or_equal' => 'Tanggal akhir masa pakai tidak boleh sebelum tanggal pembelian.',

            'condition.required' => 'Kondisi aset wajib dipilih.',
            'condition.in' => 'Kondisi aset tidak valid.',

            'status.required' => 'Status aset wajib dipilih.',
            'status.in' => 'Status aset tidak valid.',

            'attachments.array' => 'Format lampiran tidak valid.',
            'attachments.max' => 'Maksimal 5 file lampiran.',
            'attachments.*.file' => 'Lampiran harus berupa file.',
            'attachments.*.mimes' => 'Format lampiran harus JPG, JPEG, PNG, PDF, DOC, DOCX, XLS, atau XLSX.',
            'attachments.*.max' => 'Ukuran setiap lampiran maksimal 5 MB.',
        ];
    }
}

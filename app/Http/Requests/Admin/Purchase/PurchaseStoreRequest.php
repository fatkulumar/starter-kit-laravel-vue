<?php

namespace App\Http\Requests\Admin\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseStoreRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tasks' => 'required|array',
            'tasks.*.label' => 'required|string|max:255',
            'tasks.*.file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tryout_id' => 'required|array',
            'amount' => 'required|integer',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'tasks.required' => 'Daftar tugas wajib diisi.',
            'tasks.array' => 'Format daftar tugas tidak valid.',

            'tasks.*.label.required' => 'Label pada setiap tugas wajib diisi.',
            'tasks.*.label.string' => 'Label tugas harus berupa teks.',
            'tasks.*.label.max' => 'Label tugas tidak boleh lebih dari :max karakter.',

            'tasks.*.file.required' => 'Butki wajib di isi.',
            'tasks.*.file.image' => 'Bukti pada tugas harus berupa gambar.',
            'tasks.*.file.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'tasks.*.file.max' => 'Ukuran gambar maksimal 2MB.',

            'tryout_id.required' => 'Grade ID wajib diisi.',
            'tryout_id.uuid' => 'Grade ID harus berupa UUID.',
            'tryout_id.exists' => 'Grade ID tidak ditemukan dalam database.',

            'amount.required' => 'Amount wajib diisi.',
            'amount.integer' => 'Amount harus berupa angla.',
        ];
    }
}

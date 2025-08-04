<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'user_id' => ['required', 'array'],
            'tryout_id' => 'required|uuid|exists:tryouts,id',
            'amount' => 'required|integer',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Harga wajib diisi.',
            'amount.integer' => 'Harga harus berupa angka.',

            'tryout_id.required' => 'Grade ID wajib diisi.',
            'tryout_id.uuid' => 'Grade ID harus berupa UUID.',
            'tryout_id.exists' => 'Grade ID tidak ditemukan dalam database.',

            'amount.required' => 'Amount wajib diisi.',
            'amount.integer' => 'Amount harus berupa angla.',
        ];
    }
}

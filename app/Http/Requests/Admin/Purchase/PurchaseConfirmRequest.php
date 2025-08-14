<?php

namespace App\Http\Requests\Admin\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseConfirmRequest extends FormRequest
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
            'id' => 'required|uuid|exists:orders,id',
            'status' => 'required|in:pending,paid,failed,cancelled'
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID order wajib diisi.',
            'id.uuid' => 'ID order tidak sesuai.',
            'id.exists' => 'ID order tidak ditemukan.',

            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak ada.',
        ];
    }
}

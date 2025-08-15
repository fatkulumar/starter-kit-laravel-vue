<?php

namespace App\Http\Requests\Admin\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchasecConfrimAllRequest extends FormRequest
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
            'ids' => ['required', 'array'],
            'status' => 'required', 'string', 'in:panding,paid,failed,cancelled'
        ];
    }

    public function messages(): array
    {
         return [
            'ids.required'   => 'Data ID pembelian wajib diisi.',
            'ids.array'      => 'Data ID pembelian harus berupa array.',
            'status.required'=> 'Status wajib diisi.',
            'status.string'  => 'Status harus berupa teks.',
            'status.in'      => 'Status harus salah satu dari: pending, paid, failed, atau cancelled.',
        ];
    }
}

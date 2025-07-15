<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama wajib huruf.',
            'name.max' => 'Nama wajib maksimal 255 huruf.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.lowercase' => 'Email harus huruf kecil.',
            'email.unique' => 'Email sudah terdaftar.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
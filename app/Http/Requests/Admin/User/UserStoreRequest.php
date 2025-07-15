<?php

namespace App\Http\Requests\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[A-Z])(?=.*[\W_])(?=.*\d).+$/'],
            'role' => ['required', 'in:admin,member'],
            'photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.regex' => 'Password harus memiliki minimal 1 huruf kapital, 1 angka, dan 1 karakter spesial.',
            'password.min' => 'Password minimal harus 6 karakter',
            'role.required' => 'Role wajib dipilih.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}

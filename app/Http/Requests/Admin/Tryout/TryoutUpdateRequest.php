<?php

namespace App\Http\Requests\Admin\Tryout;

use Illuminate\Foundation\Http\FormRequest;

class TryoutUpdateRequest extends FormRequest
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
            'id' => 'required|string|max:36',
            'event_id' => 'required|uuid|exists:events,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|before_or_equal:end_time',
            'end_time' => 'required|date|after_or_equal:start_time',
            'duration' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'is_locked' => 'required|boolean',
            'guide_link' => 'nullable|url',
            'price' => 'required|integer|min:0',
        ];
    }

    /**
     * override message
     */
    public function messages(): array
    {
        return [
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Format thumbnail harus jpg, jpeg, png, atau webp.',
            'thumbnail.max' => 'Ukuran maksimal thumbnail adalah 2MB.',

            'event_id.required' => 'Event ID wajib diisi.',
            'event_id.uuid' => 'Event ID harus berupa UUID.',
            'event_id.exists' => 'Event ID tidak ditemukan dalam database.',

            'title.required' => 'Judul tryout wajib diisi.',
            'title.max' => 'Judul tryout maksimal 255 karakter.',

            'description.string' => 'Deskripsi harus berupa teks.',

            'start_time.required' => 'Waktu mulai wajib diisi.',
            'start_time.date' => 'Waktu mulai harus berupa tanggal yang valid.',
            'start_time.before_or_equal' => 'Waktu mulai harus sebelum atau sama dengan waktu selesai.',

            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.date' => 'Waktu selesai harus berupa tanggal yang valid.',
            'end_time.after_or_equal' => 'Waktu selesai harus setelah atau sama dengan waktu mulai.',

            'duration.required' => 'Durasi wajib diisi.',
            'duration.integer' => 'Durasi harus berupa angka.',
            'duration.min' => 'Durasi minimal adalah 1 menit.',

            'is_active.required' => 'Status aktif wajib diisi.',
            'is_active.boolean' => 'Status aktif harus berupa true/false.',

            'is_locked.required' => 'Status terkunci wajib diisi.',
            'is_locked.boolean' => 'Status terkunci harus berupa true/false.',

            'guide_link.url' => 'Link panduan harus berupa URL yang valid.',

            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
        ];
    }
}

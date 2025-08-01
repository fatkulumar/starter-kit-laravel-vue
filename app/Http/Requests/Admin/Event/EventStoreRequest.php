<?php

namespace App\Http\Requests\Admin\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // jika upload file
            'start_time' => 'required|date|before_or_equal:end_time',
            'end_time' => 'required|date|after_or_equal:start_time',
            'registration_deadline' => 'required|date|after_or_equal:start_time',
            'preliminary_date' => 'required|date|after_or_equal:registration_deadline',
            'final_date' => 'required|date|after_or_equal:preliminary_date',
            'whatsapp_group_link' => 'required|url',
            'guidebook_link' => 'required|url',
            'location' => 'required|string|max:255',
            'is_online' => 'required|boolean',
            'link_zoom' => 'nullable|required_if:is_online,true|url',
            'quota' => 'nullable|integer|min:1',
        ];
    }

    /**
     * override message
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul acara wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'start_time.date' => 'Waktu mulai harus berupa tanggal.',
            'start_time.before_or_equal' => 'Waktu mulai harus sebelum atau sama dengan waktu selesai.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.date' => 'Waktu selesai harus berupa tanggal.',
            'end_time.after_or_equal' => 'Waktu selesai harus setelah atau sama dengan waktu mulai.',
            'registration_deadline.required' => 'Batas registrasi wajib diisi.',
            'registration_deadline.before_or_equal' => 'Batas registrasi harus sebelum waktu mulai.',
            'preliminary_date.required' => 'Tanggal penyisihan wajib diisi.',
            'preliminary_date.after_or_equal' => 'Tanggal penyisihan harus setelah batas registrasi.',
            'final_date.required' => 'Tanggal final wajib diisi.',
            'final_date.after_or_equal' => 'Tanggal final harus setelah tanggal penyisihan.',
            'whatsapp_group_link.required' => 'Link grup WhatsApp wajib diisi.',
            'whatsapp_group_link.url' => 'Link grup WhatsApp harus berupa URL yang valid.',
            'guidebook_link.required' => 'Link panduan wajib diisi.',
            'guidebook_link.url' => 'Link panduan harus berupa URL yang valid.',
            'location.required' => 'Lokasi acara wajib diisi.',
            'is_online.required' => 'Status online wajib dipilih.',
            'is_online.boolean' => 'Status online harus berupa true/false.',
            'link_zoom.required_if' => 'Link Zoom wajib diisi jika event online.',
            'link_zoom.url' => 'Link Zoom harus berupa URL.',
            'quota.integer' => 'Kuota harus berupa angka.',
            'quota.min' => 'Kuota minimal 1 peserta.',
            'banner.image' => 'Banner harus berupa gambar.',
            'banner.mimes' => 'Banner hanya boleh berformat jpg, jpeg, png, atau webp.',
            'banner.max' => 'Ukuran banner maksimal 2MB.',
        ];
    }
}

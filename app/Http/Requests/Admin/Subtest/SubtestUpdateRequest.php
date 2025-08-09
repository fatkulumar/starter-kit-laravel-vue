<?php

namespace App\Http\Requests\Admin\Subtest;

use Illuminate\Foundation\Http\FormRequest;

class SubtestUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'tryout_id' => 'required|uuid|exists:tryouts,id',
            'subject_id' => 'nullable|uuid|exists:subjects,id',
            'amount_question' => 'required|integer|min:1',
            'amount_minutes' => 'required|integer|min:1',
        ];
    }

    /**
     * override message
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul subtest wajib diisi.',
            'title.string' => 'Judul subtest harus berupa teks.',
            'title.max' => 'Judul subtest tidak boleh lebih dari :max karakter.',

            'tryout_id.required' => 'Tryout wajib diisi.',
            'tryout_id.uuid' => 'Format ID tryout tidak valid.',
            'tryout_id.exists' => 'Tryout yang dipilih tidak ditemukan.',

            'subject_id.required' => 'Mata Pelajaran wajib diisi.',
            'subject_id.uuid' => 'Format ID Mata Pelajaran tidak valid.',
            'subject_id.exists' => 'Mata Pelajaran yang dipilih tidak ditemukan.',

            'amount_question.required' => 'Jumlah soal wajib diisi.',
            'amount_question.integer' => 'Jumlah soal harus berupa angka.',
            'amount_question.min' => 'Jumlah soal minimal :min.',

            'amount_minutes.required' => 'Jumlah soal wajib diisi.',
            'amount_minutes.integer' => 'Jumlah soal harus berupa angka.',
            'amount_minutes.min' => 'Jumlah soal minimal :min.',
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Question;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
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
            'subtest_id' => 'nullable|uuid|exists:subtests,id',
            'subject_id' => 'nullable|uuid|exists:subjects,id',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'required|string',
            'correct_answer' => 'required|string|in:a,b,c,d,e',
            'explanation' => 'required|string',
        ];
    }

    /**
     * override message
     */
    public function messages(): array
    {
        return [
            'subtest_id.uuid' => 'Subtest tidak valid.',
            'subtest_id.exists' => 'Subtest yang dipilih tidak ditemukan.',
            'subject_id.uuid' => 'Mata pelajaran tidak valid.',
            'subject_id.exists' => 'Mata pelajaran yang dipilih tidak ditemukan.',

            'option_a.required' => 'Pilihan A wajib diisi.',
            'option_a.string' => 'Pilihan A harus berupa teks.',
            'option_b.required' => 'Pilihan B wajib diisi.',
            'option_b.string' => 'Pilihan B harus berupa teks.',
            'option_c.required' => 'Pilihan C wajib diisi.',
            'option_c.string' => 'Pilihan C harus berupa teks.',
            'option_d.required' => 'Pilihan D wajib diisi.',
            'option_d.string' => 'Pilihan D harus berupa teks.',
            'option_e.required' => 'Pilihan E wajib diisi.',
            'option_e.string' => 'Pilihan E harus berupa teks.',

            'correct_answer.required' => 'Jawaban benar wajib diisi.',
            'correct_answer.string' => 'Jawaban benar harus berupa teks.',
            'correct_answer.in' => 'Jawaban benar hanya boleh salah satu dari: A, B, C, D, atau E.',

            'explanation.required' => 'Pembahasan wajib diisi.',
            'explanation.string' => 'Pembahasan harus berupa teks.',
        ];
    }
}

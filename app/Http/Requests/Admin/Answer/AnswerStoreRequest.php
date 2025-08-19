<?php

namespace App\Http\Requests\Admin\Answer;

use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
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
            'subtest_id' => 'required|uuid|exists:subtests,id',
            'question_id' => 'required|uuid|exists:questions,id',
            'answer' => 'required|in:A,B,C,D,E',
        ];
    }

    /**
     * override message
     */
    public function messages(): array
    {
        return [
            'subtest_id.required' => 'Subtest wajib diisi.',
            'subtest_id.uuid' => 'Subtest ID tidak valid.',
            'subtest_id.exists' => 'Subtest tidak ditemukan.',

            'question_id.required' => 'Pertanyaan wajib diisi.',
            'question_id.uuid' => 'Pertanyaan ID tidak valid.',
            'question_id.exists' => 'Pertanyaan tidak ditemukan.',

            'answer.required' => 'Jawaban wajib dipilih.',
            'answer.in' => 'Jawaban harus salah satu dari: A, B, C, D, atau E.',
        ];
    }
}

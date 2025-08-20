<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class FinishExamRequest extends FormRequest
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
            'answers' => ['required', 'array'], // pastikan ada array
            'answers.*.subtest_id' => ['required', 'uuid'], // tiap item wajib ada subtest_id
            'answers.*.question_id' => ['required', 'uuid'], // tiap item wajib ada question_id
            'answers.*.answer' => ['nullable', 'string', 'max:1'], // jawaban bisa null
        ];
    }

    /**
     * override message
     */
    public function messages(): array
    {
        return [
            'answers.required' => 'Jawaban tidak boleh kosong.',
            'answers.array' => 'Format jawaban harus array.',
            'answers.*.subtest_id.required' => 'Subtest ID wajib diisi.',
            'answers.*.question_id.required' => 'Question ID wajib diisi.',
            'answers.*.answer.string' => 'Jawaban harus berupa string.',
        ];
    }
}

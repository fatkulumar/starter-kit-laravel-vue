<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Subtest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'subtest_id' => Subtest::factory(),
            'question_id' => Question::factory(),
            'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E'])
        ];
    }
}

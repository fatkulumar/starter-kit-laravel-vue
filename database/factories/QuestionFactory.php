<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Subtest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question--model=Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtest = Subtest::inRandomOrder()->first();
        
        return [
            'subtest_id' => $subtest->id,
            'subject_id' => Subject::inRandomOrder()->first()->id,
            'option_a' => $this->faker->sentence,
            'option_b' => $this->faker->sentence,
            'option_c' => $this->faker->sentence,
            'option_d' => $this->faker->sentence,
            'option_e' => $this->faker->sentence,
            'correct_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'explanation' => $this->faker->paragraph,
        ];
    }
}

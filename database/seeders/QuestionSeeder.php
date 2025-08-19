<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Subtest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subtests = Subtest::all();

        foreach ($subtests as $subtest) {
            // cek amount_question
            $amount = $subtest->amount_question ?? 0;

            if ($amount > 0) {
                Question::factory()->count($amount)->create([
                    'subtest_id' => $subtest->id,
                ]);
            }
        }
    }
}

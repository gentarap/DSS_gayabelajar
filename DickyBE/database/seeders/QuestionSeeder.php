<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question_text' => 'Saya lebih mudah mengingat sesuatu jika saya melihat gambar atau diagram.',
                'answers' => [
                    ['answer_type' => 'Setuju', 'learning_type' => 'visual'],
                    ['answer_type' => 'Tidak Setuju', 'learning_type' => 'auditory'],
                ]
            ],
            [
                'question_text' => 'Saya lebih mudah belajar dengan mendengarkan penjelasan.',
                'answers' => [
                    ['answer_type' => 'Setuju', 'learning_type' => 'auditory'],
                    ['answer_type' => 'Tidak Setuju', 'learning_type' => 'visual'],
                ]
            ],
            [
                'question_text' => 'Saya suka belajar dengan menyentuh atau mempraktikkan langsung.',
                'answers' => [
                    ['answer_type' => 'Setuju', 'learning_type' => 'kinesthetic'],
                    ['answer_type' => 'Tidak Setuju', 'learning_type' => 'visual'],
                ]
            ],
            // Tambahkan pertanyaan lain di sini...
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'question_text' => $q['question_text']
            ]);

            foreach ($q['answers'] as $a) {
                Answer::create([
                    'question_id' => $question->question_id, // Gunakan question_id, bukan id
                    'answer_type' => $a['answer_type'],    // Sesuai dengan migration
                    'learning_type' => $a['learning_type'] // Sesuai dengan migration
                ]);
            }
        }
    }
}

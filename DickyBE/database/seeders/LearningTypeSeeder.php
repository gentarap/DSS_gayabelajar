<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningStyle;

class LearningTypeSeeder extends Seeder
{
    public function run(): void
    {
        $styles = [
            [
                'gaya_belajar' => 'Visual',
                'deskripsi' => 'Pelajar visual lebih mudah memahami informasi melalui gambar, diagram, dan representasi visual lainnya.',
                'rekomendasi' => 'Gunakan mind mapping, flashcards, diagram, video edukatif, dan catatan berwarna. Buat ringkasan dengan bullet points dan gambar ilustrasi.'
            ],
            [
                'gaya_belajar' => 'Auditory',
                'deskripsi' => 'Pelajar auditori lebih mudah memahami informasi melalui mendengar dan berbicara.',
                'rekomendasi' => 'Dengarkan podcast, rekam materi pelajaran, diskusi kelompok, baca keras-keras, dan gunakan musik untuk membantu konsentrasi.'
            ],
            [
                'gaya_belajar' => 'Kinestetik',
                'deskripsi' => 'Pelajar kinestetik lebih mudah belajar melalui gerakan, sentuhan, dan praktik langsung.',
                'rekomendasi' => 'Lakukan eksperimen, praktik langsung, belajar sambil berjalan, gunakan manipulatif, dan ambil banyak istirahat untuk bergerak.'
            ]
        ];

        foreach ($styles as $style) {
            LearningStyle::create($style);
        }
    }
}

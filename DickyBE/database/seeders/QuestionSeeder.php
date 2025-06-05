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
                'question_text' => 'Kamu suka apa?',
                'answers' => [
                    ['answer_text' => 'Suka musik', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Menonton film', 'learning_type' => 'visual'],
                    ['answer_text' => 'Menari', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Aktivitas apa yang paling kamu nikmati?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan podcast', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Melihat foto-foto', 'learning_type' => 'visual'],
                    ['answer_text' => 'Bermain olahraga', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika belajar hal baru, kamu lebih memilih?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan penjelasan', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Membaca buku', 'learning_type' => 'visual'],
                    ['answer_text' => 'Mencoba langsung', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa hobi favoritmu?',
                'answers' => [
                    ['answer_text' => 'Bernyanyi', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Menggambar', 'learning_type' => 'visual'],
                    ['answer_text' => 'Berkebun', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika berada di alam, kamu suka?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan suara alam', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Mengamati pemandangan', 'learning_type' => 'visual'],
                    ['answer_text' => 'Berjalan-jalan atau mendaki', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Kegiatan apa yang paling menenangkan untukmu?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan musik instrumental', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Melihat karya seni', 'learning_type' => 'visual'],
                    ['answer_text' => 'Memijat atau dipijat', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika menghafal, kamu lebih suka?',
                'answers' => [
                    ['answer_text' => 'Mengucapkan berulang-ulang', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Membuat mind map', 'learning_type' => 'visual'],
                    ['answer_text' => 'Menulis berulang-ulang', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang biasanya kamu lakukan saat waktu luang?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan radio', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Menonton YouTube', 'learning_type' => 'visual'],
                    ['answer_text' => 'Bermain dengan hewan peliharaan', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih hadiah, kamu lebih suka?',
                'answers' => [
                    ['answer_text' => 'Album musik', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Poster atau lukisan', 'learning_type' => 'visual'],
                    ['answer_text' => 'Perangkat olahraga', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa cara terbaik bagimu untuk bersantai?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan suara hujan', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Melihat langit malam', 'learning_type' => 'visual'],
                    ['answer_text' => 'Berendam di air hangat', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika mempelajari keterampilan baru, kamu?',
                'answers' => [
                    ['answer_text' => 'Minta dijelaskan langkah-langkahnya', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Melihat diagram atau video tutorial', 'learning_type' => 'visual'],
                    ['answer_text' => 'Langsung mencoba sambil belajar', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang paling menarik perhatianmu di museum?',
                'answers' => [
                    ['answer_text' => 'Tur audio guide', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Pameran visual', 'learning_type' => 'visual'],
                    ['answer_text' => 'Eksperimen interaktif', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih kursus, kamu lebih tertarik pada?',
                'answers' => [
                    ['answer_text' => 'Kelas diskusi', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Presentasi slide', 'learning_type' => 'visual'],
                    ['answer_text' => 'Workshop praktik', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa aplikasi favoritmu di smartphone?',
                'answers' => [
                    ['answer_text' => 'Aplikasi musik/podcast', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Aplikasi foto/video', 'learning_type' => 'visual'],
                    ['answer_text' => 'Aplikasi fitness/game', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika menjelaskan sesuatu, kamu cenderung?',
                'answers' => [
                    ['answer_text' => 'Menggunakan analogi verbal', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Menggambar atau menunjuk', 'learning_type' => 'visual'],
                    ['answer_text' => 'Memperagakan dengan gerakan', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa cara terbaik bagimu untuk mengingat nomor telepon?',
                'answers' => [
                    ['answer_text' => 'Mengucapkannya berulang', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Membayangkan tampilan angka', 'learning_type' => 'visual'],
                    ['answer_text' => 'Mengetiknya berulang', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih restoran, kamu lebih memperhatikan?',
                'answers' => [
                    ['answer_text' => 'Rekomendasi dari teman', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Tampilan menu dan interior', 'learning_type' => 'visual'],
                    ['answer_text' => 'Kenyamanan kursi dan suasana', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang paling kamu sukai dari pantai?',
                'answers' => [
                    ['answer_text' => 'Suara ombak', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Pemandangan matahari terbenam', 'learning_type' => 'visual'],
                    ['answer_text' => 'Rasanya pasir dan air laut', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika membeli baju, kamu lebih memilih berdasarkan?',
                'answers' => [
                    ['answer_text' => 'Rekomendasi penjual', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Tampilan dan warna', 'learning_type' => 'visual'],
                    ['answer_text' => 'Kenyamanan saat dipakai', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang kamu lakukan saat menunggu antrian?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan musik', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Mengamati sekitar', 'learning_type' => 'visual'],
                    ['answer_text' => 'Berdiri bergerak atau mondar-mandir', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih buku, kamu lebih tertarik pada?',
                'answers' => [
                    ['answer_text' => 'Buku audio', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Buku dengan banyak ilustrasi', 'learning_type' => 'visual'],
                    ['answer_text' => 'Buku aktivitas/interaktif', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang kamu perhatikan pertama kali pada seseorang?',
                'answers' => [
                    ['answer_text' => 'Suara dan cara bicaranya', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Penampilan dan ekspresinya', 'learning_type' => 'visual'],
                    ['answer_text' => 'Sikap dan gerak-geriknya', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika merasa stres, kamu cenderung?',
                'answers' => [
                    ['answer_text' => 'Berbicara dengan seseorang', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Membayangkan tempat tenang', 'learning_type' => 'visual'],
                    ['answer_text' => 'Berolahraga atau berjalan-jalan', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang paling kamu nikmati dalam pesta?',
                'answers' => [
                    ['answer_text' => 'Musik dan percakapan', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Dekorasi dan pencahayaan', 'learning_type' => 'visual'],
                    ['answer_text' => 'Menari dan bermain game', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih mobil, kamu lebih memperhatikan?',
                'answers' => [
                    ['answer_text' => 'Suara mesin', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Desain eksterior', 'learning_type' => 'visual'],
                    ['answer_text' => 'Kenyamanan berkendara', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang kamu sukai dari hewan peliharaan?',
                'answers' => [
                    ['answer_text' => 'Suaranya (misal: dengkuran kucing)', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Penampilan lucunya', 'learning_type' => 'visual'],
                    ['answer_text' => 'Sensasi membelai bulunya', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih tempat liburan, kamu lebih mempertimbangkan?',
                'answers' => [
                    ['answer_text' => 'Cerita tentang tempat tersebut', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Pemandangan yang indah', 'learning_type' => 'visual'],
                    ['answer_text' => 'Aktivitas yang bisa dilakukan', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang kamu lakukan saat tidak bisa tidur?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan white noise', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Membayangkan pemandangan tenang', 'learning_type' => 'visual'],
                    ['answer_text' => 'Mengubah posisi tidur', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika memilih makanan, kamu lebih mempertimbangkan?',
                'answers' => [
                    ['answer_text' => 'Rekomendasi dari orang', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Penampilan makanan', 'learning_type' => 'visual'],
                    ['answer_text' => 'Tekstur saat dimakan', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika mengingat suatu peristiwa, apa yang paling kamu ingat?',
                'answers' => [
                    ['answer_text' => 'Percakapan atau suara yang terjadi', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Gambaran visual kejadiannya', 'learning_type' => 'visual'],
                    ['answer_text' => 'Perasaan atau gerakan yang dilakukan', 'learning_type' => 'kinesthetic'],
                ],
            ],
            [
                'question_text' => 'Apa yang paling kamu nikmati saat berkemah?',
                'answers' => [
                    ['answer_text' => 'Suara alam di malam hari', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Pemandangan bintang', 'learning_type' => 'visual'],
                    ['answer_text' => 'Aktivitas mendirikan tenda', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang biasanya kamu lakukan saat menunggu teman di kafe?',
                'answers' => [
                    ['answer_text' => 'Mendengarkan musik atau podcast', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Mengamati sekitar atau melihat sosial media', 'learning_type' => 'visual'],
                    ['answer_text' => 'Memainkan sesuatu di meja atau menggoyangkan kaki', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Bagaimana cara kamu memilih baju di pagi hari?',
                'answers' => [
                    ['answer_text' => 'Mengingat komentar orang tentang pakaianmu', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Membayangkan kombinasi warna dan gaya', 'learning_type' => 'visual'],
                    ['answer_text' => 'Mencoba beberapa baju sampai merasa nyaman', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika mengajar sesuatu ke orang lain, kamu cenderung:',
                'answers' => [
                    ['answer_text' => 'Menjelaskan secara lisan dengan detail', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Menunjukkan gambar atau diagram', 'learning_type' => 'visual'],
                    ['answer_text' => 'Memandu tangan mereka langsung', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Apa yang paling menarik perhatianmu di taman?',
                'answers' => [
                    ['answer_text' => 'Suara burung dan gemerisik daun', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Warna-warni bunga dan pemandangan', 'learning_type' => 'visual'],
                    ['answer_text' => 'Sensasi berjalan di atas rumput atau bermain ayunan', 'learning_type' => 'kinesthetic'],
                ]
            ],
            [
                'question_text' => 'Ketika merasa bosan di kelas, kamu biasanya:',
                'answers' => [
                    ['answer_text' => 'Mengobrol diam-diam dengan teman', 'learning_type' => 'auditory'],
                    ['answer_text' => 'Menggambar atau melamun', 'learning_type' => 'visual'],
                    ['answer_text' => 'Bergerak gelisah atau memainkan pena', 'learning_type' => 'kinesthetic'],
                ]
            ],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'question_text' => $q['question_text']
            ]);

            foreach ($q['answers'] as $a) {
                Answer::create([
                    'question_id' => $question->question_id,
                    'answer_text' => $a['answer_text'],
                    'learning_type' => $a['learning_type']
                ]);
            }
        }
    }
}

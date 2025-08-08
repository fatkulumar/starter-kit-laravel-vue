<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['Matematika', 'MAT'],
            ['Bahasa Indonesia', 'BIN'],
            ['Bahasa Inggris', 'BIG'],
            ['Ilmu Pengetahuan Alam', 'IPA'],
            ['Ilmu Pengetahuan Sosial', 'IPS'],
            ['Pendidikan Pancasila dan Kewarganegaraan', 'PPKN'],
            ['Pendidikan Jasmani, Olahraga, dan Kesehatan', 'PJOK'],
            ['Seni Budaya', 'SB'],
            ['Prakarya', 'PRK'],
            ['Bahasa Daerah', 'BD'],
            ['Agama Islam', 'AGI'],
            ['Agama Kristen', 'AGK'],
            ['Agama Katolik', 'AGKA'],
            ['Agama Hindu', 'AGH'],
            ['Agama Buddha', 'AGB'],
            ['Agama Konghucu', 'AGKO'],
            ['Fisika', 'FIS'],
            ['Kimia', 'KIM'],
            ['Biologi', 'BIO'],
            ['Ekonomi', 'EKO'],
            ['Geografi', 'GEO'],
            ['Sosiologi', 'SOS'],
            ['Sejarah Indonesia', 'SEJID'],
            ['Sejarah Dunia', 'SEJDU'],
            ['Teknologi Informasi dan Komunikasi', 'TIK'],
            ['Informatika', 'INF'],
            ['Bimbingan Konseling', 'BK'],
            ['Muatan Lokal', 'MULOK'],
            ['Bahasa Jepang', 'BJ'],
            ['Bahasa Arab', 'BA'],
            ['Bahasa Mandarin', 'BM'],
            ['Matematika Peminatan', 'MATP'],
            ['Matematika Wajib', 'MATW'],
            ['Kewirausahaan', 'KWU'],
            ['Produktif Kejuruan', 'PK'],
        ];

        foreach ($subjects as [$name, $code]) {
            Subject::create([
                'name' => $name,
                'code' => $code,
            ]);
        }
    }
}

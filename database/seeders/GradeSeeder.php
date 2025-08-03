<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $grades = [
            ['level' => 1, 'name' => 'SD',   'alias' => 'Sekolah Dasar'],
            ['level' => 1, 'name' => 'MI',   'alias' => 'Madrasah Ibtidaiyah'],
            ['level' => 2, 'name' => 'SMP',  'alias' => 'Sekolah Menengah Pertama'],
            ['level' => 2, 'name' => 'MTs',  'alias' => 'Madrasah Tsanawiyah'],
            ['level' => 3, 'name' => 'SMA',  'alias' => 'Sekolah Menengah Atas'],
            ['level' => 3, 'name' => 'SMK',  'alias' => 'Sekolah Menengah Kejuruan'],
            ['level' => 3, 'name' => 'MA',   'alias' => 'Madrasah Aliyah'],
            ['level' => 4, 'name' => 'PT',   'alias' => 'Perguruan Tinggi'],
        ];

        foreach ($grades as $grade) {
            Grade::updateOrCreate(['name' => $grade['name']], $grade);
        }
    }
}

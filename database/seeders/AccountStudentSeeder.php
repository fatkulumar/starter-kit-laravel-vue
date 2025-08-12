<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::create([
            'name' => 'Student Umar',
            'email' => 'umar@gmail.com',
            'password' => Hash::make('test'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $student->assignRole('student');

        $student->profile()->create(
            collect(Profile::factory()->make()->getAttributes())->only(['photo'])->toArray()
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Fatkul Umar',
            'email' => 'fatkulumar@gmail.com',
            'password' => Hash::make('test'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $admin->assignRole('admin');
    }
}

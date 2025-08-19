<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'member']);
        Role::firstOrCreate(['name' => 'student']);

        $this->call([
            AccountAdminSeeder::class,
            AccountStudentSeeder::class,
            GradeSeeder::class,
            SubjectSeeder::class
        ]);

        \App\Models\User::factory()->count(10)->create();
        $events = \App\Models\Event::factory()->count(5)->create();

        $events->each(function ($event) {
            \App\Models\Tryout::factory()->count(5)->create([
                'event_id' => $event->id,
            ]);
        });

        \App\Models\Order::factory()->count(5)->create();
        \App\Models\Payment::factory()->count(5)->create();
        \App\Models\Subtest::factory()->count(5)->create();
        \App\Models\Question::factory()->count(5)->create();
        \App\Models\Purchase::factory()->count(5)->create();
        \App\Models\StartTryout::factory()->count(5)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

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
        \App\Models\User::factory()->count(10)->create();
        $events = \App\Models\Event::factory()->count(5)->create();

        $events->each(function ($event) {
            \App\Models\Tryout::factory()->count(2)->create([
                'event_id' => $event->id,
            ]);
        });

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AccountAdminSeeder::class,
        ]);
    }
}

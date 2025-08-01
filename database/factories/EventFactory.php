<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('+1 week', '+2 weeks');
        $endTime = (clone $startTime)->modify('+2 days');
        $registrationDeadline = (clone $startTime)->modify('-1 week');
        $preliminaryDate = (clone $startTime)->modify('+3 days');
        $finalDate = (clone $preliminaryDate)->modify('+1 week');

        $isOnline = $this->faker->boolean(); // true/false
        $linkZoom = $isOnline ? $this->faker->url() : null;
        $location = $isOnline ? null : $this->faker->city();

        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'banner' => $this->faker->optional()->imageUrl(800, 400, 'event', true, 'Banner'),

            'start_time' => $startTime,
            'end_time' => $endTime,

            'registration_deadline' => $registrationDeadline,
            'preliminary_date' => $preliminaryDate,
            'final_date' => $finalDate,

            'whatsapp_group_link' => $this->faker->optional()->url(),
            'guidebook_link' => $this->faker->optional()->url(),

            'location' => $location,
            'is_online' => $isOnline,
            'link_zoom' => $linkZoom,

            'quota' => $this->faker->optional()->numberBetween(100, 1000),
        ];
    }
}

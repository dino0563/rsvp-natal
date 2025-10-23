<?php

namespace Database\Factories;

use App\Enums\StatusTicket;
use App\Enums\WaStatus;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        $raw = '08' . $this->faker->unique()->numerify('#########');

        return [
            'name' => $this->faker->name(),
            'phone' => \App\Models\Registration::normalizePhone($raw),
            'age' => $this->faker->numberBetween(13, 25),
            'education_level' => $this->faker->randomElement(['SMP', 'SMA', 'Kuliah']),
            'school' => $this->faker->randomElement(['SMA 1', 'SMA 2', 'Universitas X']),
            'church' => $this->faker->randomElement(['GKT I', 'GKT II', 'GKT III']),
            'source' => $this->faker->randomElement(['IG', 'Teman', 'Komsel', 'Poster']),
            'status_ticket' => \App\Enums\StatusTicket::PENDING,
            'wa_last_status' => \App\Enums\WaStatus::QUEUED,
        ];
    }

    public function generated(): static
    {
        return $this->state(
            fn() => [
                'status_ticket' => StatusTicket::GENERATED,
            ],
        );
    }
}

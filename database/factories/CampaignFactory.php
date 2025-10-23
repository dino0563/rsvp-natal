<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'key'                 => $this->faker->randomElement(['ticket','T-1','H-3']),
            'scheduled_at'        => now()->addDay(),
            'throttle_per_second' => 5,
            'status'              => 'idle',
        ];
    }
}

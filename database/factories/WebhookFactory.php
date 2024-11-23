<?php

namespace Database\Factories;

use App\Models\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookFactory extends Factory
{
    protected $model = Webhook::class;

    public function definition()
    {
        return [
          'type' => $this->faker->randomElement(['subjectV2', 'projectV2']),
          'url' => $this->faker->url(),
        ];
    }
}


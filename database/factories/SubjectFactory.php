<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->email,
            'first_name' => $this->faker->word, 
            'last_name' => $this->faker->word,
        ];
    }
}

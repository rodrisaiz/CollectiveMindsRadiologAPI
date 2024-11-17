<?php

namespace Database\Factories;

use App\Models\SujectsInProjects;
use App\Models\Subject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class SujectsInProjectsFactory extends Factory
{
    protected $model = SujectsInProjects::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::inRandomOrder()->first()->id, 
            'project_id' => Project::inRandomOrder()->first()->id,
            'updated_at' => now(),
        ];
    }
}

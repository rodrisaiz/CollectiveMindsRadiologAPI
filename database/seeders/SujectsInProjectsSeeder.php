<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SujectsInProjects;

class SujectsInProjectsSeeder extends Seeder
{
    public function run(): void
    {
        SujectsInProjects::factory()->count(25)->create();
    }
}

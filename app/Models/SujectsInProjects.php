<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SujectsInProjects extends Model
{
    use HasFactory;

    protected $table = 'sujects_in_projects';

    protected $guarded = [];
}

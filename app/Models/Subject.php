<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Project;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $fillable = ['email', 'first_name', 'last_name'];
    
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'sujects_in_projects', 'subject_id', 'project_id')
        ->withTimestamps();

    }

}

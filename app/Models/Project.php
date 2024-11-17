<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Subject;



class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'sujects_in_projects', 'project_id', 'subject_id')
        ->withTimestamps();
    }
}

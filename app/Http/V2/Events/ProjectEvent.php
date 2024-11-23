<?php

namespace App\Http\V2\Events;

use App\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectEvent
{
    use Dispatchable, SerializesModels;

    public $project;
    public $action;

    public function __construct(Project $project, $action)
    {
        $this->project = $project;
        $this->action = $action;
    }
}

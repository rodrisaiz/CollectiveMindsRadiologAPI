<?php

namespace App\Http\V2\Events;

use App\Models\Subject;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubjectEvent
{
    use Dispatchable, SerializesModels;

    public $subject;
    public $action;

    public function __construct(Subject $subject, $action)
    {
        $this->subject = $subject;
        $this->action = $action;
    }
}

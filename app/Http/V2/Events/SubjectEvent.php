<?php

namespace App\Http\V2\Events;

use App\Models\Subject;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubjectEvent
{
    use Dispatchable, SerializesModels;

    public $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }
}

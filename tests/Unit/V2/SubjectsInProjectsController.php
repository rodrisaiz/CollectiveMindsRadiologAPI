<?php

namespace Tests\Unit\V2;


use Tests\TestCase;
use App\Models\Subject;
use App\Models\Project;
use App\Models\User;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\V1\SubjectsInProjectsController as V1SubjectsInProjectsController;



class SubjectsInProjectsController extends V1SubjectsInProjectsController
{
    use RefreshDatabase;

    //Preexisting V1 tests
    protected $baseEndpoint = '/api/v2/enroll/';

    protected function getEndpoint(string $path = ''): string
    {
        return $this->baseEndpoint . $path;
    }

    //New V2 tests 
    public function test_webhook_subjects_in_projects_enroll()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.subject', $webhookUrl);
        
        \Http::fake();

        $subject = Subject::factory()->create();
        $action = "subject test created";

        Webhook::factory()->create([
                'type' => 'subjectV2',
                'url' => 'https://example.com/webhook',
        ]);


        event(new \App\V2\Events\SubjectEvent($subject, $action));

        \Http::assertSent(function ($request) use ($webhookUrl, $subject, $action) {
            return $request->url() === $webhookUrl &&
                   $request['action'] === $action;
                   $request['id'] === $subject->id;
        });
    }
}

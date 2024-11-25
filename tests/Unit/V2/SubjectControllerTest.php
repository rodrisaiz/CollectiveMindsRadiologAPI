<?php

namespace Tests\Unit\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Subject;
use App\Models\Project;
use App\Models\User;
use App\Models\Webhook;
use Tests\Unit\V1\SubjectControllerTest as V1SubjectControllerTest;


class SubjectControllerTest extends V1SubjectControllerTest
{
    use RefreshDatabase;
    
    //Preexisting V1 tests
    protected $baseEndpoint = '/api/v2/subject/';

    protected function getEndpoint(string $path = ''): string
    {
        return $this->baseEndpoint . $path;
    }

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }


    //New V2 tests 
    public function test_webhook_subject_created()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.subject', $webhookUrl);
        
        \Http::fake();

        
        $subject = Subject::factory()->create();
        $project = Project::factory()->create();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(). $subject->id . '/' . $project->id);

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


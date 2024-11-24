<?php

namespace Tests\Unit\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Subject;
use App\Models\Webhook;
use Tests\Unit\V1\SubjectControllerTest as V1SubjectControllerTest;


class SubjectControllerTest extends V1SubjectControllerTest
{
    //Preexisting V1 tests
    protected $baseEndpoint = '/api/v2/subject/';

    protected function getEndpoint(string $path = ''): string
    {
        return $this->baseEndpoint . $path;
    }

    //New V2 tests 
    use RefreshDatabase;

    public function test_webhook_subject_created()
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

    public function test_webhook_subject_updated()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.subject', $webhookUrl);
        
        \Http::fake();

        $subject = Subject::factory()->create([
            'first_name' => 'Original Name',
        ]);

        $subject->update([
            'first_name' => 'Updated Name',
        ]);

        Webhook::factory()->create([
            'type' => 'subjectV2',
            'url' => 'https://example.com/webhook',
        ]);

        $action = "subject test updated";
        event(new \App\V2\Events\SubjectEvent($subject, $action));

        \Http::assertSent(function ($request) use ($webhookUrl, $subject, $action) {
            return $request->url() === $webhookUrl &&
                   $request['action'] === $action;
                   $request['id'] === $subject->id;
        });
    }

    public function test_webhook_subject_deleted()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.subject', $webhookUrl);
        
        \Http::fake();

        $subject = Subject::factory()->create([
            'first_name' => 'Original Name',
        ]);

        Webhook::factory()->create([
            'type' => 'subjectV2',
            'url' => 'https://example.com/webhook',
        ]);

        $action = "subject test deleted";
        event(new \App\V2\Events\SubjectEvent($subject, $action));
        $subject->delete();

        \Http::assertSent(function ($request) use ($webhookUrl, $subject, $action) {
            return $request->url() === $webhookUrl &&
                   $request['action'] === $action;
                   $request['id'] === $subject->id;
        });
    }

        
}


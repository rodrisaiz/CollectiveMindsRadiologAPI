<?php

namespace Tests\Unit\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Subject;

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

        // Configura una fake para simular la respuesta HTTP
        \Http::fake();

        $subject = Subject::factory()->create();

        event(new \App\Events\SubjectEvent($subject));

        \Http::assertSent(function ($request) use ($webhookUrl, $subject) {
            return $request->url() === $webhookUrl &&
                   $request['id'] === $subject->id;
        });
    }


        
}


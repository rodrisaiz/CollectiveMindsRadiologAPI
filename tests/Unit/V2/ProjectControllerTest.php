<?php

namespace Tests\Unit\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\Webhook;
use Tests\Unit\V1\ProjectControllerTest as V1ProjectControllerTest;


class ProjectControllerTest extends V1ProjectControllerTest
{
    use RefreshDatabase;
    
    //Preexisting V1 tests
    protected $baseEndpoint = '/api/v2/project/';

    protected function getEndpoint(string $path = ''): string
    {
        return $this->baseEndpoint . $path;
    }

    //New V2 tests 
    public function test_webhook_project_created()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.project', $webhookUrl);
        
        \Http::fake();

        $project = Project::factory()->create();
        $action = "project test created";

        Webhook::factory()->create([
                'type' => 'projectV2',
                'url' => 'https://example.com/webhook',
        ]);


        event(new \App\V2\Events\ProjectEvent($project, $action));

        \Http::assertSent(function ($request) use ($webhookUrl, $project, $action) {
            return $request->url() === $webhookUrl &&
                   $request['action'] === $action;
                   $request['id'] === $project->id;
        });
    }

    public function test_webhook_project_updated()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.project', $webhookUrl);
        
        \Http::fake();

        $project = Project::factory()->create([
            'description' => 'Original Name',
        ]);

        $project->update([
            'description' => 'Updated Name',
        ]);

        Webhook::factory()->create([
            'type' => 'projectV2',
            'url' => 'https://example.com/webhook',
        ]);

        $action = "project test updated";
        event(new \App\V2\Events\ProjectEvent($project, $action));

        \Http::assertSent(function ($request) use ($webhookUrl, $project, $action) {
            return $request->url() === $webhookUrl &&
                   $request['action'] === $action;
                   $request['id'] === $project->id;
        });
    }

    public function test_webhook_project_deleted()
    {
        $webhookUrl = 'https://example.com/webhook';
        \Config::set('services.webhook.project', $webhookUrl);
        
        \Http::fake();

        $project = Project::factory()->create([
            'description' => 'Original Name',
        ]);

        Webhook::factory()->create([
            'type' => 'projectV2',
            'url' => 'https://example.com/webhook',
        ]);

        $action = "project test deleted";
        event(new \App\V2\Events\ProjectEvent($project, $action));
        $project->delete();

        \Http::assertSent(function ($request) use ($webhookUrl, $project, $action) {
            return $request->url() === $webhookUrl &&
                   $request['action'] === $action;
                   $request['id'] === $project->id;
        });
    }

        
}


<?php

namespace Tests\Unit\V3;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Webhook;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    protected $baseEndpoints = [
        '/api/v3/webhooks/subject/',
        '/api/v3/webhooks/project/',
    ];

    protected function getEndpoint(string $path = '', string $base = '/api/v1/subject/'): string
    {
        return $base . $path;
    }

    //Test for assigning url to webhooks
    public function test_create_a_webhook_with_a_url()
    {
        foreach ($this->baseEndpoints as $base) {
            if ($base == "/api/v3/webhooks/subject/") {
                $expectedType = 'subjectV3';
            } elseif ($base == "/api/v3/webhooks/project/") {
                $expectedType = 'projectV3';
            }

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->postJson($base, [
                'url' => 'https://example.com/webhook-handler',
            ]);

            $response->assertStatus(200);
            $this->assertDatabaseHas('webhooks', [
                'type' => $expectedType,
                'url' => 'https://example.com/webhook-handler',
            ]);
        }
    }


    public function test_create_a_webhook_with_invalid_url()
    {
        foreach ($this->baseEndpoints as $base) {
            if ($base == "/api/v3/webhooks/subject/") {
                $expectedType = 'subjectV3';
            } elseif ($base == "/api/v3/webhooks/project/") {
                $expectedType = 'projectV3';
            }

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->postJson($base, [
                'url' => 1234
            ]);

            $response->assertStatus(422);
        }
    }


    public function test_update_a_webhook_url()
    {
        foreach ($this->baseEndpoints as $base) {
            if ($base == "/api/v3/webhooks/subject/") {
                $expectedType = 'subjectV3';
            } elseif ($base == "/api/v3/webhooks/project/") {
                $expectedType = 'projectV3';
            }

            $webhook = Webhook::factory()->create([
                'type' => $expectedType ,
                'url' => 'https://example.com/original-handler',
            ]);
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                ])->putJson($base.$webhook->id, [
                    'url' => 'https://example2.com/updated-handler'
                ]);
                
            $response->assertStatus(201);

            $response->assertJson([
                'data' => [
                    'url' => 'https://example2.com/updated-handler',
                    'type' =>  $expectedType,
                ],
            ]);
        }
    }   
}

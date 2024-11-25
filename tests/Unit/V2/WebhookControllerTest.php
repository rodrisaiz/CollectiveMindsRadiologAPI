<?php

namespace Tests\Unit\v2;

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
        '/api/v2/webhooks/subject/',
        '/api/v2/webhooks/project/',
    ];


    //Test for assigning url to webhooks
    public function test_create_a_webhook_with_a_url()
    {
        foreach ($this->baseEndpoints as $base) {
            if ($base == "/api/v2/webhooks/subject/") {
                $expectedType = 'subjectV2';
            } elseif ($base == "/api/v2/webhooks/project/") {
                $expectedType = 'projectV2';
            }

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->postJson($base, [
                'url' => 'https://example.com/webhook-handler',
            ]);

            $response->assertStatus(201);
            $this->assertDatabaseHas('webhooks', [
                'type' => $expectedType,
                'url' => 'https://example.com/webhook-handler',
            ]);
        }
    }


    public function test_create_a_webhook_with_invalid_url()
    {
        foreach ($this->baseEndpoints as $base) {
            if ($base == "/api/v2/webhooks/subject/") {
                $expectedType = 'subjectV2';
            } elseif ($base == "/api/v2/webhooks/project/") {
                $expectedType = 'projectV2';
            }

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->postJson($base, [
                'url' => 'invalid-url'
            ]);

            $response->assertStatus(422);
        }
    }


    public function test_update_a_webhook_url()
    {
        foreach ($this->baseEndpoints as $base) {
            if ($base == "/api/v2/webhooks/subject/") {
                $expectedType = 'subjectV2';
            } elseif ($base == "/api/v2/webhooks/project/") {
                $expectedType = 'projectV2';
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
                
            $response->assertStatus(200);

            $response->assertJson([
                'data' => [
                    'url' => 'https://example2.com/updated-handler',
                    'type' =>  $expectedType,
                ],
            ]);
        }
    }   
}

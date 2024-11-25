<?php
namespace Tests\Unit\V2;


use App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\Unit\V1\AuthenticationTest as V1AuthenticationTest;

class AuthenticationTest extends  V1AuthenticationTest
{
    use RefreshDatabase;

    protected $endpoints = [
        'project' => '/api/v2/project/',
        'subject' => '/api/v2/subject/',
        'enroll' => '/api/v2/enroll/',
        'webhooksubject' => '/api/v2/webhooks/subject/',
        'webhooksproject' => '/api/v2/webhooks/sproject/',
    ];

    protected $baseEndpoints = [
        '/api/v2/webhooks/subject/',
        '/api/v2/webhooks/project/',
    ];


    protected function getEndpoint(string $key): string
    {
        if (!isset($this->endpoints[$key])) {
            throw new \InvalidArgumentException("Endpoint '$key' not defined.");
        }
        return $this->endpoints[$key] ;
    }

  //Test for assigning url to webhooks
  public function test_create_a_webhook_with_a_url()
  {
    foreach ($this->baseEndpoints as $base) {
        if ($base == $this->getEndpoint('webhooksubject')) {
            $expectedType = 'subjectV2';
        } elseif ($base ==$this->getEndpoint('webhooksproject')) {
            $expectedType = 'projectV2';
        }

        $response = $this->postJson($base, [
            'url' => 'https://example.com/webhook-handler',
        ]);

        $response->assertStatus(401);
      }
  }


  public function test_create_a_webhook_with_invalid_url()
  {
    foreach ($this->baseEndpoints as $base) {
        if ($base == $this->getEndpoint('webhooksubject')) {
            $expectedType = 'subjectV2';
        } elseif ($base ==$this->getEndpoint('webhooksproject')) {
            $expectedType = 'projectV2';
        }

        $response = $this->postJson($base, [
            'url' => 'invalid-url'
        ]);

        $response->assertStatus(401);
      }
  }

  public function test_update_a_webhook_url()
  {
    foreach ($this->baseEndpoints as $base) {
        if ($base == $this->getEndpoint('webhooksubject')) {
            $expectedType = 'subjectV2';
        } elseif ($base ==$this->getEndpoint('webhooksproject')) {
            $expectedType = 'projectV2';
        }

        $webhook = Webhook::factory()->create([
            'type' => $expectedType . '_' . uniqid(),
            'url' => 'https://example4.com/original-handler',
        ]);
        Log::info(['TEST =' => $webhook->id ]);
        $response = $this->putJson($base.$webhook->id, [
            'url' => 'https://example2.com/updated-handler'
        ]);
              
        $response->assertStatus(401);
      }
  }   
}

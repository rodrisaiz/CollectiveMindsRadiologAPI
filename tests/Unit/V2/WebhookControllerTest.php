<?php

namespace Tests\Unit\V2;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Webhook;

class WebhookControllerTest extends TestCase
{
        //Test for assigning url to webhooks
        public function test_create_a_webhook_with_a_url()
        {
            $response = $this->postJson('/api/webhooks', [
                'name' => 'Test Webhook',
                'url' => 'https://example.com/webhook-handler',
            ]);
    
            $response->assertStatus(201);
            $this->assertDatabaseHas('webhooks', [
                'name' => 'Test Webhook',
                'url' => 'https://example.com/webhook-handler',
            ]);
        }
    
        public function it_validates_the_url_field()
        {
            $response = $this->postJson('/api/webhooks', [
                'name' => 'Test Webhook',
                'url' => 'invalid-url',
            ]);
    
            $response->assertStatus(422);
            $response->assertJsonValidationErrors('url');
        }
    
        public function test_update_a_webhook_url()
        {
            $webhook = Webhook::factory()->create([
                'name' => 'Test Webhook',
                'url' => 'https://example.com/original-handler',
            ]);
    
            $response = $this->putJson("/api/webhooks/{$webhook->id}", [
                'url' => 'https://example.com/updated-handler',
            ]);
    
            $response->assertStatus(200);
            $this->assertDatabaseHas('webhooks', [
                'id' => $webhook->id,
                'url' => 'https://example.com/updated-handler',
            ]);
        }
    
        public function test_retrieve_a_webhook_with_its_url()
        {
            $webhook = Webhook::factory()->create([
                'name' => 'Test Webhook',
                'url' => 'https://example.com/handler',
            ]);
    
            $response = $this->getJson("/api/webhooks/{$webhook->id}");
    
            $response->assertStatus(200);
            $response->assertJson([
                'id' => $webhook->id,
                'name' => 'Test Webhook',
                'url' => 'https://example.com/handler',
            ]);
        }
   
}

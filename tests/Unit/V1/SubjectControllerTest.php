<?php

namespace Tests\Unit\V1;

use Tests\TestCase;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;



class SubjectControllerTest extends TestCase
{ 
   use RefreshDatabase;

    protected $baseEndpoint = '/api/v1/subject/';

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

    //Index test
    public function test_subject_index_returns_successfully()
    {
        Subject::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint());

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'email', 'first_name', 'last_name', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    //Store test
    public function test_subject_store_returns_success()
    {
        $data = [
            'email' => 'test125@example.com',
            'first_name' => 'Test',
            'last_name' => 'User'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(), $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'email' => 'test125@example.com',
                         'first_name' => 'Test',
                         'last_name' => 'User',
                     ]
                 ]);

        $this->assertDatabaseHas('subjects', $data);
    }


    public function test_subject_store_returns_validation_error()
    {
        $data = [
            //Forcing to fail without email | required 
            'first_name' => 'Test',
            'last_name' => 'User'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(), $data);

        $response->assertStatus(422)
                 ->assertJson([
                         'error' => "Validation Error",
                         'messages' => [
                            'email' => ['The email field is required.']
                         ], 
                 ]);
    }

    //Show test
    public function test_subject_show_returns_successfully()
    {
        $subject = Subject::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint(). $subject->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $subject->id,
                         'email' => $subject->email,
                         'first_name' => $subject->first_name,
                         'last_name' => $subject->last_name
                     ]
                 ]);
    }

    public function test_subject_show_returns_validation_error()
    {
        $subject = Subject::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint(). 1000);

        $response->assertStatus(404)
                 ->assertJson([
                        'error' => "Subject not found",
                  ]);
    }

    //Show by email test
    public function test_subject_show_by_email__returns_successfully()
    {
        $subject = Subject::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint().'email/'. $subject->email);

        $response->assertStatus(200)
                 ->assertJson([
                    'data' => [
                        [
                            'id' => $subject->id,
                            'email' => $subject->email,
                            'first_name' => $subject->first_name,
                            'last_name' => $subject->last_name,
                            'created_at' => $subject->created_at->toISOString(),
                            'updated_at' => $subject->updated_at->toISOString(),
                        ]
                    ]
                 ]);
    }

    public function test_subject_show_by_email_returns_validation_error()
    {
        $subject = Subject::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint().'email/'. "rodrisaiz@icloud.com");

        $response->assertStatus(404)
                 ->assertJson([
                        'error' => "Subject not found",
                  ]);
    }


    //Update test 
    public function test_subject_update_returns_successfully()
    {
        $subject = Subject::factory()->create([
            'first_name' => 'Old Name'
        ]);
        
        $data = [
            'email' => $subject->email,
            'first_name' => 'New Name',
            'last_name' => $subject->last_name
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson($this->getEndpoint() . $subject->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'first_name' => 'New Name'
                     ]
                 ]);

        $this->assertDatabaseHas('subjects', ['id' => $subject->id, 'first_name' => 'New Name']);
    }
     
    public function test_subject_update_returns_validation_error()
    {
        $subject = Subject::factory()->create([
            'first_name' => 'Old Name'
        ]);
        
        $data = [
            'email' => 'test1000234',
            'first_name' => 'New Name',
            'last_name' => $subject->last_name
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson($this->getEndpoint() . $subject->id, $data);

        $response->assertStatus(422)
                 ->assertJson([
                    "error" => "Validation Error",
                    "messages"=> [
                        "email" => [
                            "The email field must be a valid email address."
                        ]
                ]]);
    }

    //Destroy Test
    public function test_subject_destroy_returns_successfully()
    {
        $subject = Subject::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson($this->getEndpoint(). $subject->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Subject deleted successfully'
                 ]);
    }

    public function test_subject_delete_returns_validation_error()
    {
        $subject = Subject::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson($this->getEndpoint(). 1000);

        $response->assertStatus(404)
                 ->assertJson([
                        'error' => 'Subject not found'
                  ]);
    }


}

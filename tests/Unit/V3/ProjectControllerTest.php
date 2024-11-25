<?php

namespace Tests\Unit\V3;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;



class ProjectControllerTest extends TestCase
{
    protected $baseEndpoint = '/api/v3/project/';

    protected function getEndpoint(string $path = ''): string
    {
        return $this->baseEndpoint . $path;
    }

   use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    //Index test
    public function test_project_index_returns_successfully()
    {
        Project::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint());

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'description', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    //Store test
    public function test_project_store_returns_success()
    {
        $data = [
            'name' => 'test125',
            'description' => 'Test',
       ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(), $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'name' => 'test125',
                         'description' => 'Test',
                     ]
                 ]);

        $this->assertDatabaseHas('projects', $data);
    }


    public function test_project_store_returns_validation_error()
    {
        $data = [
            //Forcing to fail without name | required 
            'description' => 'Test',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(), $data);

        $response->assertStatus(422)
                 ->assertJson([
                         'error' => "Validation Error",
                         'messages' => [
                            'name' => ['The name field is required.']
                         ], 
                 ]);
    }

    //Show test
    public function test_project_show_returns_successfully()
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint(). $project->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $project->id,
                         'name' => $project->name,
                         'description' => $project->description,
                     ]
                 ]);
    }

    public function test_project_show_returns_validation_error()
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint(). 1000);

        $response->assertStatus(404)
                 ->assertJson([
                        'error' => 'Project not found',
                  ]);
    }

    //Show by name test
    public function test_project_show_by_name__returns_successfully()
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint().'name/'. $project->name);

        $response->assertStatus(200)
                 ->assertJson([
                    'data' => [
                        [
                            'id' => $project->id,
                            'name' => $project->name,
                            'description' => $project->description,
                            'created_at' => $project->created_at->toISOString(),
                            'updated_at' => $project->updated_at->toISOString(),
                        ]
                    ]
                 ]);
    }

    public function test_project_show_by_name_returns_validation_error()
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson($this->getEndpoint().'name/'. "rodrisaiz");

        $response->assertStatus(404)
                 ->assertJson([
                        'error' => 'Project not found',
                  ]);
    }


    //Update test 
    public function test_project_update_returns_successfully()
    {
        $project = Project::factory()->create([
            'name' => 'existing',
            'description' => 'Old Name',
        ]);

        $data = [
            'name' => 'new',
            'description' => 'New Name',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson($this->getEndpoint() . $project->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                        'description' => 'New Name'
                     ]
                 ]);

    }
     
    public function test_project_update_returns_validation_error()
    {
        $project = Project::factory()->create([
            'description' => 'Old Name'
        ]);
        
        $data = [
            'name' => 123,
            'description' => 'New Name',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson($this->getEndpoint() . $project->id, $data);

        $response->assertStatus(422)
                 ->assertJson([
                    "error"=> "Validation Error",
                    "messages" => [
                    "name"=> [
                            "The name field must be a string."
                        ]

                ]]);
    }
}

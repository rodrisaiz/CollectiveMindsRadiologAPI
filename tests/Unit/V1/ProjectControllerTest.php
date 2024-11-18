<?php
namespace Tests\Unit\V1;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;



class ProjectControllerTest extends TestCase
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

     //Index test
     public function test_projects_index_returns_successfully()
     {
         Project::factory()->count(3)->create();
 
         $response = $this->withHeaders([
             'Authorization' => 'Bearer ' . $this->token,
         ])->getJson('/api/v1/project');
 
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
            'name' => 'test15',
            'description' => 'Lorem Ipsum es simplemente el texto de relleno',
        ];


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/project', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                        'name' => 'test15',
                        'description' => 'Lorem Ipsum es simplemente el texto de relleno',
                     ]
                 ]);

        $this->assertDatabaseHas('projects', $data);
    }


    public function test_projectct_store_returns_validation_error()
    {
        $data = [
            //Forcing to fail without name | required 
            'description' => 'Lorem Ipsum es simplemente el texto de relleno',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/project', $data);

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
        ])->getJson('/api/v1/project/'. $project->id);

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
        $subject = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/project/'. 1000);

        $response->assertStatus(404)
                 ->assertJson([
                        'error' => "Project not found",
                  ]);
    }

     //Show by name test 
     public function test_project_show_by_name__returns_successfully()
     {
         $project = Project::factory()->create();
 
         $response = $this->withHeaders([
             'Authorization' => 'Bearer ' . $this->token,
         ])->getJson('/api/v1/project/name/'. $project->name);
 
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
         ])->getJson('/api/v1/project/name/'. "test project 25");
 
         $response->assertStatus(404)
                  ->assertJson([
                         'error' => "Project not found",
                   ]);
     }
 
    //Update test 
    public function test_project_update_returns_successfully()
    {
        $project = Project::factory()->create([
              'name' => 'Old Name'
        ]);
        
        $data = [
            'name' => 'New Name',
            'description' => $project->description,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/project/' . $project->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'name' => 'New Name',
                         'description' => $project->description,
                         'created_at' => $project->created_at->toISOString(),
                         'updated_at' => $project->updated_at->toISOString(),
                     ]
                 ]);

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'New Name']);
    }
     
    public function test_project_update_returns_validation_error()
    {
        $project = Project::factory()->create([
            'name' => 'Old Name'
        ]);
        
        $data = [
            'name' => 12345,
            'description' => $project->description
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/v1/project/' . $project->id, $data);

        $response->assertStatus(422)
                 ->assertJson([
                    "error" => "Validation Error",
                    "messages"=> [
                        "name" => [
                            "The name field must be a string."
                        ]
                ]]);
    }
    

}

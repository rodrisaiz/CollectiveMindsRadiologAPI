<?php

namespace Tests\Unit\V1;


use Tests\TestCase;
use App\Models\Subject;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SubjectsInProjectsController extends TestCase
{
    protected $baseEndpoint = '/api/v1/enroll/';

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

    //Enroll test
    public function test_subject_enroll_in_project_returns_successfully()
    {
        $subject = Subject::factory()->create();
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(). $subject->id . '/' . $project->id);

        $response->assertStatus(200)
                    ->assertJsonStructure([
                        'message',
                        'subject' => [
                            'id',
                            'email',
                            'first_name',
                            'last_name',
                            'created_at',
                            'updated_at',
                            'projects' => [
                                [
                                    'id',
                                    'name',
                                    'description',
                                    'created_at',
                                    'updated_at',
                                    'pivot' => [
                                        'subject_id',
                                        'project_id',
                                        'created_at',
                                        'updated_at',
                                    ],
                                ],
                            ],
                        ],
                    ]);

        $this->assertDatabaseHas('subjects');
    }

    public function test_subject_enroll_in_project_returns_authentication_error()
    {
        $subject = Subject::factory()->create();
       

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson($this->getEndpoint(). $subject->id . '/4000');

        $response->assertStatus(500)
        ->assertJson([
                'error' => "Error creating the assigment",
                'message' => "No query results for model [App\\Models\\Project] 4000", 
        ]);
    }
}

<?php
namespace Tests\Unit\V1;

use Tests\TestCase;
use App\Models\Project;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

     //Index project test
     public function test_projects_index_returns_authentication_error()
     {
         Project::factory()->count(3)->create();
 
         $response = $this->getJson('/api/v1/project');
 
         $response->assertStatus(401);
     }


    //Store project test
    public function test_project_store_returns_authentication_error()
    {
        $data = [
            'name' => 'test15',
            'description' => 'Lorem Ipsum es simplemente el texto de relleno',
        ];

        $response = $this->postJson('/api/v1/project', $data);

        $response->assertStatus(401);

    }


    //Show project test
    public function test_project_show_returns_authentication_error()
    {
        $project = Project::factory()->create();

        $response = $this->getJson('/api/v1/project/'. $project->id);

        $response->assertStatus(401);
    }


     //Show project by name test
     public function test_project_show_by_name_returns_authentication_error()
     {
         $project = Project::factory()->create();
 
         $response = $this->getJson('/api/v1/project/name/'. $project->name);
 
         $response->assertStatus(401);
     }
 
   
    //Update project test 
    public function test_project_update_returns_authentication_error()
    {
        $project = Project::factory()->create([
            'name' => 'Old Name'
        ]);
        
        $data = [
            'name' => 'New Name',
            'description' => $project->description,
        ];

        $response = $this->putJson('/api/v1/project/' . $project->id, $data);

        $response->assertStatus(401);
    }




    //Index subject test
    public function test_subject_index_returns_authentication_error()
    {
        Subject::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/subject');

        $response->assertStatus(401);
    }

   
    //Store subject test
    public function test_subject_store_returns_authentication_error()
    {
        $data = [
            'email' => 'test125@example.com',
            'first_name' => 'Test',
            'last_name' => 'User'
        ];

        $response = $this->postJson('/api/v1/subject', $data);

        $response->assertStatus(401);

    }

  

    //Show subject test
    public function test_subject_show_returns_authentication_error()
    {
        $subject = Subject::factory()->create();

        $response = $this->getJson('/api/v1/subject/'. $subject->id);

        $response->assertStatus(401);
    }




    //Show subject by email test
    public function test_subject_show_by_email_returns_authentication_error()
    {
        $subject = Subject::factory()->create();

        $response = $this->getJson('/api/v1/subject/email/'. $subject->email);

        $response->assertStatus(401);
    }



    //Update subject test 
    public function test_subject_update_returns_authentication_error()
    {
        $subject = Subject::factory()->create([
            'first_name' => 'Old Name'
        ]);
        
        $data = [
            'email' => $subject->email,
            'first_name' => 'New Name',
            'last_name' => $subject->last_name
        ];

        $response = $this->putJson('/api/v1/subject/' . $subject->id, $data);

        $response->assertStatus(401);
    }


    //Destroy subject test
    public function test_subject_destroy_returns_authentication_error()
    {
        $subject = Subject::factory()->create();

        $response = $this->deleteJson('/api/v1/subject/'. $subject->id);

        $response->assertStatus(401);
    }

    //Enroll test
    public function test_subject_enroll_in_project_returns_authentication_error()
    {
        $subject = Subject::factory()->create();
        $project = Project::factory()->create();

        $response = $this->postJson('/api/v1/enroll/'. $subject->id . '/' . $project->id);

        $response->assertStatus(401);
    }

}

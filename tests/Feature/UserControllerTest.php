<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_creation_succes(){
        $response = $this->post('/users' , ['name' => 'Guillermo Garrido' ,
                                        'email' => 'g.garridoportes@edu.gva.com']);
        $response->assertSuccessful();
    }

    public function test_user_show_view_has_user()
    {
        $response = $this->get('users/show');
        $response->assertViewHas('user');
    }

    public function test_user_index_view_has_users()
    {
        $response = $this->get('users/index');
        $response->assertViewHas('users');
    }

}

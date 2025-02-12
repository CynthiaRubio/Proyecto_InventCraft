<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserStat;
use App\Models\Zone;

use Illuminate\Support\Facades\Artisan;

class UserControllerTest extends TestCase
{
    /** @test */
    public function test_ranking(): void
    {
        /* Rellenamos la base de datos */
        Artisan::call('migrate:refresh --seed');
        
        $user = User::create([
            'email' => 'user@prueba.com',
            'level' => 15,
            'experience' => 250,
        ]);

        $this->actingAs($user);

        $user1 = User::create([
            'email' => 'user1@prueba.com',
            'level' => 10, 
            'experience' => 100
        ]);
        $user2 = User::create([
            'email' => 'user2@prueba.com',
            'level' => 5, 
            'experience' => 50]);

        $response = $this->get(route('users.ranking'));

        $response->assertStatus(200);
        $response->assertViewIs('users.ranking');
        $response->assertViewHas('users', function ($users) use ($user1, $user2) {
            return $users->contains($user1) && $users->contains($user2);
        });
    }

    /** @test */
    public function test_change_avatar()
    {
        $user = User::create([
            'level' => 15,
            'experience' => 250,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('users.avatar.update', $user->_id), [
            'avatar' => 3
        ]);

        $response->assertRedirect(route('users.show', $user->_id));
        $this->assertEquals(3, $user->fresh()->avatar);
    }

    /** @test */
    public function test_unauthenticated_users_to_assign_points()
    {
        $response = $this->post(route('users.addStats'), [
            'user_id' => 'fake_id',
            'stats' => [1 => 10, 2 => 5]
        ]);

        $response->assertRedirect(route('login'));
    }


    /** @test */
    public function test_assigns_points_correctly()
    {
        $user = User::create(['unasigned_points' => 20]);
        $this->actingAs($user);

        $stat1 = UserStat::create(['user_id' => $user->_id, 'stat_id' => 1, 'value' => 5]);
        $stat2 = UserStat::create(['user_id' => $user->_id, 'stat_id' => 2, 'value' => 3]);

        $response = $this->post(route('users.addStats'), [
            'user_id' => $user->_id,
            'stats' => [1 => 10, 2 => 5]
        ]);

        $response->assertRedirect(route('users.show'));
        $this->assertEquals(15, $stat1->fresh()->value);
        $this->assertEquals(8, $stat2->fresh()->value);
        $this->assertEquals(5, $user->fresh()->unasigned_points);
    }

}
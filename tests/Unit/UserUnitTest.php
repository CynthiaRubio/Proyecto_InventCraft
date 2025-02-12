<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_user_id_is_valid_mongo_objectid()
    {
        $user = User::create();
        $this->assertNotNull($user->id);
        $this->assertIsString($user->id);
        $this->assertMatchesRegularExpression('/^[0-9a-fA-F]{24}$/', $user->id);
    }

    public function test_user_name_is_not_empty()
    {
        $user = new User(['name' => 'Guillermo Garrido']);
        $this->assertFalse(empty($user->name));
    }

    public function test_user_email_equality()
    {
        $user = new User(['email' => 'g.garridoportes@edu.gva.com']);
        $this->assertEquals('g.garridoportes@edu.gva.com', $user->email);
    }

    public function test_user_instance_of_user_model()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }


}

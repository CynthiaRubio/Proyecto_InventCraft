<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'level' => rand(0,10),
            'experience' => rand(0,1000),
            'unasigned_points' => 15,
            'avatar' => null,
        ];

        // $action_type = ActionType::where('name', 'Mover')->first();
        // $zones = Zone::all();
        // $zone = $zones->random();

        // Action::create([
        //     'user_id' => $user->_id,
        //     'action_type_id' => $action_type->_id,
        //     'actionable_id' => $zone->_id,
        //     'actionable_type' => Zone::class,
        //     'time' => now(), // now()->addSeconds(rand(60, 14400)), 
        //     'finished' => true,
        //     'notificacion' => true,
        // ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

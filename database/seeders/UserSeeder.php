<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Zone;
use App\Models\Action;
use App\Models\ActionType;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Cynthia';
        $user->email = 'cynrusan@gmail.com';
        $user->password = bcrypt('juego_servidor');
        $user->remember_token = Str::random(10);
        $user->level = 1;
        $user->experience = 0;
        $user->unasigned_points = 15;
        $user->avatar = 1;
        $user->save();

        $action_type = ActionType::where('name', 'Mover')->first();
        $zones = Zone::all();
        $zone = $zones->random();

        Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type->_id,
            'actionable_id' => $zone->_id,
            'actionable_type' => Zone::class,
            'time' => now(), // now()->addSeconds(rand(60, 14400)), 
            'finished' => true,
            'notificacion' => true,
            'updated' => true,
        ]);
    }
}

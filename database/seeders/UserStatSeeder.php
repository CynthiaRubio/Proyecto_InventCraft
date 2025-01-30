<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Stat;
use App\Models\UserStat;

class UserStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Get all users
        $users = User::all();
        $stats = Stat::all();

        foreach ($users as $key => $user) {
            foreach ($stats as $stat) {

                $userStat = UserStat::create([
                    'stat_id' => $stat->id,
                    'user_id' => $user->id,
                    'value' => 0,
                ]);
            
            }
        }
    }
}

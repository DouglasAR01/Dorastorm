<?php

namespace Database\Seeders;

use App\Models\Secret;
use App\Models\User;
use Illuminate\Database\Seeder;

class SecretsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user){
            $secrets = Secret::factory()->count(random_int(2,8))->make([
                'user_id' => $user->id,
            ]);
            $user->secrets()->saveMany($secrets);
        });
    }
}

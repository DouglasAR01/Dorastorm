<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $roles = Role::all()->pluck('id');
        $random = [true,false];
        foreach ($users as $user) {
            if(array_rand($random)){
                $user->roles()->attach($roles->random());
            }
        }
    }
}

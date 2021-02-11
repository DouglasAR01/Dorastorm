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
        $roles = Role::where('name','!=','STANDARD')->get()->pluck('id');
        $standard_id = Role::where('name','STANDARD')->first()->id;
        foreach ($users as $user) {
            $user->roles()->attach($standard_id);
            $user->roles()->attach($roles->random());
        }
    }
}

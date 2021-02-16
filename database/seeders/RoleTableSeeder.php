<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::unguard();
        
        Role::create([
            'name' => 'ADMIN',
            'description' => 'Administrator',
            'hierarchy' => 0,

            'create_users' => true,
            'read_users' => true,
            'update_users' => true,
            'delete_users' => true,

            'create_post' => true,
            'update_elses_post' => true,
            'delete_elses_post' => true,

            'update_elses_comments' => true,
            'delete_elses_comments' => true,

            'create_roles' => true,
            'read_roles' => true,
            'update_roles' => true,
            'delete_roles' => true,
        ]);
        Role::create([
            'name' => 'EDITOR',
            'hierarchy' => 1,

            'create_post' => true,
        ]);

        Role::reguard();
    }
}

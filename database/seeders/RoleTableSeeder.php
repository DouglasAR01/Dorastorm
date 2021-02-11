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
            'create' => true,
            'read' => true,
            'update' => true,
            'delete' => true
        ]);
        Role::create([
            'name' => 'EDITOR',
            'create' => true,
            'read' => true,
            'update' => true,
            'delete' => true
        ]);
        Role::create([
            'name' => 'STANDARD',
            'read' => true,
        ]);

        Role::reguard();
    }
}

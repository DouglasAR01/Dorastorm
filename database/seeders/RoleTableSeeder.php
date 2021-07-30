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
        $baseData = [
            'name' => 'ADMIN',
            'description' => 'Administrator',
            'hierarchy' => 0,
        ];

        $permissions = array_merge(config('roles.permissions.core'), config('roles.permissions.extended'));
        $extendedData = [];
        foreach ($permissions as $permission){
            $extendedData[$permission] = true;
        }
        $baseData = array_merge($baseData, $extendedData);

        Role::create($baseData);
        Role::create([
            'name' => 'EDITOR',
            'hierarchy' => 1,

            'create_posts' => true,
        ]);

        Role::reguard();
    }
}

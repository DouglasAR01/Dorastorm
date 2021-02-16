<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Role' => 'App\Policies\RolePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Combine both default roles and custom roles array, then create the gates following the pattern is{ROLE}
        // $roles = array_merge(config('roles.default'), config('roles.custom'));
        // foreach ($roles as $role){
        //     Gate::define('is'.$role, function($user) use ($role){
        //         return $user->hasRole($role);
        //     });
        // }
    }
}

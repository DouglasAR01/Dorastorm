<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class)->withDefault(config('roles.default_role'));
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function rolesBelow()
    {
        $user_hierarchy = $this->role->hierarchy;
        
        if ($user_hierarchy===config(('roles.default_role.hierarchy'))){
            return collect([]);
        }
        
        $roles_below = Role::where('hierarchy','>', ($user_hierarchy===0) ? -1 : $user_hierarchy )->orderBy('hierarchy', 'asc')->get();
        $roles_below->push(Role::makeDefault());
        return $roles_below;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public static function makeDefault()
    {
        $default = new Role();
        foreach (config('roles.default_role') as $att => $value) {
            $default->$att = $value;
        }
        return $default;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function insertHierarchy($hierarchy)
    {
        $below = Role::where('hierarchy', '>=', $hierarchy)->orderBy('hierarchy', 'desc')->get();
        if ($below->isNotEmpty()) {
            foreach ($below as $role_below){
                if ($role_below->id === $this->id) {
                    break;
                }
                $role_below->hierarchy += 1;
                $role_below->save();
            }
        } else {
            $strictly_below = Role::where('hierarchy', '<',$hierarchy)->orderBy('hierarchy', 'desc')->first();
            $hierarchy = $strictly_below->hierarchy + 1;
        }
        $this->hierarchy = $hierarchy;
    }
}

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
        foreach (config('roles.default_role') as $att => $value){
            $default->$att = $value;
        }
        return $default;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

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

    protected function tempHierarchyRemove()
    {
        $this->hierarchy = config('roles.default_role.hierarchy') + 1;
        $this->save();
    }

    protected function insertHierarchy($hierarchy)
    {
        $below = Role::where('hierarchy', '>=', $hierarchy)->where('hierarchy', '<', $this->hierarchy ?? (config('roles.default_role.hierarchy') + 1))->orderBy('hierarchy', 'desc')->get();
        // The hierarchy will be temporary changed if and only if the actual role hierarchy is not null
        if(!($this->hierarchy===null)){
            $this->tempHierarchyRemove();
        }
        if ($below->isNotEmpty()) {
            foreach ($below as $role_below) {
                $role_below->hierarchy += 1;
                $role_below->save();
            }
        } else {
            $strictly_below = Role::where('hierarchy', '<', $hierarchy)->orderBy('hierarchy', 'desc')->first();
            $hierarchy = $strictly_below->hierarchy + 1;
        }
        $this->hierarchy = $hierarchy;
    }

    protected function reverseInsertHierarchy($hierarchy)
    {
        $above = Role::where('hierarchy', '>', $this->hierarchy)->where('hierarchy', '<=', $hierarchy)->orderBy('hierarchy', 'asc')->get();
        if ($above->isNotEmpty()) {
            $this->tempHierarchyRemove();
            foreach ($above as $role_above) {
                $role_above->hierarchy -= 1;
                $role_above->save();
            }
            $this->hierarchy = $role_above->hierarchy + 1;
        }
    }

    public function assignHierarchy($hierarchy, $creating = false)
    {
        if ($creating || ($hierarchy < $this->hierarchy)) {
            $this->insertHierarchy($hierarchy);
        } elseif ($hierarchy > $this->hierarchy) {
            $this->reverseInsertHierarchy($hierarchy);
        }
    }

    public function delete()
    {
        if(parent::delete()){
            $below = Role::where('hierarchy', '>', $this->hierarchy)->orderBy('hierarchy','asc')->get();
            if($below->isNotEmpty()){
                foreach($below as $role_below){
                    $role_below->hierarchy -= 1;
                    $role_below->save();
                }
            }
            return true;
        }
        return false;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $role_permissions = [];
        $all_permissions = array_merge(config('roles.permissions.core'), config('roles.permissions.extended'));
        foreach ($all_permissions as $permission) {
            if($this->$permission == true){
                array_push($role_permissions, $permission);
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hierarchy' => $this->hierarchy,
            'permissions' => $role_permissions,
            'description' => $this->description,
            'created' => $this->created_at,
            'modified' => $this->updated_at,
        ];
    }
}

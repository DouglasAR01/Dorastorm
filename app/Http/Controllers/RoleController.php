<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Role::class)) {
            abort(403);
        }
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        // STANDARD role users should never enter here.
        if ($user->cannot('create', Role::class)) {
            abort(403);
        }
        $validation_rules = [
            'name' => 'required|unique:roles|min:2|max:50',
            'hierarchy' => 'required|unique:roles|gt:' . $user->role->hierarchy,
            'description' => 'string'
        ];
        $data = $request->validate($this->fullValidationRules($validation_rules));

        $new_role = new Role();
        $new_role->name = $data['name'];
        $new_role->description = $data['description'];
        $new_role->hierarchy = $data['hierarchy'];

        $new_role = $this->assignPermissions($user, $data, $new_role);
        $new_role->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        if ($request->user()->cannot('view', $role)) {
            abort(403);
        }
        return $role;
    }

    public function showRolesBelow(Request $request)
    {
        $user_hierarchy = $request->user()->role->hierarchy;
        // If the user is an ADMIN (hierarchy = 0), search for all roles        
        $roles_below = Role::where('hierarchy','>', ($user_hierarchy===0) ? -1 : $user_hierarchy )->get();
        return RoleResource::collection($roles_below);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $role = Role::findOrFail($id);
        // STANDARD role users should never enter here.
        // ADMIN role can not edit the ADMIN role. As it should be.
        if ($user->cannot('update', $role)) {
            abort(403);
        }
        $validation_rules = [
            'name' => 'required|unique:roles,name,' . $role->id . '|min:2|max:50',
            'hierarchy' => 'required|unique:roles,hierarchy,' . $role->id . '|gt:' . $user->role->hierarchy,
            'description' => 'string'
        ];
        $data = $request->validate($this->fullValidationRules($validation_rules));

        $role->name = $data['name'];
        $role->hierarchy = $data['hierarchy'];
        $role->description = $data['description'];

        $role = $this->assignPermissions($user,$data,$role);
        $role->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        $role = Role::findOrFail($id);
        if($request->user()->cannot('delete',$role)){
            abort(403);
        }
        $role->delete();
    }

    private function getAllPermissions()
    {
        return array_merge(config('roles.permissions.core'), config('roles.permissions.extended'));
    }

    private function fullValidationRules($validation_rules)
    {
        $permissions = $this->getAllPermissions();
        foreach ($permissions as $permission) {
            $validation_rules[$permission] = 'boolean';
        }
        return $validation_rules;
    }

    private function assignPermissions($user, array $data, Role $role)
    {
        // Check the current user permissions. The user can not create roles with more permissions than
        // his own role.
        $allowed_permissions = $this->contrastCurrentPermissions($user);
        return $this->fillRolePermissions($data, $allowed_permissions, $role);
    }

    private function contrastCurrentPermissions($user)
    {
        $user_permissions = [];
        $permissions = $this->getAllPermissions();
        foreach ($permissions as $permission) {
            if ($user->role->$permission) {
                array_push($user_permissions, $permission);
            }
        }
        return $user_permissions;
    }

    private function fillRolePermissions(array $data, array $allowed_permissions, Role $role)
    {
        foreach ($allowed_permissions as $permission) {
            $role->$permission = $data[$permission] ?? $role->$permission ?? false;
        }
        return $role;
    }
}

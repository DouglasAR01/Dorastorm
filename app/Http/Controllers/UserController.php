<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Rules\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showMe(Request $request)
    {
        return new UserResource($request->user());
    }

    public function rolesBelow(Request $request)
    {
        return RoleResource::collection($request->user()->rolesBelow());
    }

    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', User::class)) {
            abort(403);
        }
        return UserResource::collection(User::all());
    }

    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->user()->cannot('view', $user)) {
            abort(403);
        }
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create', User::class)) {
            abort(403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|unique:users|email|max:191',
            'password' => 'required|string|max:191|min:6|confirmed',
            'password_confirmation' => 'required|string|max:191|min:6',
            'role_id' => 'nullable',
        ]);
        $this->addRoleValidationRules($request->user()->role, $validator);
        $data = $validator->validate();
        $new_user = User::make($data);
        $new_user->role_id = $data['role_id'];
        $new_user->password = Hash::make($data['password']);
        $new_user->save();
        return new UserResource($new_user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->user()->cannot('update', $user)) {
            abort(403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:191',
            'email' => 'sometimes|required|email|max:191|unique:users,email,' . $user->id,
            'role_id' => 'nullable'
        ]);
        $this->addRoleValidationRules($request->user()->role, $validator);
        $data = $validator->validate();
        // Check if the user is the last admin left and he is trying to change his role
        if (
            $user->role->hierarchy === 0 && !empty($data['role_id']) &&
            $user->role->id != $data['role_id'] && $this->isLastAdminLeft($user)
        ) {
            abort(409, trans('validation.custom.user_destroy.sole_admin'));
        }

        // Start of the differential updating
        foreach ($data as $att => $value) {
            $user->$att = $value;
        }
        $user->save();
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->user()->cannot('update', $user)) {
            abort(403);
        }
        $data = $request->validate([
            'password' => 'required|string|max:191|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'current_password' => [
                'required',
                'string',
                'max:191',
                'min:6',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail(trans('auth.password'));
                    }
                }
            ],
        ]);
        $user->password = Hash::make($data['password']);
        $user->save();
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->user()->cannot('delete', $user)) {
            abort(403);
        }
        if ($this->isLastAdminLeft($user)) {
            abort(409, trans('validation.custom.user_destroy.sole_admin'));
        }
        $user->delete();
    }

    // Check if the user is the only admin left. In the database must be at least one ADMIN user.
    // If so, returns true.
    private function isLastAdminLeft(User $user): bool
    {
        $admin_role_id = Role::where('hierarchy', '=', 0)->firstOrFail()->id;
        if ($user->role->hierarchy === 0 && User::where('role_id', '=', $admin_role_id)->count() < 2) {
            return true;
        }
        return false;
    }

    private function addRoleValidationRules($user_role, $validator)
    {
        $validator->sometimes('role_id', [
            'bail',
            'required',
            'numeric',
            'min:1',
            'exists:roles,id',
            new UserRole($user_role)
        ], function ($input){
            return !($input->role_id===null);
        });
    }
}

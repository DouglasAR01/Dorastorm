<?php

namespace App\Rules;

use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;

class UserRole implements Rule
{
    
    protected $user_role;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Role $user_role)
    {
        $this->user_role = $user_role;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value_role = Role::find($value);
        if ($this->user_role->id === $value_role->id){
            return true;
        }
        if (($value_role->hierarchy > $this->user_role->hierarchy) || $this->user_role->hierarchy === 0) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.user_role');
    }
}

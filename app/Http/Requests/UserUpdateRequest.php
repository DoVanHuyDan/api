<?php

namespace App\Http\Requests;

use App\Models\User;

class UserUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['email', 'max:64', 'unique:users,email,'.$this->id],
            'password' => 'min:6|max:20',
        ];
    }
}

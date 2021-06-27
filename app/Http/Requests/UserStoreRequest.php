<?php

namespace App\Http\Requests;

use App\Models\User;

class UserStoreRequest extends BaseRequest
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
            'email' => ['required', 'email', 'max:64', 'unique:users,email'],
            'password' => 'required|min:6|max:20',
        ];
    }


}

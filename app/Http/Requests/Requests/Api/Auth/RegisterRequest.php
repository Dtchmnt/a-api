<?php

namespace App\Http\Requests\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:55',
            'email' => 'email|required',
            'type' => 'required|in:backend, frontend',
            'github' => 'max:75',
            'city' => 'max:55',
            'phone' => 'min:11|numeric',
            'birthday' => 'date_format:Y-m-d',
            'password' => 'required|confirmed'
        ];
    }
}

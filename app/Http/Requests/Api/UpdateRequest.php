<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
                'show_name' => 'string|nullable',
                'image' => 'required|file',
                'about' => 'required|string',
                'github' => 'url|nullable',
                'city' => 'string|nullable',
                'is_finished' => 'boolean',
                'telegram' => 'string|nullable',
                'phone' => 'string|nullable',
                'birthday' => 'date|nullable'
            ];
        }
}

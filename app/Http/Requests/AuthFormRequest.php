<?php

namespace Journal\Http\Requests;

use Journal\Http\Requests\Request;

class AuthFormRequest extends Request
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
     * Overrides the default message for the validations
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'    => 'E-mail is required.',
            'email.email'       => 'E-mail format is invalid.',
            'password.required' => 'Password is required.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => 'required|email',
            'password'  => 'required'
        ];
    }
}

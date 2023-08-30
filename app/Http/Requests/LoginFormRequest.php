<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
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
            'email_or_mobile' => 'required',
            'password' => 'required|min:6|max:20',
        ];
    }

    public function messages()
    {
        return [
            'email_or_mobile.required' => 'Oops! You may have forgotten to enter your email or mobile.',
            'password.required' => 'Oops! You may have forgotten to enter your password.',
        ];
    }
}

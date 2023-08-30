<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = auth()->id();
        return [
            'name' => 'required|regex:/^[a-zA-Z]/',
            'email' => [
                'required',
                Rule::unique('users')->ignore($userId),
                'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            ],
            'mobile_number' => [
                'required',
                Rule::unique('users')->ignore($userId),
                'regex:/^01[3-9]\d{8}$/',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Oops! You may have forgotten to enter your full name.',
            'name.regex' => 'Oops! Invalid user name!',
            'email.required' => 'Oops! You may have forgotten to enter your email.',
            'email.regex' => 'Oops! Invalid user name!',
            'mobile_number.required' => 'Oops! You may have forgotten to enter your mobile.',
            'mobile_number.regex' => 'Oops! Invalid mobile number!',
        ];
    }
}

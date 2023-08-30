<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
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
            'name' => 'required',
            'email_or_mobile' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^(?:\+88|01)?\d{11}$/', $value)) {
                        if (is_numeric($value)) {
                            $fail('Oops! Mobile number is invalid.');
                        } else {
                            $fail('Oops! Email is invalid.');
                        }
                    }
                },
                'unique:users,email',
                'unique:users,mobile_number'
            ],
            'password' => 'required|confirmed|min:6|max:20',
        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'Oops! You may have forgotten to enter your full name.',
            'email_or_mobile.required' => 'Oops! You may have forgotten to enter your email or mobile.',
            'password.required' => 'Oops! You may have forgotten to enter your password.',
        ];
    }
}

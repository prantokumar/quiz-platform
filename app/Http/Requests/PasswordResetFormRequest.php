<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetFormRequest extends FormRequest
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
            ],
        ];
    }

    public function messages()
    {
        return [
            'email_or_mobile.required' => 'Oops! You may have forgotten to enter your email or mobile.',
        ];
    }
}

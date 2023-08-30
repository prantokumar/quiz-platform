<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateFormRequest extends FormRequest
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
        return [
            'current_password' => 'required|min:6|max:20',
            'password' => 'required|min:6|max:20',
            'confirm_password' => 'required|min:6|max:20',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Oops! You may have forgotten to enter your old password.',
            'password.required' => 'Oops! You may have forgotten to enter your new password.',
            'confirm_password.required' => 'Oops! You may have forgotten to retype new password.',
        ];
    }
}

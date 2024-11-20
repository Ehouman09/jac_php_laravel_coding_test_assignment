<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


//Let create a Login Request in order to validate login form
class LoginRequest extends FormRequest
{
    /**
     * This will determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //This will allow user to make a request
    }

    /**
     * Let define validation rules that apply to the request when user will fill the login form.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:50'], 
            'password' => ['required', 'min:6']
        ];
    }

    /**
     * A custom validation messages for the defined rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => __('validation.email.required'),
            'email.email' => __('validation.email.email'),
            'email.max' => __('validation.email.max'),
            'password.required' => __('validation.password.required'),
            'password.min' => __('validation.password.min'),
        ];
    }

    /**
     * Let change the de default attributes by our own for validator errors.
     */
    public function attributes(): array
    {
        return [
            'email' => __('attributes.email'),
            'password' => __('attributes.password'),
        ];
    }
}

<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


//Let create a Register Request in order to validate register form
class RegisterRequest extends FormRequest
{
    /**
     * This will determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //This will allow user to make a request
    }

    /**
     * Let define validation rules that apply to the request when user will fill the registration form.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:50', 'unique:users,email'],  
            'password' => ['required', 'min:6', 'confirmed'],  
            'name' => ['required', 'string', 'max:100'], 
        ];
    }

    /**
     * Custom validation messages for each defined rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => __('validation.email.required'), 
            'email.email' => __('validation.email.email'), 
            'email.unique' => __('validation.email.unique'),
            'email.max' => __('validation.email.max'),  
            'password.required' => __('validation.password.required'),  
            'password.min' => __('validation.password.min'), 
            'password.confirmed' => __('validation.password.confirmed'),
            'name.required' => __('validation.name.required'),  
            'name.string' => __('validation.name.string'), 
            'name.max' => __('validation.name.max'), 
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
            'name' => __('attributes.name'),  
        ];
    }
}

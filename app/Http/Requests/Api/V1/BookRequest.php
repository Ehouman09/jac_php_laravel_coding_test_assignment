<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;


// Let create a Book Request in order to validate book form
class BookRequest extends FormRequest
{
    /**
     * This will determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // This will allow the user to make a request
    }

   
    /**
     * Get the validation rules that apply to the request.
     *
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'cover_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Check if the request is a POST request, if so, add the cover image rule
        // Otherwise, make the cover image rule nullable
        // This way, if the user is updating a book, they don't have to provide a new cover image
        // If the user is creating a new book, they must provide a cover image.
        if ($this->isMethod('post')) {
            $rules['cover_image'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['cover_image'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        return $rules;
    }

    /**
     * Custom validation messages for each defined rule.
     */
    public function messages(): array
    {
        return [
            'title.required' => __('validation.title.required'),
            'title.max' => __('validation.title.max'),
            'author.required' => __('validation.author.required'),
            'author.max' => __('validation.author.max'),
            'description.required' => __('validation.description.required'),
            'category_id.exists' => __('validation.category_id.exists'),
            'publication_year.required' => __('validation.publication_year.required'),
            'publication_year.integer' => __('validation.publication_year.integer'),
            'publication_year.min' => __('validation.publication_year.min'),
            'publication_year.max' => __('validation.publication_year.max'),
            'cover_image.required' => __('validation.cover_image.required'),
            'cover_image.image' => __('validation.cover_image.image'),
            'cover_image.mimes' => __('validation.cover_image.mimes'),
            'cover_image.max' => __('validation.cover_image.max'),

        ];
    }

    /**
     * Customize the attributes for the validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => __('attributes.title'),
            'author' => __('attributes.author'),
            'description' => __('attributes.description'),
            'category_id' => __('attributes.category'),
            'publication_year' => __('attributes.publication_year'),
            'cover_image' => __('attributes.cover_image'),
        ];
    }
}

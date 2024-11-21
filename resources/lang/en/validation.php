<?php


return [
    'email' => [
        'required' => 'The email address is required.',
        'email' => 'Please enter a valid email address.',
        'max' => 'The email address must not exceed 50 characters.',
        'unique' => 'The email address is already in use.',
    ],
    'password' => [
        'required' => 'The password is required.',
        'min' => 'The password must be at least 6 characters.',
        'confirmed' => 'The password confirmation does not match.',
    ],
    'name' => [
        'required' => 'The name field is required.',
        'string' => 'The name must be a string.',
        'max' => 'The name must not be greater than 100 characters.',
    ],
    'title' => [
        'required' => 'The title is required.',
        'max' => 'The title may not be greater than 255 characters.',
    ],
    'author' => [
        'required' => 'The author is required.',
        'max' => 'The author name may not be greater than 255 characters.',
    ],
    'description' => [
        'required' => 'The description is required.',
    ],
    'category_id' => [
        'exists' => 'The selected category is invalid.',
    ],
    'publication_year' => [
        'required' => 'The publication year is required.',
        'integer' => 'The publication year must be a valid integer.',
        'min' => 'The publication year must be at least 1000.',
        'max' => 'The publication year must not be greater than the current year.',
    ],
    'cover_image' => [
        'image' => 'The cover image must be an image file.',
        'max' => 'The cover image may not be greater than 2MB.',
        'mimes' => 'The cover image must be a JPEG, PNG or JPG file.',
        'required' => 'The cover image is required.',
    ],
];
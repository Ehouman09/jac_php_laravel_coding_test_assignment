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
];
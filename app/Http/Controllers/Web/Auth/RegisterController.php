<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Enums\TokenAbility;
use App\Http\Requests\Web\RegisterRequest;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Hash;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles users registration for the application and
    | redirecting them to the book index page.
    |
    */


    //Display the registration form
    public function showRegistrationForm()
    {
        //Before displaying the registration form, let check if the user is already logged
        if (Auth::check()) {
            //redirect user to the books index if he's already logged
            return redirect()->route('books.index')->with('warning', "You are already logged !");
        }

        //Display the registration form if the user is not logged
        return view('auth.register');
    }



    /**
     * Handle an incoming registration request.
     * 
     */
    public function register(RegisterRequest $request) {

        try {
            // Get only validated input data
         // Get only validated input data
         $validatedData = $request->validated();

         // Create a new user instance with the provided data
         $user = User::create([
             'name' => $validatedData['name'],
             'email' => $validatedData['email'],
             'password' => Hash::make($validatedData['password']), // Hash the password
         ]);
 
         // Log in the new user
         Auth::login($user);
 
         // Log the successful registration
         Log::info('Registration successful', ['user' => $user->id]);
 
         // Let redirect the user to the the books index with a success message
         return redirect()->route('books.index')->with('success', "Registration successful. Welcome!");
         
         } catch (\Exception $e) {
             // Log the failed registration attempt
             Log::error('Registration failed', ['error' => $e->getMessage()]);
 
             // Display an error message if registration fails
             return redirect()->back()->with('error', "Registration failed.");
         }
        
    }





}
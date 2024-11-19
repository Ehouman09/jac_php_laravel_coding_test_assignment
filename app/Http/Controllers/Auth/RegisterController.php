<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Enums\TokenAbility;
use App\Http\Requests\RegisterRequest;
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
    | redirecting them to the dashboard.
    |
    */


    //Display the registration form
    public function showRegistrationForm()
    {
        //Before displaying the registration form, let check if the user is already logged
        if (Auth::check()) {
            //redirect user to the dashboard if he's already logged
            return redirect()->route('dashboard')->with('warning', __('auth.yr_ar_already_logged'));
        }

        //Display the registration form if the user is not logged
        return view('auth.register');
    }



    /**
     * Handle an incoming registration request.
     * 
     */
    public function register(RegisterRequest $request) {

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
 
         // Let redirect the user to the dashboard with a success message
         return redirect()->route('dashboard')->with('success', __('auth.register_success'));
        
    }





}
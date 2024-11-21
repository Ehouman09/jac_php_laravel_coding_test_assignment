<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Enums\TokenAbility;
use App\Http\Requests\Web\LoginRequest;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to index if they are successfully logged in.
    |
    */

    //Display login form
    public function showLoginForm()
    {
        //Before displaying the login form, let check if the user is already logged
        if (Auth::check()) {
            //redirect user to the index if he's already logged
            return redirect()->route('books.index')->with('warning', __('auth.yr_ar_already_logged'));
        }

        //Display the login form if the user is not logged
        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     *
     */
    public function login(LoginRequest $request) {

         
        $credentials = $request->validated();

        // Log the login attempt
        Log::info('Login attempt from the web', ['credentials' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            // Log the successful login
            Log::info('Login successful from the web', ['user' => Auth::user()->id]);
            // Log in the user
            $request->session()->regenerate();
            // Redirect the user to the index with a success message
            return redirect()->route('books.index')->with('success', __('common.welcome_msg'));
        }

        // Log the failed login attempt
        Log::error('Login failed from the web', ['credentials' => $credentials['email']]);

        // Display an error message if user credentials are wrong 
        return redirect()->back()->with('error', __('auth.invalid_credentials'))->withInput($credentials);
        
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }





}
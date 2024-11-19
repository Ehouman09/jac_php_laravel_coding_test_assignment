<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Enums\TokenAbility;
use App\Http\Requests\LoginRequest;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to admin dashboard.
    |
    */

    //Display login form
    public function showLoginForm()
    {
        //Before displaying the login form, let check if the user is already logged
        if (Auth::check()) {
            //redirect user to the dashboard if he's already logged
            return redirect()->route('dashboard')->with('warning', __('auth.yr_ar_already_logged'));
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


        if (Auth::attempt($credentials)) {
            // Log in the user
            $request->session()->regenerate();
            // Redirect the user to the dashboard with a success message
            return redirect()->route('dashboard')->with('success', __('common.welcome_back'));
        }

        // Display an error message if user credentials are wrong 
        return redirect()->back()->with('error', __('auth.invalid_credentials'))->withInput($credentials);
        
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }





}
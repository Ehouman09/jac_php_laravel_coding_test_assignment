<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Enums\TokenAbility;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Traits\JsonResponseTrait;
use App\Http\Resources\Api\V1\UserResource;


class LoginController extends Controller
{

    // Let call our json response trait
    use JsonResponseTrait;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to admin dashboard.
    |
    */
    
 
    /**
     * Handle an incoming authentication request.
     */
    
    public function login(LoginRequest $request) {

         
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {

            // Send an error message if user credentials are wrong
            return $this->jsonResponse(401, __('auth.invalid_credentials'), null);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;
  
        // Return the token and user
        $data = [
            'token' => $token,
            'user' => new UserResource($user)
        ];
        
        return $this->jsonResponse(200, "success", $data);
 
        
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
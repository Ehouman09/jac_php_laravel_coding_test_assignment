<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Enums\TokenAbility;
use App\Http\Requests\LoginRequest;


class DashboardController extends Controller
{

    public function dashboard()
    {

        return view('dashboard.index');
    }

}
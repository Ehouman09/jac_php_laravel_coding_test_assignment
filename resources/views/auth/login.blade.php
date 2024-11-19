@extends('layouts.front_layout')

@section('title', __('auth.login'))

@section('content')


<!-- Login form  -->
<div class="card">
    <div class="card-header text-center"><a href="/">
        <h3 class="fs-20"> {{  __('auth.login') }} </h3>
        </a><span class="splash-description"> {{  __('auth.login_msg') }} </span>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <input class="form-control form-control-lg" name="email" value="{!! old('email') !!}" id="email" type="email" placeholder="{{  __('auth.email') }}" autocomplete="off">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" name="password" id="password" type="password" placeholder="{{  __('auth.password') }}">
            </div> 
            <button type="submit" class="btn btn-primary btn-lg btn-block mt-5">{{  __('auth.sign_in') }}</button>
        </form>
    </div>

    <div class="card-footer bg-white">
        <p> {{  __('auth.yr_ar_no_account') }} <a href="{{ route('register') }}" class="text-secondary"> {{  __('auth.register_her') }}</a></p>
    </div>
 
</div>
<!-- End of login form  -->

@endsection

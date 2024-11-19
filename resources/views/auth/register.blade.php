@extends('layouts.front_layout')

@section('title', __('auth.registration'))

@section('content')


    <!-- Register form  -->
    <form  method="post" action="{{ route('register') }}" class="splash-container">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="mb-1"> {{  __('auth.registration') }} </h3>
                <p> {{  __('auth.registration_msg') }}</p>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input class="form-control form-control-lg" type="text" name="name" value="{!! old('name') !!}" required="" placeholder="{{  __('auth.name') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="email" name="email" value="{!! old('email') !!}" required="" placeholder="{{  __('auth.email') }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg"  name="password" type="password" required="" placeholder="{{  __('auth.password') }}">
                </div>
                <div class="form-group">
                    <input class="form-control form-control-lg" type="password" name="password_confirmation" required="" placeholder="{{  __('auth.confirm_password') }}">
                </div>
                <div class="form-group pt-2">
                    <button class="btn btn-block btn-primary" type="submit"> {{  __('auth.register') }}</button>
                </div>
                
            </div>
            <div class="card-footer bg-white">
                <p> {{  __('auth.already_account') }} <a href="/" class="text-secondary"> {{  __('auth.login_here') }}</a></p>
            </div>
        </div>
    </form>
    <!-- End of register form  -->
    


@endsection
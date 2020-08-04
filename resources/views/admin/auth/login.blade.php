@extends('layouts.admin.simple')

@section('title','Login')

@section('content')

    <form class="login-form" action="{{ route('admin.login.submit') }}" method="post">
        @csrf

{{--        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i></h3>--}}
        <div class="form-group">
            <label class="control-label">USERNAME</label>
            <input class="form-control" type="text" placeholder="Email" name="email" autofocus>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="control-label">PASSWORD</label>
            <input class="form-control" type="password" placeholder="Password" name="password">
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="utility">
                <div class="animated-checkbox">
                    <label>
                        <input type="checkbox"><span class="label-text">Stay Signed in</span>
                    </label>
                </div>
                <p class="semibold-text mb-2"><a href="{{ route('admin.password.request') }}" data-toggle="flip">Forgot Password ?</a></p>
            </div>
        </div>
        <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
        </div>
    </form>
@stop

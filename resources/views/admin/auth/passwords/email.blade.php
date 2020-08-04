@extends('layouts.admin.simple')

@section('content')

    <form class="login-form" method="POST" action="{{ route('admin.password.email') }}">
        @csrf

{{--        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>@section('title','Reset password')</h3>--}}

        <div class="form-group">
            <label class="control-label">USERNAME</label>
            <input class="form-control" type="text" placeholder="Email" name="email" autofocus>
            @error('email')
            <div class="text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            {{ __('Send Password Reset Link') }}
        </button>
    </form>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endsection

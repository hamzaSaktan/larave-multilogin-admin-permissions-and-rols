@extends('layouts.admin.app')

@section('title','Social login')

@section('bc')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}"><i class="fa fa-home fa-lg"></i></a></li>
    <li class="breadcrumb-item">Social login</li>
@stop

@section('content')
    <form method="post" class="form-ajax" action="{{ route('admin.settings.store') }}">

        @php
            $sites_names = ['facebook','google'];
        @endphp

        <ul class="nav nav-tabs">

            @foreach($sites_names as $index => $site_name)
                <li class="nav-item">
                    <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-toggle="tab" href="#{{$site_name}}">{{ucfirst($site_name)}}</a>
                </li>
            @endforeach

        </ul>

        <div id="myTabContent" class="tab-content">
            @foreach($sites_names as $index => $site_name)
            <div class="tab-pane fade {{ $index == 0 ? 'active show' : '' }} mt-3" id="{{$site_name}}">

                <div class="form-group">
                    <label for="{{$site_name }}_client_id">{{ucfirst($site_name)}} Secret id</label>
                    <input type="text"
                           class="form-control"
                           id="{{$site_name }}_client_id"
                           name="{{$site_name }}_client_id"
                           value="{{setting($site_name.'_client_id')}}"
                           placeholder="">
                </div>

                <div class="form-group">
                    <label for="{{$site_name }}_client_secret">{{ucfirst($site_name)}} Client Secret</label>
                    <input type="text"
                           class="form-control"
                           id="{{$site_name }}_client_secret"
                           name="{{$site_name }}_client_secret"
                           value="{{setting($site_name.'_client_secret')}}"
                           placeholder="">
                </div>

                <div class="form-group">
                    <label for="{{$site_name }}_redirect_url">{{ucfirst($site_name)}} Redirect Url</label>
                    <input type="text"
                           class="form-control"
                           id="{{$site_name }}_redirect_url"
                           name="{{$site_name }}_redirect_url"
                           value="{{setting($site_name.'_redirect_url')}}"
                           placeholder="">
                </div>

            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
@stop

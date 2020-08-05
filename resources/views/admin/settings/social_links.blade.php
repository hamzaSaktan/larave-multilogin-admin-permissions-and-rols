@extends('layouts.admin.app')

@section('title','Social links')

@section('bc')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}"><i class="fa fa-home fa-lg"></i></a></li>
    <li class="breadcrumb-item">Social links</li>
@stop

@section('content')
    <form method="post" class="form-ajax" action="{{ route('admin.settings.store') }}">

        @php
            $sites_names = ['facebook','google'];
        @endphp

            @foreach($sites_names as $index => $site_name)
                <div class="form-group">
                    <label for="{{$site_name }}_link">{{ucfirst($site_name)}} Link</label>
                    <input type="text"
                           class="form-control"
                           id="{{$site_name }}_link"
                           name="{{$site_name }}_link"
                           value="{{ setting($site_name.'_link') }}"
                           placeholder="">
                </div>
            @endforeach

        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
@stop

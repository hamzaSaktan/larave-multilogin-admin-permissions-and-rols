<!DOCTYPE html>
<html lang="en">
<head>

    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('cpanel/css/main.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/r-2.2.5/datatables.min.css"/>

</head>
<body class="app sidebar-mini">
<!-- Navbar-->

@include('layouts.admin._header')
{{--header--}}

<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>

{{--aside--}}
@include('layouts.admin._aside')
{{--content--}}
<main class="app-content">

    <div class="app-title">
        <div>
            <h1>@yield('title')</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            @yield('bc')
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">@yield('content')</div>
            </div>
        </div>
    </div>

    @include('layouts.admin._modal')
</main>

<!-- Essential javascripts for application to work-->
<script src="{{ asset('cpanel/js/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/r-2.2.5/datatables.min.js"></script>

<script src="{{ asset('cpanel/js/popper.min.js') }}"></script>
<script src="{{ asset('cpanel/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('cpanel/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('cpanel/js/plugins/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('cpanel/js/plugins/sweetalert.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
<script src="{{ asset('cpanel/js/main.js') }}"></script>
<script src="{{ asset('cpanel/app/js/main.js') }}"></script>

<!-- The javascript plugin to display page loading on top-->
{{--<script src="js/plugins/pace.min.js"></script>--}}
<!-- Page specific javascripts-->
@yield('javascript')
</body>
</html>


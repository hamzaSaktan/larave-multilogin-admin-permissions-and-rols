@extends('layouts.admin.app')

@section('title','Amins')

@section('bc')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fa fa-home fa-lg"></i></a></li>
    <li class="breadcrumb-item">Roles</li>
@stop


@section('content')

    @if(Auth::user()->hasPermission('admins-create'))
    <button class="btn btn-primary btn-sm action-create mb-3"
            data-route="{{route('admins.create')}}"><i
            class="fa fa-add"></i>Add Admin</button>
    @else
        <button disabled class="btn btn-primary btn-sm mb-3"><i
                class="fa fa-add"></i>Add Admin</button>
    @endif

    <table id="table_id" class="display table table-bordered table-striped dataTable">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
@stop

@section('javascript')
    <script>
        // Datatables
        let table = $('#table_id').DataTable( {
            responsive: true,
            processing : true,
            // order : false,
            ajax : '{{ route('admins.index') }}',
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'roles' },
                { data: 'actions' },
            ],
            {{--"language": {!! __('plugins.datatables'); !!}--}}
        });
    </script>
@stop

@extends('layouts.admin.app')

@section('title','Roles')

@section('bc')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fa fa-home fa-lg"></i></a></li>
    <li class="breadcrumb-item">Roles</li>
@stop


@section('content')
    @if(Auth::user()->hasPermission('roles-create'))
        <button class="btn btn-primary btn-sm action-create mb-3"
                data-route="{{route('roles.create')}}"><i
                class="fa fa-add"></i>Add Role</button>
    @else
        <button disabled class="btn btn-primary btn-sm mb-3">
            <i class="fa fa-add"></i>Add Role</button>
    @endif

    <table id="table_id" class="display table table-bordered table-striped dataTable">
        <thead>
        <tr>
            <th>Name</th>
            <th>Display name</th>
            <th>Description</th>
            <th>Permissions</th>
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
            ajax : '{{ route('roles.index') }}',
            columns: [
                { data: 'name' },
                { data: 'display_name' },
                { data: 'description' },
                { data: 'permissions' },
                { data: 'actions' },
            ],
            {{--"language": {!! __('plugins.datatables'); !!}--}}
        });
    </script>
@stop

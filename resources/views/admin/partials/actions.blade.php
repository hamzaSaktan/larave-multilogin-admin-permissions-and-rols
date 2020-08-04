@if(Auth::user()->hasPermission($can_delete))
    <button class="btn btn-info btn-sm action-edit"
            data-id="{{$id}}"
            data-route="{{route($route.'.edit',$id)}}" ><i class="fa fa-edit"></i> Edit</button>
@else
    <button disabled class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button>
@endif

@if(Auth::user()->hasPermission($can_update))

    <button class="btn btn-warning btn-sm action-delete" data-id="{{$id}}"><i class="fa fa-trash"></i> Delete</button>
    <form style="display:none;" action="{{route($route.'.destroy',$id)}}" method="post" class="form-ajax">
        @csrf
        @method('delete')
    </form>

@else
    <button disabled class="btn btn-warning btn-sm" ><i class="fa fa-trash"></i> Delete</button>
@endif

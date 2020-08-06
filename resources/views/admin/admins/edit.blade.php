<form method="post" class="form-ajax" action="{{ route('admins.update',$admin->id) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               value="{{ $admin->name }}"
               placeholder="">
    </div>

    <div class="form-group">
        <label for="email">email</label>
        <input type="text"
               class="form-control"
               id="email"
               name="email"
               value="{{ $admin->email }}"
               placeholder="">
    </div>

    <div class="form-group" id="select2">
        <label for="email">Roles</label>

        <select class="form-control select2-class" name="role">
            <optgroup label="Select Roles">
                @foreach($roles as $role)
                    <option
                        {{  $admin->hasRole($role->name) ? 'selected' : '' }} value="{{$role->id}}">{{ $role->display_name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

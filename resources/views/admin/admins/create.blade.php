<form method="post" class="form-ajax" action="{{ route('admins.store') }}">
    @csrf

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               value="{{ old('name') }}"
               placeholder="">
    </div>

    <div class="form-group">
        <label for="email">email</label>
        <input type="text"
               class="form-control"
               id="email"
               name="email"
               value="{{ old('email') }}"
               placeholder="">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="text"
               class="form-control"
               id="password"
               name="password">
    </div>

    <div class="form-group" id="select2">
        <label for="role">Roles</label>

        <select class="form-control select2-class" name="role" id="role">
            <optgroup label="Select Roles">
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{ $role->display_name }}</option>
            @endforeach
        </select>
        <a href="{{ route('roles.index') }}">Add New Role</a>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

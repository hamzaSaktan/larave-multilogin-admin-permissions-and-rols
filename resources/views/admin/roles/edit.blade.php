<form method="post" class="form-ajax" action="{{ route('roles.update',$role->id) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               value="{{ $role->name }}">
    </div>

    <div class="form-group">
        <label for="name">display name</label>
        <input type="text"
               class="form-control"
               id="display_name"
               name="display_name"
               value="{{ $role->display_name }}">
    </div>

    <div class="form-group">
        <label for="name">description</label>
        <input type="text"
               class="form-control"
               id="description"
               name="description"
               value="{{ $role->description }}">
    </div>

    <div class="form-group" id="select2">

        <label for="permissions">Permissions</label>

        <table id="permissions" class="table table-bordered table-striped table-hover">
            @php
                $models = config('laratrust_seeder.models');
            @endphp

            <thead>
            <tr>
                <td>Models</td>
                <td>Permissions</td>
            </tr>
            </thead>

            <tbody>
            @foreach($models as $model)
                <tr>
                    <td width="25%">{{ ucfirst($model) }}</td>
                    <td width="75%">
                        <select class="form-control select2-class" multiple="" name="permissions[]">
                            <optgroup label="Select Permissions">
                                @php
                                    $permissions_map = config('laratrust_seeder.permissions_map');
                                @endphp

                                @foreach($permissions_map as $key=>$permission)
                                    <option
                                        {{ $role->hasPermission($model.'-'.$permission) ? 'selected' : '' }}
                                        value="{{ $model.'-'.$permission }}">

                                        {{ ucfirst($permission) }}
                                    </option>

                                @endforeach
                            </optgroup>
                        </select>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use DataTables;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-create')->only(['create','store']);
        $this->middleware('permission:roles-read')->only(['index']);
        $this->middleware('permission:roles-update')->only(['update','edit']);
        $this->middleware('permission:roles-delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            return DataTables::eloquent(Role::query()->WhereRoleNot(['super_admin','user']))
                ->addColumn('permissions',function (Role $role){
                    return '<span class="badge badge-dark">'.implode('</span> <span class="badge badge-dark">',$role->permissions()->pluck('display_name')->toArray()).'</span>';
                })
                 ->addColumn('actions',function (Role $role){
                    $route = 'roles';
                     $can_delete = 'roles-delete';
                     $can_update = 'roles-update';
                    $id = $role->id;
                    return view('admin.partials.actions',compact('route','id','can_delete','can_update'));
                })->rawColumns(['actions','permissions'])->toJson();
        }
        return view('admin.roles.index');
    }

    public function create(Request $request)
    {
        if($request->ajax()){
            return $view = view('admin.roles.create')->render();
        }else{
            echo 'Create';
        }
    }

    public function store(Request $request)
    {

        $request->validate(
            [
                'name' => ['required','unique:roles,name'],
                'display_name' => 'string',
                'description' => 'string',
                'permissions' => ['required','array','min:1'],
            ]
        );

        $role = Role::create($request->except('permissions'));
        $role->attachPermissions($request->permissions);

        return response()->json([
                'response' => [
                    'status' => 'success',
                    'title' => 'Created',
                    'message' => 'Role '.$request->display_name.' Created',
                ]
            ]);
    }

    public function edit(Role $role,Request $request)
    {
        if($request->ajax()) {
            $permissions = $role->permissions()->pluck('name')->toArray();
            return $view = view('admin.roles.edit',compact('role','permissions'))->render();
        }else{
            echo 'Edit';
        }
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(
            [
                'name' => 'required|unique:roles,name,'.$role->id,
                'display_name' => 'string',
                'description' => 'string',
                'permissions' => 'required|array|min:1',
            ]
        );

        $role->update($request->except('permissions'));
        $role->syncPermissions($request->permissions);

        return response()->json([
            'response' => [
                'status' => 'success',
                'title' => 'Updated',
                'message' => 'Role '.$request->display_name.' Updated',
            ]
        ]);
    }

    public function destroy(Role $role)
    {
        $role->detachPermissions();
        $role->delete();
        return response()->json([
            'response' => [
                'status' => 'success',
                'title' => 'Deleted',
                'message' => 'Role '.$role->display_name.' Deleted',
            ]
        ]);
    }
}

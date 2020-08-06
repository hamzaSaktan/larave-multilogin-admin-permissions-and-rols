<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
class AdminsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admins-create')->only(['create','store']);
        $this->middleware('permission:admins-read')->only('index');
        $this->middleware('permission:admins-update')->only(['update','edit']);
        $this->middleware('permission:admins-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            return DataTables::eloquent(Admin::query()->WhereRoleNot('super_admin'))
                ->addColumn('roles', function(Admin $admin){
                   return $admin->roles()->pluck('display_name')->toArray();
                })
                ->addColumn('actions',function (Admin $admin){
                    $id = $admin->id;
                    $can_delete = 'admins-delete';
                    $can_update = 'admins-update';
                    $route = 'admins';
                    return view('admin.partials.actions',compact('id','route','can_delete','can_update'));
                })->rawColumns(['actions','roles'])->toJson();
        }
        return view('admin.admins.index');
    }

    public function create(Request $request)
    {
        if($request->ajax()){
            $roles = Role::WhereRoleNot(['super_admin','user'])->get();
            return $view = view('admin.admins.create',compact('roles'))->render();
        }else{
            echo 'Create';
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email',
            'password' => 'min:6|required',
            'name' => 'required|string|min:6|max:30',
            'role' => 'required',
        ]);

        $request->merge(['password' => bcrypt($request->password)]);

        $admin = Admin::create($request->except(['role']));
        $admin->attachRole($request->role);

        return response()->json([
            'response' => [
                'status' => 'success',
                'title' => 'Created',
                'message' => 'Admin '.$request->name.' Created',
            ]
        ]);
    }

    public function edit(Admin $admin , Request $request)
    {
        if($request->ajax()) {
            $roles = Role::WhereRoleNot(['super_admin','user'])->get();
            return $view = view('admin.admins.edit', compact('admin','roles'))->render();
        }else{
            echo 'Edit';
        }
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'name' => 'required|string|min:6|max:30',
            'role' => 'required',
        ]);

        if($admin->email != $request->email){
            $admin->forceFill(['email_verified_at' => null]);
        }

        $admin->update($request->except('role'));
        $admin->syncRoles([$request->role]);

        return response()->json([
            'response' => [
                'status'  => 'success',
                'title'   => 'Updated',
                'message' => 'Admin '.$request->name.' Updated',
            ]
        ]);
    }

    public function destroy(Admin $admin)
    {
        //
    }
}

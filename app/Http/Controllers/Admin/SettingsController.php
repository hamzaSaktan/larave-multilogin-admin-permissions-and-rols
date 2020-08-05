<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:settings-create')->only(['store']);
        $this->middleware('permission:settings-read')->only(['social_links','social_login','index']);
    }

    public function index(){
        return view('admin.settings.index');
    }

    public function social_links(){
        return view('admin.settings.social_links');
    }

    public function social_login(){
        return view('admin.settings.social_login');
    }

    public function store(Request $request){

        setting($request->all())->save();

        return response()->json([
            'response' => [
                'status' => 'success',
                'title' => 'Updated',
                'message' => 'Settings Updated',
            ]
        ]);
    }
}

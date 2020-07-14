<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Admin;
use Illuminate\Http\Request;

//use Illuminate\Foundation\Auth\RegistersUsers;
//use Illuminate\Http\Response;
//use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
//    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::ADMIN;

    protected function redirectPath(){
        return $this->redirectTo;
    }

    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    protected function create(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->except('password','password_confirmation','_token');

        $password = bcrypt($request->password);

        $data = array_merge($data,['password' => $password]);

//        Auth::guard('admin')->login($admin = Admin::create($data));
//
//        if ($response = $this->registered($request, $admin)) {
//            return $response;
//        }
//
//
//        return $request->wantsJson()
//            ? new Response('', 201)
//            : redirect($this->redirectPath());

        if(Admin::create($data)){
            return redirect($this->redirectPath());
        }else{
            return redirect()->back()->withInput($request->only('name','email'));
        }
    }
}

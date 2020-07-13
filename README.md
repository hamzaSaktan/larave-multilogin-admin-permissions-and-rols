<h2>1 - create laravel project:</h2>
	<p>laravel new laravel-multilogins-and-permissions --auth</p>
    
<h2>2 - create authontification for admin</h2>
	<h3>1 - get started:</h3>
        <ul>
        <li>create Admin model</li>
            <li>create admin migration </li>
            <li>add admin guard , provider , password broker in auth config file</li>
            <li>create admin.php route file and add admin route map function in RouteServiceProvider</li>
        </ul>
	<h3>2 - admin login:</h3>
        <ul>
            <li>create Admin/LoginController</li>
            <li>create Admin login route width guest middleware</li>
            <li>create Admin/Auth/login.blade.php</li>
            <li>create showLogin function in the LoginController controller and return the admin login view</li>
            <li>create login function in the LoginController with admin guard attempt to login and redirect intended on success or redirect back
            withinput 'email' on faild</li>
        </ul>
       
<h3>3 - admin dashboard</h3>
    <ul>
       <li>create Admin dashboard controller and views</li> 
       <li>create Admin dashboard routes with auth middleware</li> 
   </ul>
   	
<h3>4 - create Admin with tinker and test login </h3>

<h3>5  middlewares edit auth:admin and guest:admin redirections</h3>
    <ul>
        <li>middleware auth:admin redirect unauthontificated admin to the admin login page</li>
        <li>middleware guest:admin redirect authontificated admin to the dashborad </li>
    </ul>
        
Middleware/Authenticate.php

            protected function redirectTo($request)
            {
                if (! $request->expectsJson()) {
                    if(Route::is('admin.*')){
                        $login = 'admin.login';
                    }else{
                        $login = 'login';
                    }
                    return route($login);
                }
            }
            
Middleware/RedirectIfAuthenticated.php
        
            public function handle($request, Closure $next, $guard = null)
            {
                if (Auth::guard($guard)->check()) {
                    switch ($guard){
                        case 'admin':
                            return redirect(RouteServiceProvider::ADMIN);
                        default:
                            return redirect(RouteServiceProvider::HOME);
                    }
                }
        
                return $next($request);
            }

<h2>7- create reset password for admin</h2>

<ul>
<li>duplicate ForgotPasswordController and ResetPasswordController controllers from user to admin</li>
<li>add broker and guard functions in ForgotPasswordController and ResetPasswordController controllers</li>
<li>add showLinkRequestForm function in ForgotPasswordController and return form view</li>
<li>add showResetForm function in ResetPasswordController and return form view</li>
<li> duplicat reset password routes from user to admin</li>
<li>edit reset password email in Admin model by creating sendPasswordResetNotification and return notification AdminResetPasswordNotification</li>
</ul>

in Admin Model

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    
in terminal:

    php artisan make:notification AdminResetPasswordNotification


<h2>login with remember me:</h2>

    if($request->remember){
            $remember = true;
        }else{
            $remember = false;
       }

        if(Auth::guard('admin')->attempt($request->only('email','password'),$remember)){
            return Redirect::intended(route('admin.home'));
        }else{
            return Redirect::back()->withInput($request->only('email'));
        }


confirm email for admin optional 

install laratrust

use laratrust seeder

create admin dashboard

create adminController to create , read , update , delete admins

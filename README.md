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

in AdminResetPasswordNotification

        public $token;
        
        public function __construct($token)
        {
            $this->token = $token;
        }
        
        
        public function toMail($notifiable)
        {
            return (new MailMessage)
                        ->line('You are receiving this email because we received a password reset request for your account.')
                        ->action('Notification Action', route('admin.password.reset',$this->token))
                        ->line('This password reset link will expire in 15 minutes.')
                        ->line('If you did not request a password reset, no further action is required.');
        }
routes: 

    Route::namespace('Admin')->group(function (){

        Route::group(['middleware'=>['guest:admin']],function(){
            Route::get('/login','LoginController@showLogin')->name('admin.login');
            Route::post('/login','LoginController@login')->name('admin.login.submit');
    
            // password reset
            Route::post('password/email','ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
            Route::get('password/reset','ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
            Route::post('password/reset','ResetPasswordController@reset')->name('admin.password.update');
            Route::get('password/reset/{token}','ResetPasswordController@showResetForm')->name('admin.password.reset');
        });     
    });   
    
<h2>8 - login with remember me:</h2>

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


<h2>9 - confirm email for admin and user </h2>

app/Admin.php and app/User.php  implements MustVerifyEmail

	use Illuminate\Contracts\Auth\MustVerifyEmail;
	class User extends Authenticatable implements MustVerifyEmail
	{ //

route/web.php

	Auth::routes(['verify' => true]);

create verified-admin middleware

add verified-admin middleware in kendel.php 

	'verified-admin' => \App\Http\Middleware\AdminEnsureEmailIsVerified::class,


app\Http\Middleware

	namespace App\Http\Middleware;

	use Closure;
	use Illuminate\Contracts\Auth\MustVerifyEmail;
	use Illuminate\Support\Facades\Redirect;

	class AdminEnsureEmailIsVerified
	{

    		public function handle($request, Closure $next, $redirectToRoute = null)
    		{
        		if (! $request->user('admin') ||
            		($request->user('admin') instanceof MustVerifyEmail &&
             		! $request->user('admin')->hasVerifiedEmail())) {
            			return $request->expectsJson()
                		? abort(403, 'Your email address is not verified.')
                		: Redirect::route($redirectToRoute ?: 'admin.verification.notice');
        		}

        		return $next($request);
    		}
	} 
Admin/VerificationController
 
    namespace App\Http\Controllers\Admin;
    
    use App\Http\Controllers\Controller;
    use App\Providers\RouteServiceProvider;
    use Illuminate\Auth\Access\AuthorizationException;
    use Illuminate\Auth\Events\Verified;
    use Illuminate\Foundation\Auth\VerifiesEmails;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Auth;
    
    class VerificationController extends Controller
    {

    use VerifiesEmails;

    protected $redirectTo = RouteServiceProvider::ADMIN;

    protected function redirectPath(){
        return $this->redirectTo;
    }

    public function __construct()
    {
       $this->middleware('signed')->only('verify');
       $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        return $request->user('admin')->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('admin.auth.verify');
    }

    public function resend(Request $request)
    {
        if ($request->user('admin')->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($this->redirectPath());
        }

        $request->user('admin')->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new Response('', 202)
            : back()->with('resent', true);
    }

    public function verify(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user('admin')->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user('admin')->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($request->user('admin')->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($this->redirectPath());
        }

        if ($request->user('admin')->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($this->redirectPath())->with('verified', true);
    }

    protected function guard()
        {
            return Auth::guard('admin');
        }
    } 
    
Admin/RegisterController

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


Routes: 


    Route::namespace('Admin')->group(function (){
    
    
        Route::group(['middleware' => ['auth:admin','verified-admin']],function(){
    
            Route::get('/register','RegisterController@showRegistrationForm')->name('admin.register');
            Route::post('/register','RegisterController@create')->name('admin.register');
    
            Route::get('/','HomeController@index')->name('admin.home');
            Route::get('/home','HomeController@index')->name('admin.home');
            Route::post('/logout','LoginController@adminLogout')->name('admin.logout');
    
        });
    
    
        Route::group(['middleware' => ['auth:admin']],function(){
    
            // email verification
            Route::post('email/resend','VerificationController@resend')->name('admin.verification.resend');
            Route::get('email/verify','VerificationController@show')->name('admin.verification.notice');
            Route::get('email/verify/{id}/{hash}','VerificationController@verify')->name('admin.verification.verify');
    
        });
    
    }
 
App\Admin
    
    use App\Notifications\AdminSendEmailVerificationNotification;
    
    public function SendEmailVerificationNotification(){
        $this->notify(new AdminSendEmailVerificationNotification());
    }
    
Notifications/AdminSendEmailVerificationNotification 

    namespace App\Notifications;
    
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Lang;
    use Illuminate\Support\Facades\URL;
    
    class AdminSendEmailVerificationNotification extends Notification
    {
    
        public static $toMailCallback;
    
        public function via($notifiable)
        {
            return ['mail'];
        }
    
        public function toMail($notifiable)
        {
            $verificationUrl = $this->verificationUrl($notifiable);
    
            if (static::$toMailCallback) {
                return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
            }
    
            return (new MailMessage)
                ->subject(Lang::get('Verify Email Address'))
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $verificationUrl)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
        }
    
        protected function verificationUrl($notifiable)
        {
            return URL::temporarySignedRoute(
                'admin.verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        }
    
        public static function toMailUsing($callback)
        {
            static::$toMailCallback = $callback;
        }
    }


install laratrust

use laratrust seeder

create admin dashboard

create adminController to create , read , update , delete admins

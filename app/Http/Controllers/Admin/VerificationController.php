<?php

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

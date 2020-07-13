<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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

    Route::group(['middleware' => ['auth:admin']],function(){
        Route::get('/','HomeController@index')->name('admin.home');
        Route::get('/home','HomeController@index')->name('admin.home');
        Route::post('/logout','LoginController@adminLogout')->name('admin.logout');
    });

});

// POST     | password/email         | password.email     | ForgotPasswordController@sendResetLinkEmail
//
// GET|HEAD | password/reset         | password.request   | ForgotPasswordController@showLinkRequestForm
//
// POST     | password/reset         | password.update    | ResetPasswordController@reset
//
// GET|HEAD | password/reset/{token} | password.reset     | ResetPasswordController@showResetForm

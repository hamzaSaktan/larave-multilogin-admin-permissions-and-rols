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
    });

    Route::group(['middleware' => ['auth:admin']],function(){
        Route::get('/','HomeController@index')->name('admin.home');
        Route::get('/home','HomeController@index')->name('admin.home');
        Route::post('/logout','LoginController@adminLogout')->name('admin.logout');
    });

});

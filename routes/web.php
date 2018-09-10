<?php

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


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return Redirect::to('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/history','ShowLoginHistoryController@index')->name('show_history');

Route::get('/user/modify','ModifyUserInformationController@showModifyUserInformationFrom')->name('modify_user_information_from');
Route::post('/user/modify','ModifyUserInformationController@modifyUserInformation')->name('modify_user_information');

Route::get('/user/delete','DeleteUserController@showDeleteUserFrom')->name('delete_user_from');
Route::post('/user/delete','DeleteUserController@deleteUser')->name('delete_user');

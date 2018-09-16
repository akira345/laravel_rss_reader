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
Route::get('/home/read/{rss_data}','HomeController@read')->name('home.read');

Route::get('/user/history','ShowLoginHistoryController@index')->name('show_history');

Route::get('/user/modify','ModifyUserInformationController@showModifyUserInformationFrom')->name('modify_user_information_from');
Route::post('/user/modify','ModifyUserInformationController@modifyUserInformation')->name('modify_user_information');

Route::get('/user/delete','DeleteUserController@showDeleteUserFrom')->name('delete_user_from');
Route::post('/user/delete','DeleteUserController@deleteUser')->name('delete_user');

Route::get('/category','CategoryController@index')->name('category.index');
Route::get('/category/create','CategoryController@create')->name('category.create');
Route::post('/category','CategoryController@store')->name('category.store');
Route::get('/category/{category}','CategoryController@show')->name('category.show');
Route::get('/category/{category}/edit','CategoryController@edit')->name('category.edit');
Route::put('/category/{category}','CategoryController@update')->name('category.update');
Route::delete('/category/{category}','CategoryController@destroy')->name('category.destroy');

Route::get('/rss','RssDataController@index')->name('rss_data.index');
Route::get('/rss/create','RssDataController@create')->name('rss_data.create');
Route::post('/rss','RssDataController@store')->name('rss_data.store');
Route::get('/rss/{rss_data}','RssDataController@show')->name('rss_data.show');
Route::get('/rss/{rss_data}/edit','RssDataController@edit')->name('rss_data.edit');
Route::put('/rss/{rss_data}','RssDataController@update')->name('rss_data.update');
Route::delete('/rss/{rss_data}','RssDataController@destroy')->name('rss_data.destroy');



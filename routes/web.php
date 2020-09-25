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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowLoginHistoryController;
use App\Http\Controllers\ModifyUserInformationController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RssDataController;


Route::get('/', function () {
    return Redirect::to('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/read/{rss_data}', [HomeController::class, 'read'])->name('home.read');

Route::get('/user/history', [ShowLoginHistoryController::class, 'index'])->name('show_history');

Route::get('/user/modify', [ModifyUserInformationController::class, 'showModifyUserInformationFrom'])->name('modify_user_information_from');
Route::post('/user/modify', [ModifyUserInformationController::class, 'modifyUserInformation'])->name('modify_user_information');

Route::get('/user/delete', [DeleteUserController::class, 'showDeleteUserFrom'])->name('delete_user_from');
Route::post('/user/delete', [DeleteUserController::class, 'deleteUser'])->name('delete_user');

Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/rss', [RssDataController::class, 'index'])->name('rss_data.index');
Route::get('/rss/create', [RssDataController::class, 'create'])->name('rss_data.create');
Route::post('/rss', [RssDataController::class, 'store'])->name('rss_data.store');
Route::get('/rss/{rss_data}', [RssDataController::class, 'show'])->name('rss_data.show');
Route::get('/rss/{rss_data}/edit', [RssDataController::class, 'edit'])->name('rss_data.edit');
Route::put('/rss/{rss_data}', [RssDataController::class, 'update'])->name('rss_data.update');
Route::delete('/rss/{rss_data}', [RssDataController::class, 'destroy'])->name('rss_data.destroy');

Route::get('/logout', function () {
    return Redirect::to('/');
});

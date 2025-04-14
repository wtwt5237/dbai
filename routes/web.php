<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\UserAuthController;

/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
*/
Route::get('/', [Controller::class, 'index']);

/*
|-----------------------------------Route::get('/', function () {
    return view('welcome');
});---------------------------------------
| Other Pages
|--------------------------------------------------------------------------
*/
Route::view('/tools', 'tools/tools');
//Route::view('/team', 'team');
//Route::view('/about', 'about');

/*
|--------------------------------------------------------------------------
| User Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('login', [UserAuthController::class, 'login'])->name('login');
Route::post('post-login', [UserAuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [UserAuthController::class, 'registration'])->name('register');
Route::get('user-management', [UserAuthController::class, 'userManagement']);
Route::get('logout', [UserAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Info Routes
|--------------------------------------------------------------------------
*/
Route::get('/all-users', [UserAuthController::class, 'allUsers']);
Route::get('/cur-user', [UserAuthController::class, 'curUser']);
Route::post('/add-user', [UserAuthController::class, 'addUser'])->name('addUser');
Route::post('/delete-user/{user_id}', [UserAuthController::class, 'deleteUser']);
Route::post('/update-user/{user_id}', [UserAuthController::class, 'updateUser']);

Route::get('/user-profile', [UserAuthController::class, 'userProfile']);

/*
|--------------------------------------------------------------------------
| Data Tables Routes
|--------------------------------------------------------------------------
*/
Route::get('/database/{db_name?}', [DatabaseController::class, 'database']);
Route::get('/fetch/{db_name}', [DatabaseController::class, 'fetchTable']);
Route::get('/download-file/{db_name}/{id}/{f_name}', [DatabaseController::class, 'fileDownload'])->name('download-file');
Route::get('/download-db/{db_version}', [DatabaseController::class, 'dbDownload'])->name('download-db');;


Route::get('/history', [HistoryController::class, 'fetchHistory']);


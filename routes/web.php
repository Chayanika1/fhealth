<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\GroupController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest:web'])->group(function(){
        Route::view('/login', 'dashboard.user.login')->name('login');
        Route::view('/register', 'dashboard.user.register')->name('register');
        Route::any('/create-user', [UserController::class, 'create_user'])->name('create-user');
        Route::any('/dologin',[UserController::class, 'dologin'])->name('dologin');
    });
    Route::middleware(['auth:web'])->group(function(){
        //Route::view('/home', 'dashboard.user.home')->name('home');
        Route::post('/searchkeyword', [UserController::class, 'searchkeyword'])->name('searchkeyword');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        Route::any('/home', [UserController::class, 'homefeed'])->name('home');
        Route::post('/storeimage', [UserController::class, 'storeimage'])->name('storeimage');
        Route::post('/upload_post', [UserController::class, 'upload_post'])->name('upload_post');
        Route::get('/postcontents', [UserController::class, 'getArticles']);
        Route::post('/postLike', [UserController::class, 'postLike'])->name('postLike');
        Route::post('/postComment', [UserController::class, 'postComment'])->name('postComment');
        Route::any('/article/{id}', [UserController::class, 'article'])->name('article');
        Route::any('/group-create', [GroupController::class, 'group_create'])->name('group-create');
        Route::any('/group/{id}', [GroupController::class, 'groupfeed'])->name('group');
        Route::get('/groupcontents', [GroupController::class, 'getgroupArticles']);
        Route::any('/sendJoinGroupRequest', [GroupController::class, 'sendJoinGroupRequest'])->name('sendJoinGroupRequest');
    });
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:admin'])->group(function(){
        Route::view('/login', 'dashboard.admin.login')->name('login');
        Route::any('/dologin',[AdminController::class, 'dologin'])->name('dologin');
    });
    Route::middleware(['auth:admin'])->group(function(){
        Route::view('/home', 'dashboard.admin.home')->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});
<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuestAdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function(){
    
    Route::middleware([GuestAdminMiddleware::class])->group(function(){
        Route::get('/login', [AdminLoginController::class, 'index']);
        Route::post('/login', [AdminLoginController::class, 'login'])->name
        ('login');
    });
    
    // Route::middleware(['auth', 'is_admin'])->group(function () {
    //     Route::post('/admin/update-role/{id}', [AdminController::class, 'updateRole']);
    //     Route::post('/admin/deactivate-user/{id}', [AdminController::class, 'deactivateUser']);
    // });
    
    Route::middleware(['auth', AdminMiddleware::class])->group(function(){
        Route::get('/home', [AdminHomeController::class, 'index'])->name('home');
    });


    
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
->name('home')->middleware(UserMiddleware::class);


// Route::get('/admin/users', [UserController::class, 'index']);
// Route::get('/admin/get-users', [UserController::class, 'getUsers'])->name('admin.getUsers');

// Route::view('/admin/get-users', 'customers.index')->name('admin.getUsers');

// Route::get('/admin/get-users', [UserController::class, 'getUsers'])->name('admin.getUsers');

?>
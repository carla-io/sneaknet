<?php

use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ShipperController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\CartController;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuestAdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function() {
    Route::middleware([GuestAdminMiddleware::class])->group(function() {
        Route::get('/login', [AdminLoginController::class, 'index']);
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
    });

    Route::middleware(['auth', AdminMiddleware::class])->group(function() {

        Route::controller(DashboardController::class)->group(function(){
            Route::get('/dashboard', 'Index')->name('home');
        });

        Route::controller(UserController::class)->group(function(){
            Route::get('/customers', 'Index')->name('customers');
            
        });

        Route::controller(ProductController::class)->group(function(){
            Route::get('/product', 'Index')->name('product');
            // Route::post('/product/import', 'import')->name('product.import');
        });

        Route::controller(CategoryController::class)->group(function(){
            Route::get('/category', 'index')->name('category');
           
        });

        Route::controller(OrderController::class)->group(function(){
            Route::get('/order', 'Index')->name('order');
        });

        Route::controller(SupplierController::class)->group(function(){
            Route::get('/supplier', 'Index')->name('supplier');
        });

        Route::controller(ShipperController::class)->group(function(){
            Route::get('/shipper', 'Index')->name('shipper');
        });

    });

   
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Auth routes generated by Laravel's Auth::routes()
Auth::routes();

// Home route for regular users with middleware
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')->middleware(UserMiddleware::class);
?>
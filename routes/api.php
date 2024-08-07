<?php

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
// header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ShipperController;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SalesController;
use App\Models\User;
use App\Http\Controllers\CheckoutController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:api')->get('/generate-token', function () {
//     $user = auth()->user(); // Get the authenticated user
//     $token = $user->createToken('YourAppTokenName')->accessToken;
//     return response()->json(['token' => $token]);
// });


// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('orders', [OrderController::class, 'store']);
//     // Other API routes...
// });

Route::get('users', [UserController::class, 'index']);
Route::post('update-users', [UserController::class, 'updateRole']);
Route::post('deactivate-users', [UserController::class, 'deactivateUser']);

Route::get('products', [ProductController::class, 'index']);
Route::post('create-product', [ProductController::class, 'create']);
Route::post('update-product', [ProductController::class, 'update']);
Route::delete('delete-product', [ProductController::class, 'delete']);
Route::post('import-products', [ProductController::class, 'importProducts']);
Route::get('products/{id}', [ProductController::class, 'show']);


Route::get('category', [CategoryController::class, 'index']);
Route::post('create-category', [CategoryController::class, 'create']);
Route::post('update-category', [CategoryController::class, 'update']);
Route::delete('delete-category', [CategoryController::class, 'delete']);

Route::get('supplier', [SupplierController::class, 'index']);
Route::post('create-supplier', [SupplierController::class, 'create']);
Route::post('update-supplier', [SupplierController::class, 'update']);
Route::delete('delete-supplier', [SupplierController::class, 'delete']);

Route::get('shipper', [ShipperController::class, 'index']);
Route::post('create-shipper', [ShipperController::class, 'create']);
Route::post('update-shipper', [ShipperController::class, 'update']);
Route::delete('delete-shipper', [ShipperController::class, 'delete']);

Route::get('search', [SearchController::class, 'search']);

// Route::middleware('auth:sanctum')->post('create-orders', [OrderController::class, 'store']);

Route::middleware('auth:sanctum')->post('create-orders', [OrderController::class, 'store']);
Route::middleware('auth:sanctum')->get('orders', [OrderController::class, 'index']);

Route::post('cart-add', [CartController::class, 'addToCart'])->middleware('auth:sanctum');
Route::get('cart-items', [CartController::class, 'getCartItems'])->middleware('auth:sanctum');
Route::post('checkout', [OrderController::class, 'checkout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('sales', [SalesController::class, 'index']);
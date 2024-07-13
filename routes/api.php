<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');

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

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('users', [UserController::class, 'index']);
Route::post('update-users', [UserController::class, 'updateRole']);
Route::post('deactivate-users', [UserController::class, 'deactivateUser']);

Route::get('products', [ProductController::class, 'index']);
Route::post('create-product', [ProductController::class, 'create']);
Route::post('update-product', [ProductController::class, 'update']);
Route::delete('delete-product', [ProductController::class, 'delete']);
Route::post('import-products', [ProductController::class, 'importProducts']);


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
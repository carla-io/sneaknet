<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('products', [ProductController::class, 'index']);
Route::post('create-product', [ProductController::class, 'create']);
Route::post('update-product', [ProductController::class, 'update']);
// Route::delete('delete-product/{id}', [ProductController::class, 'delete']);
Route::delete('delete-product', [ProductController::class, 'delete']);


Route::get('category', [CategoryController::class, 'index']);
Route::post('create-category', [CategoryController::class, 'create']);
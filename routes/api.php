<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileFormController;
use App\Http\Controllers\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/register",[AuthController::class, 'register']);
Route::post("/login",[AuthController::class, 'login']);
Route::post("/logout",[AuthController::class, 'logout'])->middleware('auth:api');
Route::get("/refresh_token",[AuthController::class, 'refresh'])->middleware('auth:api');


// Route::controller(AuthController::class)->group(function() {
//     Route::post('/register', 'register');
//     Route::post('/login', 'login');
//     Route::post('/logout', 'logout');
//     Route::get('/refresh_token', 'refresh');

// });


// Profile Form API
Route::get('/profiles-form', [ProfileFormController::class, 'index']);
Route::get('/profiles-form/{id}', [ProfileFormController::class, 'show']);
Route::post('/profiles-form',[ProfileFormController::class, 'store']);
Route::put('/profiles-form/{id}',[ProfileFormController::class, 'update']);
Route::delete('/profiles-form/{id}',[ProfileFormController::class, 'destroy']);

// Prodouct List API
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Category List API
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

// Unit List API
Route::get('/units', [UnitController::class, 'index']);
Route::post('/units', [UnitController::class, 'store']);
Route::put('/units/{id}', [UnitController::class, 'update']);
Route::delete('/units/{id}', [UnitController::class, 'destroy']);





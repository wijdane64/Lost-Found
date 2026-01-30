<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;


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
// Routes for UserController
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Routes protected by sanctum middleware
 
// Profile user connecté
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
// Routes for ItemController
    Route::get('/users', [UserController::class, 'index']);       
    Route::post('/users', [UserController::class, 'store']);      
    Route::put('/users/{id}', [UserController::class, 'update']); 
    Route::delete('/users/{id}', [UserController::class, 'destroy']); 
    
    // Routes for ItemController
    Route::get('/items', [ItemController::class, 'index']);       
    Route::post('/items', [ItemController::class, 'store']);      
    Route::get('/items/{id}', [ItemController::class, 'show']);   
    Route::put('/items/{id}', [ItemController::class, 'update']); 
    Route::delete('/items/{id}', [ItemController::class, 'destroy']); 

    // Route to get items of the authenticated user
     Route::get('/my-items', [ItemController::class, 'myItems']);


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;




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
// Routes pour UserController
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    
    Route::post('/logout', [AuthController::class, 'logout']);


// Utilisateur du profil connecté
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
// Routes pour UserController
    Route::get('/users', [UserController::class, 'index']);       
    Route::post('/users', [UserController::class, 'store']);      
    Route::put('/users/{id}', [UserController::class, 'update']); 
    Route::delete('/users/{id}', [UserController::class, 'destroy']); 
    
    // Routes pour ItemController
    Route::get('/items', [ItemController::class, 'index']);       
    Route::post('/items', [ItemController::class, 'store']);        
    Route::put('/items/{id}', [ItemController::class, 'update']); 
    Route::delete('/items/{id}', [ItemController::class, 'destroy']); 

    // Itinéraire pour obtenir les éléments de l'utilisateur authentifié
     Route::get('/my-items', [ItemController::class, 'myItems']);
     });

     //Route:get('/dashbord',[ItemController::class, 'dashbord']);
      


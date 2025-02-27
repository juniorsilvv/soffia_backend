<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;



Route::prefix('api')->group(function(){
    Route::prefix('auth')->group(function(){
        // Route::post('/login', [AuthController::class, 'login']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });


    Route::middleware(AuthMiddleware::class)->group(function(){
        Route::get('logout', [AuthController::class, 'logout']);
        
        // USERS

        Route::prefix('users')->group(function(){
            Route::get('/', [UserController::class, 'users']);
            Route::post('create', [UserController::class, 'create']);
            route::put('update/{id}', [UserController::class, 'update']);
        });
    });
});


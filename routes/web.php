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
        Route::get('users', [UserController::class, 'users']);
    });
});


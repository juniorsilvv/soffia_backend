<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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

        Route::prefix('users')->controller(UserController::class)->group(function(){
            Route::get('/', 'users');
            Route::post('create', 'create');
            route::put('update/{id}', 'update');
            Route::delete('delete/{id}', 'delete');
        });


        Route::prefix('posts')->controller(PostController::class)->group(function(){
            Route::get('/', 'posts');
            Route::post('create', 'create');
            Route::delete('delete/{id}', 'delete');
        });
    });
});


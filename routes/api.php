<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenRoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/users')->name('')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('users.register');
    Route::post('login', [AuthController::class, 'login'])->name('users.login');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
        ->name('users.password.request');
    Route::post('/reset-password', [AuthController::class, 'reset'])
        ->name('users.password.reset');

    Route::middleware(['auth:sanctum', TokenRoleMiddleware::class . ':VISITOR,ADMIN'])
        ->group(function(){
            Route::get('connected', [AuthController::class, 'connected'])->name('users.connected');
            Route::delete('logout', [AuthController::class, 'logout'])->name('users.logout');
        });
    
        Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware(['signed'])
        ->name('users.verification.verify');
        Route::post('email/verification-notification', [AuthController::class, 'resend'])
        ->middleware(['throttle:5,60'])
        ->name('users.verification.send');

        Route::put('/',[UserController::class,'update'])->name('users.update');
        Route::delete('/',[UserController::class,'destroy'])->name('users.destroy');
    });

    Route::get('categories',[CategoryController::class,'index'])->name('categories.index');
    Route::get('categories/{category}',[CategoryController::class,'show'])->name('categories.show');

    Route::get('tags',[TagController::class,'index'])->name('tags.index');
    Route::get('tags/{tag}',[TagController::class,'show'])->name('tags.show');

    Route::get('file/{file}',[TagController::class,'show'])->name('files.show');
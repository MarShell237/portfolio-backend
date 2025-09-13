<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenRoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/users')->name('users.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
        ->name('password.request');
    Route::post('/reset-password', [AuthController::class, 'reset'])
        ->name('password.reset');

    Route::middleware(['auth:sanctum', TokenRoleMiddleware::class . ':VISITOR,ADMIN'])
        ->group(function(){
            Route::get('connected', [AuthController::class, 'connected'])->name('connected');
            Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
        });
    
        Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');
        Route::post('email/verification-notification', [AuthController::class, 'resend'])
        ->middleware(['throttle:5,60'])
        ->name('verification.send');

        Route::put('/',[UserController::class,'update'])->name('update');
        Route::delete('/',[UserController::class,'destroy'])->name('destroy');
});
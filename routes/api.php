<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenRoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/users')->name('user.')->group(function () {
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
});
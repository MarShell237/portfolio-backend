<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenRoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('')->group(function () {
    Route::get('users/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware(['signed'])
        ->name('users.verification.verify');
    Route::post('users/email/verification-notification', [AuthController::class, 'resend'])
        ->middleware(['throttle:5,60'])
        ->name('users.verification.send');

    Route::middleware(['auth:sanctum', TokenRoleMiddleware::class . ':VISITOR,ADMIN'])->group(function(){
        Route::get('users/connected', [AuthController::class, 'connected'])->name('users.connected');
        Route::delete('users/logout', [AuthController::class, 'logout'])->name('users.logout');

        Route::put('users',[UserController::class,'update'])->name('users.update');
        Route::delete('users',[UserController::class,'destroy'])->name('users.destroy');

        // like routes
        Route::prefix('likes')->controller(LikeController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('{likeableType}/{likeableId}', 'likeOrUnlike');
        });

        // share routes
        Route::prefix('shares')->controller(ShareController::class)->group(function () {
            Route::post('{shareableType}/{shareableId}/{platform}', 'share');
        });

        Route::prefix('comments')->group(function () {
            Route::post('{commentableType}/{commentableId}', [CommentController::class, 'store']);
            Route::put('{comment}', [CommentController::class, 'update']);
            Route::delete('{comment}', [CommentController::class, 'destroy']);
        });
    });

    Route::post('users/register', [AuthController::class, 'register'])->name('users.register');
    Route::post('users/login', [AuthController::class, 'login'])->name('users.login');
    Route::post('users/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
        ->name('users.password.request');
    Route::post('users/reset-password', [AuthController::class, 'reset'])
        ->name('users.password.reset');
    Route::get('users/{user}',[UserController::class,'show'])->name('users.show');

    Route::get('categories',[CategoryController::class,'index'])->name('categories.index');
    Route::get('categories/{category}',[CategoryController::class,'show'])->name('categories.show');

    Route::get('tags',[TagController::class,'index'])->name('tags.index');
    Route::get('tags/{tag}',[TagController::class,'show'])->name('tags.show');

    Route::get('links',[LinkController::class,'index'])->name('links.index');
    Route::get('links/{link}',[LinkController::class,'show'])->name('links.show');

    Route::get('projects',[ProjectController::class,'index'])->name('projects.index');
    Route::get('projects/{project}',[ProjectController::class,'show'])->name('projects.show');

    Route::get('posts',[PostController::class,'index'])->name('posts.index');
    Route::get('posts/{post}',[PostController::class,'show'])->name('posts.show');

    Route::get('file/{file}',[FileController::class,'show'])->name('files.show');

    // comment routes
    Route::prefix('comments')->group(function () {
        Route::get('{commentableType}/{commentableId}', [CommentController::class, 'index'])->name('comments.index');
        Route::get('{comment}', [CommentController::class, 'show'])->name('comments.show');
    });

        Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::get('{notification_id}', 'show')->name('notifications.show');
        Route::patch('{notification_id}/read', 'markAsRead')->name('notifications.markAsRead');
        Route::patch('read/all', 'markAllAsRead')->name('notifications.markAllAsRead');
        Route::delete('{notification_id}', 'destroy')->name('notifications.destroy');
        Route::delete('read/clear', 'clearRead')->name('notifications.clearRead');
        Route::get('unread/count', 'unreadCount')->name('notifications.unreadCount');
    });
});

Route::get('/cache-test', function () {
    sleep(5);
    return response()->json([
        'now' => now()->toDateTimeString(),
        'random' => rand(1000, 9999),
    ]);
});

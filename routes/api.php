<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\VoteController;
use App\Http\Controllers\Api\V1\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'v1'], function() {
        Route::group(['prefix' => 'auth'], function() {
            Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
        });
    });
});

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('login', [LoginController::class, 'login'])->name('auth.login');
        Route::post('register', [RegisterController::class, 'register'])->name('auth.register');
    });

    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/{id}', [PostController::class, 'show'])->name('posts.show');

    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::apiResource('posts', PostController::class)->except(['edit', 'index', 'show']);
        Route::apiResource('comments', CommentController::class)->except(['index', 'show', 'edit']);

        Route::post('votes/{post}/up', [VoteController::class, 'upVote'])->name('votes.up');
        Route::post('votes/{post}/down', [VoteController::class, 'downVote'])->name('votes.down');
    });
});

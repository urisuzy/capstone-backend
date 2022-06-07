<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TourismController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // User
    Route::prefix('users')->group(function () {
        Route::get('me', [UserController::class, 'me']);
        Route::post('profile', [UserController::class, 'updateImageProfile']);
        Route::put('/', [UserController::class, 'updateUserInfo']);
    });

    // Category
    Route::resource('categories', CategoryController::class);

    // Tourism
    Route::prefix('tourisms')->group(function () {
        Route::post('/', [TourismController::class, 'store']);
        Route::get('/', [TourismController::class, 'list']);
        Route::get('/favorites', [TourismController::class, 'favorited']);
        Route::get('/{id}', [TourismController::class, 'show']);
        Route::delete('/{id}', [TourismController::class, 'delete']);
    });

    // Interest
    Route::prefix('interests')->group(function () {
        Route::post('/', [InterestController::class, 'add']);
        Route::get('/', [InterestController::class, 'list']);
        Route::put('/', [InterestController::class, 'update']);
    });

    // Review
    Route::prefix('reviews')->group(function () {
        Route::post('/', [ReviewController::class, 'add']);
        Route::get('/', [ReviewController::class, 'list']);
        Route::delete('/{id}', [ReviewController::class, 'delete']);
    });
});

Route::get('/unaunthenticated', [AuthController::class, 'unauthenticated'])->name('login');

<?php

use App\Http\Controllers\AuthController;
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
});

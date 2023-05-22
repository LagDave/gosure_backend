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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::controller(AuthController::class)->prefix('auth')->group(function() {

    Route::get('getGoogleRedirectURL', 'getGoogleRedirectURL');
    Route::get('google/loginUser', 'loginUser');
    Route::middleware('auth:sanctum')->get('logoutUser', [AuthController::class, 'logoutUser']);

});

Route::middleware('auth:sanctum')->controller(UserController::class)->prefix('user')->group(function() {

    Route::get('getActiveUser', 'getActiveUser');

});

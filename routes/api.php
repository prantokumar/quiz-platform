<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LeaderboardApiController;
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

/* public api's */
Route::post('/login', [AuthApiController::class, 'adminUserLogin']);
/* public api's */

/* authenticate api */
Route::any('/logout', [AuthApiController::class, 'adminUserLogout']);
Route::any('/leader-board', [LeaderboardApiController::class, 'getUserLeaderboard']);
/* authenticate api */

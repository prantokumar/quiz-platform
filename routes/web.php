<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Dashboard\DashboardController;
use App\Http\Controllers\Frontend\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.pages.auth.login');
});

Auth::routes();

/* user login routes */
Route::get('/', [LoginController::class, 'userLoginPage'])->name('userlogin');
Route::get('/login', [LoginController::class, 'userLoginPage'])->name('userlogin');
Route::post('/post/login', [LoginController::class, 'userLogin']);
/* user login routes */
/* user logout */
Route::get('/logout', [LogoutController::class, 'logout']);
/* user logout */

Route::group(['middleware' => ['auth']], function () {
    /* view user dashboard page */
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('userDashboard');
    /* view user dashboard page */
});

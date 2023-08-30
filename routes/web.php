<?php

use App\Http\Controllers\Frontend\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Dashboard\DashboardController;
use App\Http\Controllers\Frontend\Auth\LogoutController;
use App\Http\Controllers\Frontend\Auth\ProfileUpdateController;
use App\Http\Controllers\Frontend\Auth\UserRegistrationController;

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
Route::post('/user/login', [LoginController::class, 'userLogin']);
/* user login routes */
/* user registration routes */
Route::get('/register', [UserRegistrationController::class, 'register'])->name('userRegister');
Route::post('/save-new-user', [UserRegistrationController::class, 'saveNewUser']);
/* user registration routes */

/* user logout */
Route::get('/logout', [LogoutController::class, 'logout']);
/* user logout */

Route::group(['middleware' => ['auth']], function () {
    /* user update profile page */
    Route::get('/update-profile', [ProfileUpdateController::class, 'userProfileUpdate'])->name('userProfileUpdate');
    Route::post('/save-profile-data', [ProfileUpdateController::class, 'userProfileUpdateSave'])->name('userProfileUpdateSave');
    /* user update profile page */
    /* user password update */
    Route::get('/change-password', [ChangePasswordController::class, 'userUpdatePassword'])->name('userUpdatePassword');
    Route::post('/update-password-save', [ChangePasswordController::class, 'userUpdatePasswordSave'])->name('userUpdatePasswordSave');
        /* user password update */
    /* view user dashboard page */
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('userDashboard');
    /* view user dashboard page */
});
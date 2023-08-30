<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Backend\Auth\LogoutController;

Route::group(['prefix' => 'admin'], function () {

    /* admin login routes */
    Route::get('/', [LoginController::class, 'adminLoginPage'])->name('adminlogin');
    Route::get('/login', [LoginController::class, 'adminLoginPage'])->name('adminlogin');
    Route::post('/post/login', [LoginController::class, 'adminLogin']);
    /* admin login routes */
    /* admin logout */
    Route::get('/logout', [LogoutController::class, 'logout']);
    /* admin logout */

    Route::group(['middleware' => ['auth']], function () {
        /* view admin dashboard page */
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('adminDashboard');
        /* view admin dashboard page */
    });
});

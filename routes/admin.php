<?php

use App\Http\Controllers\Backend\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Backend\Auth\LogoutController;
use App\Http\Controllers\Backend\Auth\ProfileUpdateController;
use App\Http\Controllers\Backend\Exam\ExamController;
use App\Http\Controllers\Backend\Users\UserController;

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
        /* admin update profile page */
        Route::get('/update-profile', [ProfileUpdateController::class, 'adminProfileUpdate'])->name('adminProfileUpdate');
        Route::post('/save-profile-data', [ProfileUpdateController::class, 'adminProfileUpdateSave'])->name('adminProfileUpdateSave');
        /* admin update profile page */
        /* admin password update */
        Route::get('/change-password', [ChangePasswordController::class, 'adminUpdatePassword'])->name('adminUpdatePassword');
        Route::post('/update-password-save', [ChangePasswordController::class, 'adminUpdatePasswordSave'])->name('adminUpdatePasswordSave');
        /* admin password update */
        /* view admin dashboard page */
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('adminDashboard');
        /* view admin dashboard page */
        /* view registered users */
        Route::get('/users', [UserController::class, 'getAllRegistedUsers'])->name('getAllRegistedUsers');
        /* view registered users */

        /* exam module */
        Route::get('/exams', [ExamController::class, 'getExams'])->name('getExams');
        Route::any('/show-exams', [ExamController::class, 'showExams'])->name('showExams');
        Route::post('/save-exam', [ExamController::class, 'saveExam'])->name('saveExam');
        Route::post('/get-exam-data', [ExamController::class, 'getExamDataWithExamId'])->name('getExamDataWithExamId');
        Route::post('/update-exam', [ExamController::class, 'updateExam'])->name('updateExam');
        Route::post('/delete-exam', [ExamController::class, 'deleteExam'])->name('deleteExam');
        Route::post('/add-question-to-exam', [ExamController::class, 'addQuestionToExam'])->name('addQuestionToExam');
        Route::post('/view-exam-questions', [ExamController::class, 'viewExamQuestions'])->name('viewExamQuestions');
        Route::post('/update-exam-questions', [ExamController::class, 'updateExamQuestions'])->name('updateExamQuestions');
        /* exam module */
    });
});

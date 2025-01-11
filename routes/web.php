<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SchoolGradeController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewStudentController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [WelcomeController::class, 'index']);

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    // 学生リソースルートを追加
    Route::get('/students', [NewStudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [NewStudentController::class, 'create'])->name('students.create');
    Route::post('/students', [NewStudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [NewStudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [NewStudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [NewStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [NewStudentController::class, 'destroy'])->name('students.destroy');

    // 成績リソースルートを追加
    Route::get('/schoolGrades', [SchoolGradeController::class, 'index'])->name('schoolGrades.index');
    Route::get('/schoolGrades/create', [SchoolGradeController::class, 'create'])->name('schoolGrades.create');
    Route::post('/schoolGrades', [SchoolGradeController::class, 'store'])->name('schoolGrades.store');
    Route::get('/schoolGrades/{schoolGrade}', [SchoolGradeController::class, 'show'])->name('schoolGrades.show');
    Route::get('/schoolGrades/{schoolGrade}/edit', [SchoolGradeController::class, 'edit'])->name('schoolGrades.edit');
    Route::put('/schoolGrades/{schoolGrade}', [SchoolGradeController::class, 'update'])->name('schoolGrades.update');
    Route::delete('/schoolGrades/{schoolGrade}', [SchoolGradeController::class, 'destroy'])->name('schoolGrades.destroy');

    // 学年更新ルートの追加
    Route::get('/schoolGrades/update-grades', [SchoolGradeController::class, 'updateGrades'])->name('schoolGrades.update-grades');
});

// Password reset routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset']);

// LoginControllerのルートを追加
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// RegisterControllerのルートを追加
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);



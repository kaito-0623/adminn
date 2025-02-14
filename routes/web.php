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
use App\Http\Controllers\GradesController; // GradesController を追加
use Illuminate\Support\Facades\Auth;

Route::get('/', [WelcomeController::class, 'index']);

Auth::routes();

// 学生の検索ルートを追加
Route::get('/students/search', [NewStudentController::class, 'search'])->name('students.search');

// 学生のソートルートを追加
Route::get('/students/sort', [NewStudentController::class, 'sort'])->name('students.sort');

// 学生詳細画面の成績フィルタリングルートを追加
Route::get('/students/{student}/filterStudentGrades', [NewStudentController::class, 'filterStudentGrades'])->name('students.filterStudentGrades');

// 学年でソートするルートを追加
Route::get('/students/{student}/sortStudentGrades', [NewStudentController::class, 'sortStudentGrades'])->name('students.sortStudentGrades');

// 学年更新ルートの追加
Route::get('/schoolGrades/update-grades', [SchoolGradeController::class, 'updateStudentGrades'])->name('schoolGrades.update-student-grades'); // 追加

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    // 学生リソースルートを追加
    Route::get('/students', [NewStudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [NewStudentController::class, 'createForm'])->name('students.create');
    Route::post('/students', [NewStudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [NewStudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [NewStudentController::class, 'editForm'])->name('students.edit');
    Route::put('/students/{student}', [NewStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [NewStudentController::class, 'destroy'])->name('students.destroy');

    // 成績リソースルートを追加
    Route::get('/schoolGrades', [SchoolGradeController::class, 'index'])->name('schoolGrades.index');
    Route::get('/schoolGrades/create', [SchoolGradeController::class, 'createForm'])->name('schoolGrades.create');
    Route::post('/schoolGrades', [SchoolGradeController::class, 'store'])->name('schoolGrades.store');
    Route::get('/schoolGrades/{schoolGrade}', [SchoolGradeController::class, 'show'])->name('schoolGrades.show');
    Route::get('/schoolGrades/{schoolGrade}/edit', [SchoolGradeController::class, 'editStudentGradeForm'])->name('schoolGrades.edit'); // 修正
    Route::put('/schoolGrades/{schoolGrade}', [SchoolGradeController::class, 'update'])->name('schoolGrades.update');
    Route::delete('/schoolGrades/{schoolGrade}', [SchoolGradeController::class, 'destroy'])->name('schoolGrades.destroy');

    // Grades リソースルートを追加
    Route::get('/grades', [GradesController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [GradesController::class, 'createForm'])->name('grades.create');
    Route::post('/grades', [GradesController::class, 'store'])->name('grades.store');
    Route::get('/grades/{grade}', [GradesController::class, 'show'])->name('grades.show');
    Route::get('/grades/{grade}/edit', [GradesController::class, 'editForm'])->name('grades.edit');
    Route::put('/grades/{grade}', [GradesController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [GradesController::class, 'destroy'])->name('grades.destroy');
    
    // 学年更新ルートの追加
    Route::get('/grades/update-grades', [GradesController::class, 'updateStudentGrades'])->name('grades.update-student-grades');
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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SchoolGradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\NewStudentController;
use Illuminate\Support\Facades\Auth;

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// 認証ルート
Auth::routes();

// ゲスト用ルート
Route::middleware('guest')->group(function () {
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

// ログアウトルート
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// 学生関連ルート
Route::get('/students/search', [NewStudentController::class, 'search'])->name('students.search');
Route::get('/students/sort', [NewStudentController::class, 'sort'])->name('students.sort');

// 学生個別成績のフィルターとソート
Route::get('/students/{student}/filterStudentGrades', [NewStudentController::class, 'filterStudentGrades'])->name('students.filterStudentGrades');
Route::get('/students/{student}/sortStudentGrades', [NewStudentController::class, 'sortStudentGrades'])->name('students.sortStudentGrades');

// 成績検索機能ルート（SchoolGradeControllerからの検索に対応）
Route::get('/students/{student}/grades/search', [SchoolGradeController::class, 'search'])->name('students.filterGrades'); // 修正済み

// 学年更新ルート
Route::post('/students/update-grades', [StudentController::class, 'updateStudentGrades'])->name('students.update-grades');

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    // 学生登録関連ルート
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show'); // 学生詳細画面へのルート
    Route::resource('students', StudentController::class)->except(['create']); // createは個別定義

    // 成績管理ルート（個別定義）
    Route::get('/schoolGrades', [SchoolGradeController::class, 'index'])->name('schoolGrades.index'); // 成績一覧
    Route::get('/schoolGrades/create', [SchoolGradeController::class, 'create'])->name('schoolGrades.create'); // 成績作成フォーム
    Route::post('/schoolGrades', [SchoolGradeController::class, 'store'])->name('schoolGrades.store'); // 成績保存
    Route::get('/schoolGrades/{id}', [SchoolGradeController::class, 'show'])->name('schoolGrades.show'); // 特定の成績表示
    Route::get('/schoolGrades/{id}/edit', [SchoolGradeController::class, 'edit'])->name('schoolGrades.edit'); // 成績編集フォーム
    Route::put('/schoolGrades/{id}', [SchoolGradeController::class, 'update'])->name('schoolGrades.update'); // 成績更新
    Route::delete('/schoolGrades/{id}', [SchoolGradeController::class, 'destroy'])->name('schoolGrades.destroy'); // 成績削除
});
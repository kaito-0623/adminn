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
use App\Http\Controllers\UserController; // ✅ 追加
use Illuminate\Support\Facades\Auth;

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// 認証ルート
Auth::routes(['verify' => true]);


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
Route::post('/students/update-all-grades', [StudentController::class, 'updateAllGrades'])->name('students.update-all-grades'); // **追加**

// 認証が必要なルート
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

    // ✅ ユーザー情報関連ルート（追加）
    // ✅ ユーザー一覧ページのルートを追加
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // ⭐ 追加
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // 学生登録関連ルート
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/students/{student}/update', [StudentController::class, 'update'])->name('students.update'); // Route::putから変更
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show'); // 学生詳細画面へのルート
    Route::post('/students/{student}/delete', [StudentController::class, 'destroy'])->name('students.destroy'); // Route::deleteから変更
    Route::get('/students', [StudentController::class, 'index'])->name('students.index'); // **追加**

    // 成績管理ルート（個別定義）
    Route::get('/schoolGrades', [SchoolGradeController::class, 'index'])->name('schoolGrades.index'); // 成績一覧
    Route::get('/schoolGrades/create', [SchoolGradeController::class, 'create'])->name('schoolGrades.create'); // 成績作成フォーム
    Route::post('/schoolGrades', [SchoolGradeController::class, 'store'])->name('schoolGrades.store'); // 成績保存
    Route::get('/schoolGrades/{id}', [SchoolGradeController::class, 'show'])->name('schoolGrades.show'); // 特定の成績表示
    Route::get('/schoolGrades/{id}/edit', [SchoolGradeController::class, 'edit'])->name('schoolGrades.edit'); // 成績編集フォーム
    Route::post('/schoolGrades/{id}/update', [SchoolGradeController::class, 'update'])->name('schoolGrades.update'); // Route::putから変更
    Route::post('/schoolGrades/{id}/delete', [SchoolGradeController::class, 'destroy'])->name('schoolGrades.destroy'); // Route::deleteから変更
});